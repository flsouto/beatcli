<?php

return function($a, $b){
    normalize_speed($a,$b);
    first_segment($a,$b);
    $a = $a->split(16);
    $b = $b->split(16);
    $layer1 = [-15, -15, -15, -12, -9,   -7,  -5,  -5, -5, -5, -7, -9, -12, -12, -14, -14];
    $layer2 = [-10, -10, -10, -13, -16, -19, -22, -22, -22,-22,-19,-16,-13, -10, -10, -10];
    $out = silence(0);
    foreach($layer1 as $i => $val){
        $k = $i;
        echo $val.'-'.$layer2[$i].PHP_EOL;
        $a[$k]->mod('gain '.$val);
        $b[$k]->mod('gain '.$layer2[$i]);
        $out->add($a[$i]->mix($b[$i]));
    }
    return $out->x(4);
};

