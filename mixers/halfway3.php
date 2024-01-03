<?php

return function($a,$b,$c){
    first_segment($a,$b,$c);
    normalize_speed($a,$b);
    $c->resize($a->len());
    $afout = $a()->fade(0,-15);
    $p1 = $afout()->mix($b()->fade(-15,0));
    $p2 = $afout();
    if(mt_rand(0,1)){
        $p2->mod('pitch -'.mt_rand(40,80));
    }
    $p2->mix($c()->fade(-15,0));
    $out = $p1->add($p2);
    if($out->len() <= 9){
        $out->x(4);
    } else {
        $out->x(2);
    }
    return $out;
};
