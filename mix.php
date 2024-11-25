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

$glob = $argv[2] ?? $config['out_path']."/*.wav";
$loops = glob($glob,GLOB_BRACE);

shuffle($loops);

$loop1 = new Sampler($loops[0]);
$loop2 = new Sampler($loops[1]??$loops[0]);
$loop3 = new Sampler($loops[2]??$loops[1]??$loops[0]);

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
$result->save($f);
//$result->part('3/4')->play();
if(!getenv('noplay')){
    shell_exec('play stage.wav');
}

