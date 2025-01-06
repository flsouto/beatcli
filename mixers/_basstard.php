<?php

return function($a,$b,$c){
    $f = require('samshuf.php');
    $r = $f($a,$b,$c);
    [$a,$b,$c,$d] = $r->split(4);
    $b = $b->pitch('-666');
    if(mt_rand(0,1)){
        [$d1,$d2] = $d->split(2);
        $d1->pitch(333);
        $d2->pitch(111);
        $d = $d::join([$d1,$d2]);
    } else {
        $d = $d->pitch(666);
    }
    $r = $a::join([$a,$b,$c,$d]);
    $r->lowpass(mt_rand(150,200));
    $r->overdrive(mt_rand(20,30));
    if($r->len() > 7){
        $r->speed(mt_rand(10,20)/10);
    }
    return $r;
};
