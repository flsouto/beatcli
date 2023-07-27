<?php
use FlSouto\Sampler;
require_once(__DIR__."/utils.php");

$mixer = $argv[1] ?? array_rand(mixers());
echo "Using mixer: $mixer\n";

$func = require "mixers/$mixer.php";

$loops = glob($config["out_path"]."/*.wav");
shuffle($loops);

$loop1 = new Sampler($loops[0]);
$loop2 = new Sampler($loops[1]);
$loop3 = new Sampler($loops[2]);

$result = $func($loop1, $loop2, $loop3);
$result->maxgain();
$result->save(__DIR__."/stage.wav");

$result->play();

