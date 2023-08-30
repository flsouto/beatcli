<?php

return function($a,$b){
    if(mt_rand(0,1)) $a->mod('oops');
    $a->mod('speed .'.mt_rand(2,6).' lowpass '.mt_rand(200,600).' delay .'.($d=mt_rand(2,6)));
    $a->cut(0,'-.'.$d);

    if($a->len() > 40){
        $a->cut(0,'1/2');
    }

    if(mt_rand(0,1)){
        $b->resize($a->len())->mod('gain -30 synth sin fmod '.mt_rand(250,500).' '.mt_rand(5,40).' highpass 3000 tremolo .'.mt_rand(1,9).' 80');
        $a->mix($b,false);
    }
    return $a;
};
