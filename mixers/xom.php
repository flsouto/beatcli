<?php

return function($a, $b,$c){

    first_segment($a,$b,$c);
    normalize_speed($a,$b);
    $c->resize($a->len());

    [$a1,$a2,$a3,$a4] = $a->split(4);
    [$b1,$b2,$b3,$b4] = $b->split(4);

    $layer = $a()->mod('gain -100');
    $len = $layer->len();
    $a1->chop(mt_rand(2,3));
    $silence = $a1()->mod('gain -100');
    $sn = $b2()->cut(0,'1/2');
    $b2 = $silence()->add($sn);
    $layer->mix($b2,false);
    if(mt_rand(0,1)){
        $b3 = $silence()->x(3)->add($sn()->chop(mt_rand(2,16)));
    } else {
        $b3 = $silence()->x(3)->add($sn());
    }
    $layer->mix($b3,false);
    $layer->mod('reverb delay .'.mt_rand(2,3))->cut(0,$len);
    $layer->mix($a1);
    $layer->mix($c->fade(-15,0));
    return $layer->x(4);
};
