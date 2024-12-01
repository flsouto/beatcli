<?php

return function($a, $b, $c){
    $f = require(__DIR__.'/patterns.php');
    $p1 = $f($a,$b,$c);
    $p2 = $f($a,$b,$c);
    first_segment($p1,$p2);
    $p2->resize($p1->len());
    [$a] = $p1->split(2);
    [$_,$b] = $p2->split(2);
    return $a->add($b)->x(4);
};
