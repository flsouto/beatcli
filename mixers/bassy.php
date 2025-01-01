<?php

return function($a,$b,$c){
//    $f = require(mt_rand(0,1)?'samshuf.php':'meshterns.php');
    $f = require('samshuf.php');
    $r = $f($a,$b,$c);
    $r->lowpass(mt_rand(150,200));
    $r->overdrive(mt_rand(20,30));
    if(mt_rand(0,1)){
        if(mt_rand(0,1)) $r->mod('synth '.(mt_rand(0,1)?'sin':'square').' fmod '.mt_rand(100,150).' '.mt_rand(20,80));
        if(mt_rand(0,1)) $r->oops();
        if(mt_rand(0,1)) $r->pitch(mt_rand(0,300));
    }
    $r->gain('-2');
    if($r->len() > 7){
        $r->speed(mt_rand(10,20)/10);
    }
    return $r;
};
