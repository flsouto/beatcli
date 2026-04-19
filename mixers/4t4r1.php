<?php

return function($a){
    first_segment($a);
    $x=2;
    if(mt_rand(1,2)){
        $x=4;
        first_segment($a);
    }
    $arr = [];
    $chop = mt_rand(2,9);
    foreach($a->split(16) as $s){
        if(mt_rand(0,1)){
            $tail = $s()->part('-1/2')->chop($chop)->mod('tempo .666 oops');
        } else {
            $tail = $s()->part('-1/2')->chop($chop)->mod('speed .666 gain -5');
        }
        $arr[] = $s->add($tail);
    }
    if(mt_rand(0,1)){
        foreach($a->split(8) as $s){
            if(mt_rand(0,1)){
                $tail = $s()->part('-1/2')->chop($chop)->mod('tempo .666');
            } else {
                $tail = $s()->part('-1/2')->chop($chop*2)->mod('speed .666 gain -5');
            }
            $arr[] = $s->add($tail);
        }
    } else {
        $x*=2;
    }

    $out = $a::join($arr)->x($x);
    $out->keepgain = true;
    return $out;
};
