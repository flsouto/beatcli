<?php

return function($a){
    $bit = $a->pick(mt_rand(16,32)/10);
    [$a,$b] = $bit->split(2);
    $b = $b->fade(0,-30);
    $a = $a->fade(-30,0);
    return $a->mix($b,false)->x(4);
};
