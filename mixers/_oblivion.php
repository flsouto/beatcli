<?php
return function($a, $b, $c, $fade=true){

    $pattern1 = function($l1, $l2, $l3){

    	$l1 = $l1()->cut(0,'1/4')->x(2);
    	$l1->add($l1()->mod('gain -100'));

    	$l2 = $l2()->cut(0,'1/4')->x(4);
    	$l2->part('-1/4')->fade(0,-50)->sync();

    	$l3 = $l3();

    	$l1->mix($l2);
    	$l1->mix($l3);

    	return $l1;

    };

    $l1 = $a;
    $len = $l1->len();

    $l2 = $b->resize($len);
    $l3 = $c->resize($len);

    $p1 = $pattern1($l1,$l3,$l2);
    $p2 = $pattern1($l1,$l2,$l3);


    $m1 = $l1()->chop(16);
    $p3 = $pattern1($m1,$l3,$l2);

    $m2 = $l2()->chop(16);
    $p4 = $pattern1($l1,$l3,$m2);
    if(mt_rand(0,1)){
        if($fade){
            $p3->part('14/16')->fade(0,-30)->sync();
        }
        if(rand(0,1)) {
//            $p4->part('15/16')->chop(16)->sync();
        }
        $out = $p4->add($p1)->add($p2)->add($p3);
    } else {
        if($fade){
            $p4->part('14/16')->fade(0,-30)->sync();
        }
        $out = $p1->add($p2)->add($p3)->add($p4);
    }
    if(mt_rand(0,1)) $out->speed('.5');
    return $out;
};
