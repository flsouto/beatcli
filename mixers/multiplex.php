<?php

return function($loop1, $loop2){

    first_segment($loop1, $loop2);

    [$a1,$b1,$c1,$d1] = $loop1->split(4);
    [$a2,$b2,$c2,$d2] = $loop2->split(4);

    if(mt_rand(0,1)) normalize_speed($loop1, $loop2);

    if(mt_rand(0,1)){
        $result = $a1->add($b2)->add($c1)->add($d2);
    } else {
        $result = $a1->add($b2)->add($c1)->add($d2);
    }
    return $result->x(4);
};

