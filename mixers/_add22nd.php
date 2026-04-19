<?php

return function($a,$b){
    $b->resize($a->len());
    $tail = $b->part(0,'1/2')->mod('gain -100');
    $tail->fade(-40,-8);
    $tail->sync();
    return $a->mix($b,false);
};
