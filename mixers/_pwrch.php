<?php

return function($a){

    $n_chords = mt_rand(3,8);
    $n_steps = mt_rand(2,4);
    $ch_len = mt_rand(10,15)/1000;
    $pad = .1;
    $seed = time();
    $steps = [];
    $min_pitch = -mt_rand(0,666);
    $max_pitch = mt_rand(0,666);
    $max_len = mt_rand(50,60);
    for($j=1;$j<=$n_steps;$j++){
        srand(crc32(uniqid()));
        $len = mt_rand(3,$max_len);
        $pitch = mt_rand($min_pitch,$max_pitch);
        srand($seed);
        $step = $a::silence(0);
        for($i=0;$i<$n_chords; $i++){
            $str = $a->pick($ch_len)->x($len);
            $str->tremolo(mt_rand(1,9).' '.mt_rand(10,99));
            $str = $a::silence($pad * $i)->add($str);
            $step->mix($str,false);
        };
        $step->pitch($pitch)->lowpass(mt_rand(300,666))->overdrive(mt_rand(36,40));
        $steps[] = $step;
    }

    return $a::join($steps)->x(4);

};
