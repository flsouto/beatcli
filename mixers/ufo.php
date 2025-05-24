<?php

return function($a,$b){
    $f = require(mt_rand(0,1)?'samplify.php':'samplicat.php');
    $s = $f($a,$b);
    $s->mod('tempo .2 reverb');
    $f = require('bender.php');
    return $f($s);
};
