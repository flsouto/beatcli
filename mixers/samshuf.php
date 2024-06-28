<?php

return function($a,$b){
    normalize_speed($a,$b);
    first_segment($a);
    first_segment($b);
    $size = 16;
    $smps[] = $a->split($size);
    if(mt_rand(0,1)) $smps[] = $b->split($size);
    shuffle($smps[0]);
    if(isset($smps[1])) shuffle($smps[1]);
    $out = $a::silence(0);
    $chop = mt_rand(0,1);
    for($i=0;$i<$size;$i++){
        $s = $smps[array_rand($smps)][$i];
        if(!mt_rand(0,3)) $s->chop(mt_rand(2,8));
        $out->add($s->fade(0,-mt_rand(20,50)));
    }
    $out->x(4);
    if(mt_rand(0,1)) $out->reverb();
    if($out->len()>20) $out->resize(mt_rand(9,20));
    return $out;
};
