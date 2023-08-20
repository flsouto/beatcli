<?php
return function($a, $b){
    first_segment($a, $b);
    normalize_speed($a, $b);

    $a = $a->split(8);
    $b = $b->split(8);

    $out = silence(0);

    foreach($a as $i => $s){
        if($i + 1 % 2){
            $x = $s->mod('reverse');
            $y = $b[$i];
        } else {
            $x = $s;
            $y = $b[$i]->mod('reverse');
        }
        $out->add( $x->mix($y) );
    }

    return $out->x(4);

};
