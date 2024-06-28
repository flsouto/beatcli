<?php

return function($a, $b){
    $len = normalize_speed($a,$b);

    $loops = [$a,$b];
    $out = silence(0);
    foreach($loops as $s){
        $s->mod('synth sin fmod '.mt_rand(50,300).' '.mt_rand(10,40));
        $s->mod('tremolo '.(mt_rand(1,9)/10).' '.mt_rand(70,90));
        if(mt_rand(0,1)) $s->mod('reverse');
        if(mt_rand(0,1)) $s->mod('oops');
        if(mt_rand(0,1)) $s->mod('pitch '.mt_rand(-50,50));
        if(mt_rand(0,1)) $s->mod('highpass '.mt_rand(1000,5000));
        $out->mix($s);
    }
    $out = $out->cut(0,$len);
    $out = $out->mod('speed .5');
    if(mt_rand(0,1)) $out->mod('reverb');
    while($out->len() > 25){
        $out->mod('tempo 2');
    }
    return $out;

};
