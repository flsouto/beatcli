<?php

return function($a){

    first_segment($a);

    $arr = [];
    foreach(array_reverse($a->split(16)) as $s){
        if(mt_rand(0,1))
            $s->reverse();
        $arr[] = $s;
    }

    $o = $s::join($arr)->x(4);
    $o->keepgain = true;
    return $o;
};
