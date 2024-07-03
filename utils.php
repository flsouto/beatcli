<?php
use FlSouto\Sampler;
$config = require(__DIR__."/config.php");
require_once($config['smp_path']);

const RECURSIVE_MIXERS = ['chaos','mixfuse'];

function cleanup_sampler_tmp_dir(){
	$config = require(__DIR__."/config.php");
	$dir = dirname($config['sampler_path']).'/tmp_dir/';
	shell_exec("rm $dir/*.wav");
}

function mixers(array $options = []){
    global $config;
    $files = glob(__DIR__."/mixers/*.php");
    $mixers = [];
    foreach($files as $f){
        $key = str_replace('.php','',basename($f));
        if(substr($key,0,1)=='_'){
            continue;
        }
        if(!empty($config['use_mixers']) && !in_array($key, $config['use_mixers'])){
            continue;
        }
        $mixers[$key] = require $f;
        if(!empty($options['max_args'])){
            $reflection = new ReflectionFunction($mixers[$key]);
            $count = $reflection->getNumberOfParameters();
            if($options['max_args'] < $count){
                unset($mixers[$key]);
                continue;
            }
        }
    }
    return $mixers;
}

function normalize_speed($loop1,$loop2){
    if(mt_rand(0,1)){
        $len = ($loop1->len() + $loop2->len())/2;
        $loop1->resize($len);
        $loop2->resize($len);
    } else {
        $loop2->resize($loop1->len());
    }
}

function silence($time){
    return Sampler::silence($time);
}

function first_segment(...$loops){
    foreach($loops as $loop){
        $loop->cut(0,'1/4');
    }
}

function sampler($f){
    return new Sampler($f);
}

function get_fxs(){
    return [
        'synth' => function($s){
            $types = ['sin','square','triangle','sawtooth','noise','whitenoise'];
            $type = $types[array_rand($types)];
            $mods = ['fmod','amod'];
            $mod = $mods[array_rand($mods)];
            $x = mt_rand(50,100);
            $y = mt_rand(50,100);
            $s->mod("synth $type $mod $x $y");
        },
        'hlpass' => function($s){
            $type = mt_rand(0,1)?'highpass':'lowpass';
            $s->mod($type." ".mt_rand(200,4000));
        },
        'reverb' => function($s){
            $s->mod('reverb');
        },
        'pitch' => function($s){
            $s->mod('pitch '.mt_rand(-100,100));
        },
        'reverse' => function($s){
            $s->mod('reverse');
        },
        'chop' => function($s){
            $s->chop(mt_rand(2,8));
        },
        'flanger' => function($s){
            $s->mod('flanger');
        }
    ];
}

function apply_fx($s){
    $fx = get_fxs();
    $keys = array_keys($fx);
    shuffle($keys);
    $keys = array_slice($keys, 1, mt_rand(1,count($keys)));
    foreach($keys as $k){
        $fx[$k]($s);
    }
    return $s;
}

function x($out){
    if($out->len() <= 9){
        $out->x(4);
    } else {
        $out->x(2);
    }
    return $out;
}

function pick(array $array){
    return $array[array_rand($array)];
}
