<?php

return function($a,$b){
    normalize_speed($a,$b);
    first_segment($a);
    first_segment($b);
    $size = mt_rand(0,1)?16:32;
    $smps[] = $a->split($size);
    if(mt_rand(0,1)) $smps[] = $b->split($size);
    shuffle($smps[0]);
    if(isset($smps[1])) shuffle($smps[1]);
    $out = $a::silence(0);
    for($i=0;$i<$size;$i++){
        $s = $smps[array_rand($smps)][$i];
        $out->add($s);
    }
    $out->x(4);
    $out->mod('overdrive 60 lowpass 1500');
//    if(mt_rand(0,1)) $out->reverb();
    if($out->len()>20) $out->resize(mt_rand(9,20));
    return $out;
};
