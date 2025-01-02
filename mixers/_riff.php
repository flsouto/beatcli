<?php

return function($a,$b,$c){
//    $f = require(mt_rand(0,1)?'samshuf.php':'meshterns.php');
    $f = require('samshuf.php');
    $r = $f($a,$b,$c);
    if(mt_rand(0,1)) $r->cut(0,'1/2');
    $r->lowpass(200);
    $r->mod('synth sin fmod 100 '.mt_rand(20,80));
    $r->chop(mt_rand(328,666));
    $r->overdrive(50);
    $r->pitch(-mt_rand(100,300));
    $r->oops()->reverb();
    $r2 = $r()->mod('gain -100');

    $f = require('m4ze.php');
    $r = $f($r,$r2,$r);
    [$a,$b] = $r->split(2);
    $b->fade(0,-30);
    $a->fade(-30,0);
    $p1 = $a->mix($b,false);
    $p1->x(2);
    if(mt_rand(0,1)){
        $f = require('melodize.php');
        return $f($p1);
    }
    return $p1;
};
