<?php

return function($a,$b,$c){
    $noise = require(__DIR__."/noise.php");
    $o = $noise($a,$b,$c);
    $oops = mt_rand(0,1)?'oops':'';
    $o = $o->mod("reverb overdrive ".mt_rand(20,30)." speed .".mt_rand(5,7)." $oops tremolo ".mt_rand(3,33)." ".mt_rand(80,90));

    if(mt_rand(0,1)){
        $bender = require(__DIR__."/bender.php");
        $o = $bender($o,$o,$o);
    }
    if(mt_rand(0,1)){
        $dim = require(__DIR__."/dim.php");
        $o = $dim($o,$o);
    }
    if(mt_rand(0,1)) $o->reverse();
    return $o->resize(20);
};
