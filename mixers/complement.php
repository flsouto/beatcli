<?php

return function($a, $b){

    normalize_speed($a,$b);

    $f1 = mt_rand(1, 9) / 10;
    $f2 = 1 - $f1;
    echo "F1: $f1, F2: $f2\n";

    $a->tremolo("$f1 99");
    $b->tremolo("$f2 99");
    $a->mix($b, false);
    
    return $a;
};
