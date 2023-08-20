<?php

return function($a, $b){
    normalize_speed($a,$b);
    if($a->len() > 12 || mt_rand(0,1)){
        first_segment($a,$b);
    }
    if(mt_rand(0,1)){
        if(mt_rand(0,1)){
            $b->mod('reverse');
        } else {
            $a->mod('reverse');
        }
    }
    $a = $a->split(16);
    $b = $b->split(16);
    $layer1 = [-21, -18, -15, -12, -9, -6, -3, 0, -12, -18, -30, -30, -30, -40, -40, -48];
    $layer2 = [-20, -20, -20, -20, -15, -10, -5, 0, -2,-4,-8,-10,-14, mt_rand(0,1) ? -4 : -18, -24, -30];
    $out = silence(0);
    switch(mt_rand(1,3)){
        case 1:
            $normal = fn() => true;
        break;
        case 2:
            $normal = fn() => false;
        break;
        case 3:
            $normal = fn() => mt_rand(0,1);
        break;
    }
    foreach($layer1 as $i => $val){
        $k = $i;
        echo $val.'-'.$layer2[$i].PHP_EOL;
        $a[$k]->mod('gain '.$val);
        $b[$k]->mod('gain '.$layer2[$i]);
        $out->add($a[$i]->mix($b[$i]),$normal());
    }
    return $out->x(4);
};

