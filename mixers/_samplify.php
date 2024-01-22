<?php

return function($a){

    if(mt_rand(0,1)) first_segment($a);

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
    return $out;
};
