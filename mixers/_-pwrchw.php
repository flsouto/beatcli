<?php

return function($a){
    $f = require('_pwrch.php');
    $riff = $f($a);
    $f = require('4ment4.php');
    $silence = $a->gain(-100);
    return $f($riff, $riff, $riff);
};
