<?php
return function($a,$b){

    $b->resize($a->len());

    [$s] = $a->split(4)[0]->split(16);
    $b = $b->split(64);

    $layer1 = $a::silence(0);
    $layer2 = $a::silence(0);
    $c4 = $s()->chop(4);
    for($i=1;$i<=64;$i++){
        if(!mt_rand(0,4)){
            $layer1->add($c4);
        } else {
            $layer1->add($s);
        }
        $t = array_shift($b);
        if(mt_rand(0,1)){
            if(!mt_rand(0,3)){
                $t->chop(mt_rand(1,16));
            } else {
                $t->mod('gain -100');
            }
        }
        $layer2->add($t);
    }

    return $layer1->mix($layer2,false);



};

