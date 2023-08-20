<?php
return function($a, $b){
    first_segment($a, $b);
    normalize_speed($a, $b);
    $parts = mt_rand(0,1)?8:16;
    $arr_a = $a->split($parts);
    $arr_b = $b->split($parts);
    $out = silence(0);
    $chop = !mt_rand(0,2);
    $mode = rand(0,1);
    for($i=0;$i<$parts;$i++){
        $x = $y = $i;
        $a = $arr_a[$x];
        $b = $arr_b[$y];
        if($mode ?  ($i+1) % 2 : rand(0,1)){
            $a->mod('highpass 4000');
            $b->mod('lowpass 100');
        } else {
            $b->mod('highpass 4000');
            $a->mod('lowpass 100');
        }
        if($chop && !rand(0,10)) $a->chop(8);
        if($chop && !rand(0,10)) $b->chop(16);
        $out->add($a->mix($b),rand(0,1));
    }
    return $out->x(4);
};
