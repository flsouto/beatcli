<?php
require_once(__DIR__."/../Pattern.php");

return function($l1, $l2, $l3){
    $a = $l1->pick(1)->fade(0,-mt_rand(10,20));
    $b = $l2->pick(1)->fade(0,-mt_rand(10,20));
    $c = $l3->pick(1)->fade(0,-mt_rand(10,20));
    $_d = null;
    $d = function() use($l1,$l2,$l3,&$_d){
        if($_d) return $_d;
        $l = [$l1,$l2,$l3][mt_rand(0,2)];
        $_d = $l->pick($l->len()/16)->fade(0,-mt_rand(10,20));
        return $_d;
    };
    $p = pattern()->with(compact('a','b','c','d'));
    $loop = $p()->x(4)->resize(8);
    if(mt_rand(0,1)) $loop->reverb();
    return $loop;
};
