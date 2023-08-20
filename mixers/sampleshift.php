<?php
return function($a, $b){
    first_segment($a, $b);
    mt_rand(0,3) && normalize_speed($a, $b);
    $parts = mt_rand(0,1)?8:16;
    $arr_a = $a->split($parts);
    $arr_b = $b->split($parts);
    $out = silence(0);
    for($i=0;$i<$parts;$i++){
        $x = $y = $i;
        $a = $arr_a[$x];
        $b = $arr_b[$y];
        if(($i+1) % 2){
            $a->mod('gain -10');
        } else {
            $b->mod('gain -10');
        }
        $out->add($a->mix($b));
    }
    return $out->x(4);
};
