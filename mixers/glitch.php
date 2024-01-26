<?php

return function($a, $b){
    first_segment($a,$b);
    normalize_speed($a,$b);
    $parts_a = $a->split(16);
    $parts_b = $b->split(16);
    $pitch = 0;
    $out = silence(0);
    $chop = mt_rand(0,1);
    $extras = mt_rand(0,1);
    foreach($parts_a as $i => $p){
        $p = mt_rand(0,1) ? $p : $parts_b[$i];
        $p->mod('fade .005 0 .005');
        $pitch = 10;
        if(!$extras || mt_rand(0,1)){
            for($i=1;$i<=16;$i++){
                $p->pitch($pitch);
                $pitch += mt_rand(5,20);
            }
        } else {
            if($chop && mt_rand(0,1)){
                $p->chop(mt_rand(16,99));
            } else {
                $p->mod('tempo .5')->cut(0,'1/2');
            }
        }
        $out->add($p);
    }
    return $out->x(4);
};
