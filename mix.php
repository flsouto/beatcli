<?php
use FlSouto\Sampler;
require_once(__DIR__."/utils.php");

if($seed=getenv('seed')){
    srand($seed);
}

if(($argv[1]??'')=='rand') $argv[1] = null;

if(empty($argv[1]) && getenv('loopmixer')){
    $argv[1] = getenv('loopmixer');
}

$mixer = $argv[1] ?? array_rand(mixers());
echo "Using mixer: $mixer\n";

$func = require "mixers/$mixer.php";

$globs = $argv[2] ?? $config['out_path']."/*.wav";

$groups = [];
foreach(explode(';',$globs) as $glob){
    $loops = glob($glob, GLOB_BRACE);
    shuffle($loops);
    $groups[] = $loops;
}

if(empty($groups)){
    die("No loops found\n");
}

function pick_loop($index){
    global $groups;
    while(!isset($groups[$index])){
        $index--;
        if($index < 0){
            break;
        }
    }
    $loop = array_shift($groups[$index]);
    if(empty($groups[$index])){
        $groups[$index][] = $loop;
    }
    return $loop;
}

$loop1 = new Sampler(pick_loop(0));
$loop2 = new Sampler(pick_loop(1));
$loop3 = new Sampler(pick_loop(2));

if($mseed=getenv('mseed')){
    srand($mseed);
}

$result = $func($loop1, $loop2,$loop3);
if(empty($result->keepgain) && !getenv('keepgain')){
    $result->maxgain();
}
$result = $result->rate('44100');
$f = __DIR__."/stage.wav";
if(file_exists($f)){
    copy($f, $f.'.bkp');
}
if($mod=getenv('m')){
    $result->mod($mod);
}
$result->save($f);
//$result->part('3/4')->play();

if($fx=getenv('c')){
    foreach(explode(',',$fx) as $m){
        shell_exec("m= c= noplay=1 php mix.php $m stage.wav");
    }
}

if(!getenv('noplay')){
    shell_exec('play stage.wav');
}

