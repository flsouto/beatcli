<?php
use FlSouto\Sampler;
require_once(__DIR__."/utils.php");

if(($argv[1]??'')=='rand') $argv[1] = null;
$mixer = $argv[1] ?? array_rand(mixers());
echo "Using mixer: $mixer\n";

$func = require "mixers/$mixer.php";

$glob = $argv[2] ?? $config['out_path']."/*.wav";
$loops = glob($glob);
shuffle($loops);

$loop1 = new Sampler($loops[0]);
$loop2 = new Sampler($loops[1]??$loops[0]);
$loop3 = new Sampler($loops[2]??$loops[1]??$loops[0]);

$result = $func($loop1, $loop2,$loop3);
$result->maxgain();
$result->save(__DIR__."/stage.wav");

$result->play();

