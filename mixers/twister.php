<?php
return function($a, $b){
    first_segment($a, $b);
    mt_rand(0,3) && normalize_speed($a, $b);
    $parts = mt_rand(0,1)?8:16;
    $arr_a = $a->split($parts);
    $arr_b = $b->split($parts);
    $out = silence(0);
    $pitchfx = mt_rand(0,1) ? 'pitch '.rand(-5,5) : '';
    $gainfx = mt_rand(-20,-5);
    for($i=0;$i<$parts;$i++){
        $a = $arr_a[$i];
        $b = $arr_b[$i];
        if(isset($arr_a[$i+1]) && rand(0,1)){
            $arr_a[$i+1] = $a()->mod("gain $gainfx ".$pitchfx);
        }
        if(isset($arr_b[$i+1]) && rand(0,1)){
            $arr_b[$i+1] = $b()->mod("gain $gainfx ".$pitchfx);
        }
        if(mt_rand(0,1)){
            $a->mod('gain '.mt_rand(-20,0));
        } else {
            $b->mod('gain '.mt_rand(-20,0));
        }
        $out->add($a->mix($b));
    }
    return $out->x(4);
};
