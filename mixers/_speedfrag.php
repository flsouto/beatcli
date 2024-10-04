<?php

return function($a,$b){
    first_segment($a);
    $a = $a->split(16);
    $len = $a[0]->len();
    $final = $b::silence(0);
    $base = $a[0]()->mod('gain -100');
    foreach($a as $s){
        $s = $base()->mix(
            $s()->mod('tempo '.(mt_rand(12,18)/10))
        ,false);
        $final->add($s);
    }
    return $final->reverb()->x(4);
};
