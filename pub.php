<?php
use FlSouto\Sampler;

$config = require(__DIR__."/config.php");
require_once($config['smp_path']);

$prefix = getenv('PREFIX');

$path = $config['pub_path'];

if(!is_dir($path)){
    mkdir($path,0777,true);
}

$out = Sampler::select(__DIR__."/stage.wav");
$hash = $out->hash();
$out->save($f=$path."/$prefix$hash.wav");
echo "Saving to $f\n";

