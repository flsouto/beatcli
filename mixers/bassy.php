<?php

return function($a,$b){
    $f = require('samshuf.php');
    $r = $f($a,$b);
    $r->lowpass(mt_rand(150,200));
    $r->overdrive(mt_rand(30,35));
    $r->gain('-8');
    if($r->len() > 7){
        $r->speed(mt_rand(10,20)/10);
    }
    return $r;
};
