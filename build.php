<?php
use FlSouto\Sampler;

$config = require(__DIR__."/config.php");
require_once($config['smp_path']);

$srand = $argv[1] ?? time();

function s($s){
    global $srand;
    srand($srand + $s);
    return Sampler::select(__DIR__."/out/*.wav");
}

$s1 = s(1)->mod('speed .5');
$len = $s1->len();

$out = $s1()->mod('lowpass 400 oops gain 5')->cut(0,'1/2')->add($s1);
$out->add($s1()->mix(s(2)->x(4)->resize($len),false));

$s3 = s(2)->x(2)->resize($len);
$out->add($s3);

$out->add($s3()->mix(s(10)->x(4)->resize($len),false));
$out->add($s3()->mod('oops')->mix(s(12)->mod('gain -6')->resize($len),false));

$final = $s3()->mod('speed .5 lowpass 500');
$final->part('1/2')->fade(0,-40)->sync();
$out->add($final);

echo 'SEED: '.$srand."\n";
$out->save('out.wav');
