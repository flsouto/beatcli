<?php
return function($a,$b,$c){
    first_segment($a, $b, $c);
    normalize_speed($a,$b);
    $c->resize($a->len());

    $a = $a->split(8);
    $b = $b->split(8);
    $c = $c->split(8);


    $layer1 =  $b[0]()->mod('speed .5')
        ->add($c[4]->mod('gain -20'))
        ->add($a[4])
        ->add($b[5])
        ->add($a[5]->mod('gain -20'))
        ->add($a[4]()->mod('speed .5'))
        ->mod('lowpass 100');


    $layer2 =  $b[1]()->mod('speed .5')
        ->add($c[2])
        ->add($a[5])
        ->add($b[5])
        ->add($a[1]->mod('gain -10'))
        ->add($a[1]()->mod('speed .5'));

    $out = $layer2->mix($layer2,1);
    if(rand(0,1)) $out->add($layer1);
    $out->x(4)->resize(mt_rand(10,16));
    return $out;
};
