<?php

return function($a, $b){

    first_segment($a, $b);
    normalize_speed($a,$b);

    $out = silence(0);
    foreach($a->split(16) as $i => $s){
        $out->add($s->mod('gain -'.mt_rand(0,20)));
    }
    $a = $out;

    $parts = $b->split(mt_rand(0,1)?8:16);
    $part = $parts[array_rand($parts)];
    $part->resize($a->len());
    if(mt_rand(0,1)){
        $part->mod('reverse');
    }

    return $a->mix($part)->x(4);

};


