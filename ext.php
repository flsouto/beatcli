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

$result = $func(...array_map(fn($f) => new Sampler($f), array_slice($loops,0,6)));
$result->maxgain();
$result->save(__DIR__."/stage.wav");

$result->play();

