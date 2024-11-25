<?php
require_once(__DIR__."/../Pattern.php");

return function($a, $b, $c){

    $a->resize($len1=mt_rand(2,8));
    $b->resize($len2=mt_rand(2,8));
    $c->resize($len3=mt_rand(2,8));


    $samples = new StdClass;
    $samples->pool = [$a,$b,$c];
    $samples->i = 0;

    $factory = function($key) use($samples){
        return function() use($key, $samples){
            $samples->i++;
            if(empty($samples->$key)){
                $s = pick($samples->pool);
                $len = $s->len();
                $samples->$key = $s->pick($len/16);
            }
            return $samples->$key;
        };
    };

    foreach(['a','b','c','d'] as $k){
        $with[$k] = $factory($k);
    }

    do{
        $p1 = pick(Pattern::$pool);
        $p2 = pick(Pattern::$pool);
    } while($p1 == $p2 ||  strlen($p1) != strlen($p2) || levenshtein($p1, $p2) < 5);

    $p1 = str_split($p1);
    $p2 = str_split($p2);

    $groups = [];
    for($j=1; $j<=3; $j++){

        $meshed = [];

        $selector = mt_rand(0,1) ? fn($i) => ($i+1) % 2 : fn() => mt_rand(0,1);

        foreach($p1 as $i => $k){
            if($selector($i)){
                $meshed[] = $k;
            } else {
                $meshed[] = $p2[$i];
            }
        }

        $groups[] = $meshed;

    }


    $meshed = [...$groups[0], ...$groups[1], ...$groups[0], ...$groups[2]];

    echo "p1:".implode($p1)."\np2:".implode($p2)."\nmx:".implode($meshed)."\n";

    $p = (new Pattern($meshed))->with($with);
    $loop = $p()->resize(mt_rand(10,16));
//    if(mt_rand(0,1)) $loop->reverb();
    $loop->gain(-10);
    $loop->keepgain = true;
    return $loop;

};
