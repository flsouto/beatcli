<?php

return function($a,$b,$c){
//    $f = require(mt_rand(0,1)?'samshuf.php':'meshterns.php');
    $f = require('samshuf.php');
    $r = $f($a,$b,$c);
    $r->lowpass(200);
    $r->mod('synth sin fmod 200 '.mt_rand(20,80));
    $r->overdrive(40);
    $r->pitch(mt_rand(0,300));

    $f = require('samshuf.php');
    $r2 = $f($c,$b,$a);
    $r2->lowpass(100);
    $r2->mod('oops  synth sin fmod 500 '.mt_rand(20,80));
    $r2->overdrive(20);
    $r2->pitch(-mt_rand(0,300));

    $f = require('maz3.php');
    $r = $f($r,$r2,$r)->mod('reverb');
    [$a,$b] = $r->split(2);
    $b->fade(0,-30);
    $a->fade(-30,0);
    return $a->mix($b,false)->x(2);
};
