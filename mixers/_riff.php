<?php

return function($a,$b,$c){
//    $f = require(mt_rand(0,1)?'samshuf.php':'meshterns.php');
    $f = require('samshuf.php');
    $r = $f($a,$b,$c);
    if(mt_rand(0,1)) $r->cut(0,'1/2');
    $r->lowpass(800);
    $r->mod('synth sin fmod 100 '.mt_rand(5,80));
    $r->chop(mt_rand(328,666))->reverse();
    $r->overdrive(mt_rand(40,50));
    if(mt_rand(0,1)) $r->mod('tempo .8');
    $r->pitch(-mt_rand(100,300));

    if(mt_rand(0,1)) $r->oops()->reverb();

    if(mt_rand(0,1)){
        $r3 = $r::silence(.1)->add($r)->pitch('500')->mix($r,false);
    } else {
        $r3 = $r;
    }
    $r2 = $r()->mod('gain -100');

    $f = require(mt_rand(0,1)?'m4ze.php':'maz3.php');
    $r = $f($r3,$r2,$r3);
    [$a,$b] = $r->split(2);
    $b->fade(0,-30);
    $a->fade(-30,0);
    $p1 = $a->mix($b,false);
    $p1->x(2);
    if(mt_rand(0,1)){
        $f = require(mt_rand(0,1)?'melodize.php':'bender.php');
        return $f($p1);
    }
    return $p1;
};
