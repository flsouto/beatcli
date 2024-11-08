<?php

return function($a){
    $a->mod('speed .2 lowpass 666');
    if(mt_rand(0,1)) $a->reverse();
    $bit = $a->pick(mt_rand(128,256)/10);
    [$a,$b] = $bit->split(2);
    $b = $b->fade(0,-30);
    $a = $a->fade(-30,0);
    return $a->mix($b,false)->x(64)->mod('reverb');
};
