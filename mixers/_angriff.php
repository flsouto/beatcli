<?php

return function($a,$b){
    $f = require('_fuzzer.php');
    $a = $f($a);
    $b = $f($b);
    $f = require('m4ze.php');
    $o = $f($a, $b);
    if(mt_rand(0,1)){
        return $o->flanger();
    } else {
        $f = require('bender.php');
        return $f($o);
    }
};
