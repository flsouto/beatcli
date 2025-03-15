<?php

return function($a,$b){
    $f = require('dim.php');
    $out = $f($a,$b);
    [$a,$b] = $out->split(2);
    $b->fade(0,-30);
    $a->fade(-30,0);
    $out = $a->mix($b,true);
    return $out->x(2);
};
