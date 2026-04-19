<?php

return function($a,$b,$c){
    $f = require('bassy.php');
    $riff = $f($a, $b, $c);
    $f = require('m4ze.php');
    $silence = $a->gain(-100);
    return $f($riff, $silence, $silence);
};
