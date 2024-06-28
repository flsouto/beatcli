<?php
require_once(__DIR__."/utils.php");
$config = require(__DIR__."/config.php");

$out = __DIR__."/decorated/";

if(!is_dir($out)){
    mkdir($out, 0777);
}


foreach(glob(__DIR__."/out/*.wav") as $i => $f){
    $mask = FlSouto\Sampler::select($argv[1]);
    echo "At $i\n";
    $loop = sampler($f);
    if($loop->len() < 12) continue;
    $len = $loop->len();
    $pick = $len / 16;
    $mix = $mask->pick($pick)->mod('fade .005 0 .005 gain -8')->x(4)->resize($len);
    $loop->mix($mix, true);
    $loop->maxgain();
    $loop->save($out. "/".basename($f));
}
