<?php

return function($a, $b){
    first_segment($a,$b);
    normalize_speed($a, $b);
    return $a->add($b)->x(4);
};
