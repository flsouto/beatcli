<?php
return function($a,$b,$c){
    normalize_speed($a,$b);
    $c->resize($a->len());
    return $a->mix($b)->mix($c);
};

