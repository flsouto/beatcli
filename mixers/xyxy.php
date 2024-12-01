<?php

return function($a,$b){
    $a = $a->cut(0,'1/4');
    $b = $b->cut(0,'1/4');
    $b->resize($a->len());
    $c = $b();
    /*
    if(mt_rand(0,1)){
        $d = $c->part('1/2')->chop(4);
        if(mt_rand(0,1)){
            $e = $d->part('1/2')->chop(4);
            $e->sync();
        }
        $d->sync();
    } */
    return $a()->add($b)->add($a)->add($c);
};
