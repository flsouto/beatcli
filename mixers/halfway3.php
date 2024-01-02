<?php

return function($a,$b,$c){
    first_segment($a,$b,$c);
    normalize_speed($a,$b);
    $c->resize($a->len());
    $afout = $a()->fade(0,-15);
    $p1 = $afout()->mix($b()->fade(-15,0));
    $p2 = $afout()->mix($c()->fade(-15,0));
    return $p1->add($p2)->x(4);
};
