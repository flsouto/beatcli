<?php

return function($s) {

    [$a,$b,$c,$d] = $s->split(4);

    if(mt_rand(0,1)){
        $x = $b;
        $y = $d;
    } else {
        $x = $c;
        $y = $d;
    }

    $r = fn() => pow(2,mt_rand(1,getenv('remax')?:4));
    $x->part('-1/'.$r())->chop($r())->sync();
    $y->part('-1/'.$r())->chop($r())->sync();

    return $s::join([$a,$b,$c,$d]);

};
