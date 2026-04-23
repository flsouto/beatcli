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
    $f = fn($s) => mt_rand(0,1) ? $s->chop($r()) : $s->reverse();
    $f($x->part('-1/'.$r()))->sync();
    if(!isset($s->skip_last_remix)){
        if(mt_rand(0,2)) $f = fn($s) => $s->{mt_rand(0,1)?'reverb':'oops'}();
        $f($y->part('-1/'.$r()))->sync();
    }

    return $s::join([$a,$b,$c,$d]);

};
