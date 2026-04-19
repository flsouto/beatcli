<?php

return function($a){
    
    $a = $a->pick(5)->chop(mt_rand(666,900));
    $parts = [];
    $muter = null;
    switch(mt_rand(1,3)){
        case 1:
            $muter = fn($s) => $s->oops();
        break;
        case 2:
            $muter = fn($s) => $s->gain('-10');
        break;
        case 3:
            $muter = fn($s) => $s->gain('-100');
        break;
    }
    foreach($a->split(64) as $s){
        if(!mt_rand(0,2)){
            $muter($s);
        }
        else if(!mt_rand(0,2)){
            $s->pitch(mt_rand(100,1000));
        }
        $parts[] = $s;
    }
    $out = $a::join($parts);
    $f = require('adrenaline.php');
    $a = $f($out);
    $a->overdrive('30');
    return $out->tempo(1.2);

};
