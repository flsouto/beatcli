<?php
require_once(__DIR__."/../Pattern.php");
return function ($a){
    $pattern = Pattern::$pool[array_rand(Pattern::$pool)];
    $pitchmap = [0,50,-50,-100,100];
    shuffle($pitchmap);
    $pitchmap = array_combine(['a','b','c','d','e'],$pitchmap);
    $pattern = str_split($pattern);
    $out = [];
    foreach($a->split(count($pattern)) as $s){
        $k = array_shift($pattern);
        if($k == '_') $k = $lastk;
        $out[] = $s->pitch($pitchmap[$k])->file;
        $lastk = $k;
    }
    return $a::join($out);
};
