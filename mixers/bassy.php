<?php

return function($a,$b,$c){
    $f = require(mt_rand(0,1)?'samshuf.php':'meshterns.php');
    $r = $f($a,$b,$c);
    $r->lowpass(mt_rand(150,400));
    $r->overdrive(mt_rand(10,35));
    $r->gain('-8');
    if($r->len() > 7){
        $r->speed(mt_rand(10,20)/10);
    }
    return $r;
};
