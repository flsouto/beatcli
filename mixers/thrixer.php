<?php

return function($a, $b, $c){
    first_segment($a,$b,$c);
    normalize_speed($a,$b);
    $c->resize($b->len());

    [$a1,$a2,$a3,$a4] = $a->split(4);
    [$b1,$b2,$b3,$b4] = $b->split(4);
    [$c1,$c2,$c3,$c4] = $c->split(4);

    if(mt_rand(0,3)){
        if(mt_rand(0,1)){
            $always = [1,1,1];
        } else {
            $always = [mt_rand(0,1),mt_rand(0,1),mt_rand(0,1)];
        }

        $parts = ['-1/2','-1/4','-1/8','-1/16'];
        shuffle($parts);
        $shuf_parts = mt_rand(0,1);
        $out = silence(0);
        for($i=1;$i<=4;$i++){
            $a = ${"a$i"}->mod('gain '.mt_rand(-15,0));
            $b = ${"b$i"}->mod('gain '.mt_rand(-15,0));
            $c = ${"c$i"}->mod('gain '.mt_rand(-15,0));
            if($shuf_parts){
                shuffle($parts);
            }
            ($always[0] || mt_rand(0,1)) && $a->part($parts[0])->mod('gain -100')->sync();
            ($always[1] || mt_rand(0,1)) && $b->part($parts[1])->mod('gain -100')->sync();
            ($always[2] || mt_rand(0,1)) && $c->part($parts[2])->mod('gain -100')->sync();
            $out->add($a->mix($b)->mix($c));
        }
        return $out->x(4);
    }

    $a1->mix($b1);
    $a2->mod('gain -15');
    $b2->mod('gain -5');
    $a2->mix($b2);
    $a3->mix($b3)->mix($c3->mod('gain -5'));
    $a4->mod('gain -15')->mix($b4->mod('gain -10'))->mix($c4);
    return $a1->add($a2)->add($a3)->add($a4)->x(4);
};
