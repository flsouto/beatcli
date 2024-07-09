<?php
$oblivion = require(__DIR__.'/_oblivion.php');
return function($a, $b, $c, $d, $e, $f) use($oblivion){
    if(mt_rand(0,1)){
        $part1 = $oblivion($a, $b, $c, false);
        $part2 = $oblivion($a, $b, $d, false);
        $part3 = $oblivion($e, $a, $d, false);
        $part4 = $oblivion($c, $e, $d, true);
    } else {
        $shuf = function($x,$y,$z,$flag){
            $arr = [$x,$y,$z];
            shuffle($arr);
            $arr[] = $flag;
            return $arr;
        };
        $part1 = $oblivion(...$shuf($a, $b, $c,false));
        $part2 = $oblivion(...$shuf($a, $b, $d,false));
        $part3 = $oblivion(...$shuf($e, $a, $d,false));
        $part4 = $oblivion(...$shuf($c, $e, mt_rand(0,1)?$d:$b,true));
    }
    $part2->resize($part1->len());
    $part3->resize($part1->len());
    $part4->resize($part1->len());
    return $part1->add($part2)->add($part3)->add($part4);
};
