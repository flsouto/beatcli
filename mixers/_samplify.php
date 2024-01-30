<?php

return function($a){

    if($a->len() > 20) $a=$a->pick(20);
    if(mt_rand(0,1)) first_segment($a);
    if(mt_rand(0,1)) $a->fade(0,-20);
    $a = $a->resize(mt_rand(1,6)/10)->mod('lowpass '.mt_rand(300,800));
    $out = $a()->add(
        $a()->mod('gain -5')
    )->add(
        $a()->mod('gain -10')
    )->add(
        $a()->mod('gain -15')
    )->x(4);
    if(mt_rand(0,1)) $out->reverb();
    if(mt_rand(0,1)) $out->oops();
    if($out->len() < 2){
        $out->x(4);
    }
    if($out->len() < 4){
        $out->x(2);
    }

    return $out;
};
