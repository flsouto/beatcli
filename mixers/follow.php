<?php

return function($a, $b){
    normalize_speed($a, $b);
    first_segment($a,$b);
    return $a->add($b)->x(4);
};
