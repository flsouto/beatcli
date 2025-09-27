<?php

return function($a,$b,$c){
    $pattern = mt_rand(0,1) ? 'noise' : 'patterns';
    $noise = require(__DIR__."/$pattern.php");
    $o = $noise($a,$b,$c);
    $oops = mt_rand(0,1)?'oops':'';
    $o = $o->mod("reverb overdrive ".mt_rand(20,30)." speed .".mt_rand(5,7)." $oops ");
    if(!mt_rand(0,5)){
        $o = $o->mod(" tremolo ".mt_rand(3,33)." ".mt_rand(80,90));
    }

    $bender = require(__DIR__."/bender.php");
    $o = $bender($o,$o,$o);
    if(mt_rand(0,1)){
        $dim = require(__DIR__."/dim.php");
        $o = $dim($o,$a);
    }
    if(mt_rand(0,1)) $o->reverse();
    return $o->resize(20);
};
