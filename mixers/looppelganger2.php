<?php

return function($a, $b, $c){
    putenv('nofade=1');
    $o = require('_oblivion.php');
    $o = $o($a,$b,$c)->resize(mt_rand(120,180));
    $f = require('looppelganger.php');
    return $f($o,$b);
};
