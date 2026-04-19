<?php

return function($a,$b){
    $bit = $a->pick(mt_rand(4,6)/10);
    if(mt_rand(0,1)) apply_fx($bit);
    $bit->x(4)->fade(0,-16)->x(4);
    return $bit;
};
