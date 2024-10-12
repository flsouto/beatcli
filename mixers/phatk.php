<?php

return function($a){

    first_segment($a);

    $levels = mt_rand(3,16);

    $phatk = null;
    $shifter = null;
    switch(mt_rand(1,3)){
        case 1:
            $shifter = fn($x) => $x->mod('tempo 2');
        break;
        case 2:
            $shifter = fn($x) => $x->mod('speed 2');
        break;
        case 3:
            $shifter = fn($x) => $x->mod((mt_rand(0,1)?'tempo':'speed').' 2');
        break;
    }

    $phatk = function($a,$level=1) use(&$phatk,$levels,$shifter){
        if($level == $levels){
            return $a;
        }
        $p1 = $a->split(2)[0];
        $p2 = $shifter($a());
        $p2 = $phatk($p2, ++$level);
        return $p1->add($p2);
    };

    $result = $phatk($a)->x(4);
    $result->keepgain = true;
    return $result;

};
