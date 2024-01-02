<?php

return function($a,$b){
    $bit = $a->pick(mt_rand(4,6)/10)->mod('fade 0.02 0 0.02');
    if(mt_rand(0,1)) apply_fx($bit);
    return $bit()->add($bit()->mod('gain -5'))
                ->add($bit()->mod('gain -10'))
                ->add($bit()->mod('gain -15'))->x(4);
};
