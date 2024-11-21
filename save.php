<?php
use FlSouto\Sampler;

$config = require(__DIR__."/config.php");
require_once($config['smp_path']);

$prefix = getenv('PREFIX');

$path = $config['out_path'];

if(!is_dir($path)){
    mkdir($path,0777,true);
}

$out = Sampler::select(__DIR__."/stage.wav");
$hash = $out->hash();
$out->save($f=rtrim($path,"/")."/$prefix$hash.wav");
if($prefix && is_dir($pdir=__DIR__."/$prefix")){
    $out->save($pdir."/$prefix$hash.wav");
}

echo "Saving to ".str_replace(__DIR__."/","",$f)."\n";

