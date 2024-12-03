<?php

return function($a){
    if(mt_rand(0,1)){
        $bit = $a->pick(16);
        $x = 2;
    } else {
        $bit = $a->pick(8);
        $x = 4;
    }
    [$a,$b] = $bit->split(2);
    $b = $b->fade(0,-30);

    $a = $a->fade(-30,0);
    $a = $a->mix($b,false)->x($x);
    return $a;
};
