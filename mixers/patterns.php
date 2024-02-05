<?php
require_once(__DIR__."/../Pattern.php");

return function($l1, $l2, $l3){
    $l1->resize($len1=mt_rand(2,8));
    $l2->resize($len2=mt_rand(2,8));
    $l3->resize($len3=mt_rand(2,8));
    $a = $l1->pick($len1/16)->fade(0,-mt_rand(10,20));
    $b = $l2->pick($len2/16)->fade(0,-mt_rand(10,20));
    $c = $l3->pick($len3/16)->fade(0,-mt_rand(10,20));
    $_d = null;
    $d = function() use($l1,$l2,$l3,&$_d){
        if($_d) return $_d;
        $l = [$l1,$l2,$l3][mt_rand(0,2)];
        $_d = $l->pick($l->len()/16)->fade(0,-mt_rand(10,20));
        return $_d;
    };
    $p = pattern()->with(compact('a','b','c','d'));
    $p->resize(mt_rand(5,16)/10);
    $loop = $p()->x(4)->resize(mt_rand(5,16));
    if(mt_rand(0,1)) $loop->reverb();
    return $loop;
};
