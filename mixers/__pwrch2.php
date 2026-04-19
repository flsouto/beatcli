<?php

return function($a,$b){
    $f = require('_pwrch.php');
    $riff = $f($a);
    $riffb = $f($a);

    $riffb->pitch(-666)->lowpass(300);
    $f = require('m4ze.php');
    return $f($riff, $riffb, $riffb);
};
