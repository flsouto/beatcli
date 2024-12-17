<?php

return function($a){
    if(mt_rand(0,1)) $a->mod('tempo .'.mt_rand(8,9));
    $len = mt_rand(9,20);
    $a->resize($len);
    first_segment($a);
    $len = $a->len();
    $shadow = $a::silence(mt_rand(1,6)/1000)->add($a);
    if(mt_rand(0,1)) $shadow->mod('tempo .9'.mt_rand(8,9));
    if(mt_rand(0,1)) $shadow->tremolo('.'.mt_rand(1,9).' 99');
    $out = $a->mix($shadow,false)->cut(0,$len)->x(4);
    return $out;
};
