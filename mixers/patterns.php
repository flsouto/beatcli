<?php
require_once(__DIR__."/../Pattern.php");

return function($l1, $l2, $l3){
    $l1->resize($len1=mt_rand(2,8));
    $l2->resize($len2=mt_rand(2,8));
    $l3->resize($len3=mt_rand(2,8));
    $a = $l1->pick($len1/16);
    $b = $l2->pick($len2/16);
    $c = $l3->pick($len3/16);
    $a->fade(0,-mt_rand(10,20));
    $b->fade(0,-mt_rand(10,20));
    $c->fade(0,-mt_rand(10,20));
    $p = pattern("a_b_a__ca_b_c___")->resize(mt_rand(10,20)/10)->with(compact('a','b','c'));
    $loop = $p()->x(4)->resize(mt_rand(8,20));
    if(mt_rand(0,1)) $loop->reverb();
    return $loop;
};
