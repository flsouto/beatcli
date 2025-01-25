<?php

return function($a){
    $tunes = require(__DIR__.'/../tunes.php');
    if(isset($tunes['L'])){
        $tune = str_split($tunes['L']);
    } else {
        $tune = str_split($tunes[array_rand($tunes)]);
    }
    $parts = $a->split(count($tune));
    $power = mt_rand(20,50);

    foreach($parts as $p){
        $tone = array_shift($tune);
        $p->pitch( $tone * $power );
        $out[] = $p;
    }

    return $a::join($out);
};
