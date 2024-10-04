<?php

return function($a){
    $bit = $a->pick(mt_rand(16,32)/10);
    [$a,$b] = $bit->split(2);
    $b->fade(0,-20);
    $a->fade(-20,0);
    $a->mix($b,false);
    $bit->x(4);
    return $bit;
};
