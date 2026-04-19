<?php

return function($a){
    $f = require('samplicat.php');
    $s = $f($a);
    $s->mod('tempo .2 reverb');
    $f = require('bender.php');
    $s = $f($s);
    return $s;
};
