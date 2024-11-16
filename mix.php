<?php
use FlSouto\Sampler;
require_once(__DIR__."/utils.php");

if(($argv[1]??'')=='rand') $argv[1] = null;
$mixer = $argv[1] ?? array_rand(mixers());
echo "Using mixer: $mixer\n";

$func = require "mixers/$mixer.php";

$glob = $argv[2] ?? $config['ipt_glob'];
$loops = glob($glob,GLOB_BRACE);


$seed = date('Ymd') + (getenv('seed')?:time());
echo 'SEED: '.$seed."\n";
srand($seed);
shuffle($loops);


$loop1 = new Sampler($loops[0]);
$loop2 = new Sampler($loops[1]??$loops[0]);
$loop3 = new Sampler($loops[2]??$loops[1]??$loops[0]);
$loop4 = new Sampler($loops[3]??$loops[2]??$loops[1]??$loops[0]);

$result = $func($loop1, $loop2,$loop3,$loop4);
$result->maxgain();
$result->save(__DIR__."/stage.wav");
//$result->part('3/4')->play();
$result->play();

