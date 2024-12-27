<?php
require_once(__DIR__."/../Pattern.php");
return function ($a){
    do{
        $pattern = Pattern::$pool[array_rand(Pattern::$pool)];
    } while($pattern[0]!='a');

    preg_match_all("/[a][^a]*/",$pattern,$m);
    $frags = [];
    $out = [];
    foreach($m[0] as $frag){
        $len = strlen($frag);
        if(!isset($frags[$len])){
            $frags[$len] = $a->copy(0,$len.'/'.strlen($pattern));
        }
        $out[] = $frags[$len]->file;
    }
    $out = $a::join($out);
    [$a,$b,$c,$d] = $out->split(4);
    return $a()->add($b)->add($a)->add(mt_rand(0,1)?$c:$d);
};
