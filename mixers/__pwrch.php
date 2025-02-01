<?php

return function($a){
    $f = require('_pwrch.php');
    $riff = $f($a);
    $f = require('m4ze.php');
    $silence = $a->gain(-100);
    return $f($riff, $silence, $silence);
};
