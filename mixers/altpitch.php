<?php

return function($a, $b){
    first_segment($a,$b);
    normalize_speed($a,$b);

    $out = $a::silence(0);

    $a = $a->split($parts = mt_rand(1,2)*4);
    $b = $b->split($parts);

    foreach($a as $i=>$x){
        $y = $b[$i];
        $filters = [
            'pitch '.mt_rand(50,80),
            'pitch -'.mt_rand(50,80)
        ];
        if(mt_rand(0,1)){
            $filters = array_reverse($filters);
        }
        $x->mod($filters[0]);
        $y->mod($filters[1]);
        $x->mix($y,true);
        $out->add($x);
    }
    return $out->x(4);
};
