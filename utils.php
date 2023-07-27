<?php
use FlSouto\Sampler;
$config = require(__DIR__."/config.php");
require_once($config['smp_path']);

function cleanup_sampler_tmp_dir(){
	$config = require(__DIR__."/config.php");
	$dir = dirname($config['sampler_path']).'/tmp_dir/';
	shell_exec("rm $dir/*.wav");
}

function mixers(array $options = []){
    $files = glob(__DIR__."/mixers/*.php");
    $mixers = [];
    foreach($files as $f){
        $key = str_replace('.php','',basename($f));
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
