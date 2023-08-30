<?php

return function($loop1, $loop2){
    normalize_speed($loop1, $loop2);
    if(mt_rand(0,1)) first_segment($loop1,$loop2);

    [$a,$b] = $loop1->split(2);
    $b->fade(0,-20);
    $a->fade(-20,0);
    $loop1 = $a->mix($b,false);

    [$a,$b] = $loop2->split(2);
    $b->fade(0,-20);
    $a->fade(-20,0);
    $loop2 = $a->mix($b,false);

    return $loop1->mix($loop2, true)->x(4);
};
