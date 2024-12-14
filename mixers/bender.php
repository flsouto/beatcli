<?php
require_once(__DIR__."/../Pattern.php");
return function ($a){
    $pattern = Pattern::$pool[array_rand(Pattern::$pool)];
    $pitchmap = [1,50,-50,50,-50];
    shuffle($pitchmap);
    $pitchmap = array_combine(['a','b','c','d','e'],$pitchmap);
    $pattern = str_split($pattern);
    $slen = $a->len() / count($pattern);

    $bends = [];
    $bend = [];
    $lastk = null;

    foreach($pattern as $k){
        if($k == '_'){
            $bend[2] += $slen;
        } else {
            if($k != $lastk){
                if($bend){
                    $bends[] = implode(',',$bend);
                }
                $bend = [0, $pitchmap[$k], $slen];
            }
            $lastk = $k;
        }
    }

    if($bend){
        $bends[] = implode(',',$bend);
    }

    $bends = implode(' ',$bends);
    $a = $a->bend($bends);
    [$a,$b] = $a->split(2);
    $b->fade(0,-30);
    $a->fade(-30,0);
    $out = $a->mix($b,true);
    if($out->len() < 8){
        $out = $out->x(2);
    }
    return $out;
};
