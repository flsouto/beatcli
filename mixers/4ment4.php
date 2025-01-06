<?php
return function($a,$b){

    $b->resize($a->len());

    [$s] = $a->split(4)[0]->split(16);
    $b = $b->split(64);

    $l1 = [];
    $l2 = [];
    $c4 = $s()->chop(mt_rand(0,1)?2:4);

    for($i=1;$i<=64;$i++){
        if(mt_rand(0,3)){
            $l1[] = $c4;
        } else {
            $l1[] = $s()->mod('gain -100');
        }
        $t = array_shift($b);
        if(mt_rand(0,1)){
            if(!mt_rand(0,3)){
                $t->chop(mt_rand(1,16));
            } else {
                $t->mod('gain -100');
            }
        }
        $l2[] = $t;
    }

    $layer1 = $s::join($l1);

    $layer2 = $s::join($l2);

    return $layer1->mix($layer2,false);



};

