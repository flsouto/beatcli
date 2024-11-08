<?php

return function($a){
    $a->mod('speed .'.mt_rand(3,6).' lowpass '.mt_rand(444,777));
    if(mt_rand(0,1)) $a->reverse();
    $bit = $a->pick(mt_rand(128,256)/10);
    [$a,$b] = $bit->split(2);
    $b = $b->fade(0,-30);

    $a = $a->fade(-30,0);
    $a = $a->mix($b,false)->x(64);
    if(mt_rand(0,1)){
        $a->mod('tremolo '.mt_rand(2,7).' '.mt_rand(40,60));
    }
    $a->mod('reverb');
    return $a;
};
