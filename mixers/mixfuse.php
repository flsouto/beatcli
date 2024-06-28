<?php

return function($a, $b){
    $mixers = mixers([
        'skip_recursive' => true,
        'max_args' => 2
    ]);
    $keys = array_keys($mixers);
    shuffle($keys);
    $keys = array_slice($keys, 0, 3);
    $mx1 = $mixers[$keys[0]];
    $mx2 = $mixers[$keys[1]];
    $mx3 = $mixers[$keys[2]];
    $o1 = $mx1($a,$b);
    $o2 = $mx2($a,$b);
    if(mt_rand(0,1)){
        first_segment($o1,$o2);
        normalize_speed($o1,$o2);
        $o1 = $o1->split(2);
        $o2 = $o2->split(2);
        $o = $o1[0]->add($o2[1])->x(4);
    } else {
        $o = $mx3($o1,$o2);
    }
    if($o->len() < 5){
        $o->resize(mt_rand(6,12));
    }
    return $o;
};

