<?php

return function($a, $b, $c){
    if($a->len() > 20) $a=$a->pick(20);
    if($b->len() > 20) $b=$b->pick(20);
    if($c->len() > 20) $c=$c->pick(20);

    first_segment($a);
    first_segment($b);
    first_segment($c);
    $samples = $a->split(16);
    $samples = [...$samples, ...$b->split(16)];
    $samples = [...$samples, ...$c->split(16)];
    shuffle($samples);
    $a = $a::silence(0);
    foreach(array_slice($samples,0,mt_rand(8,16)) as $s){
        $a->add($s);
    }

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
