<?php
use FlSouto\Sampler;

$config = require(__DIR__."/config.php");
require_once($config['smp_path']);

$path = $config['pub_path'];

if(!is_dir($path)){
    mkdir($path,0777,true);
}

$out = Sampler::select(__DIR__."/stage.wav");
$hash = $out->hash();
$out->save($f=$path."/$hash.wav");
echo "Saving to $f\n";

