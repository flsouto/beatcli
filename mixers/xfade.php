<?php

return function($loop1, $loop2){
    normalize_speed($loop1,$loop2);
    first_segment($loop1, $loop2);
    $loop1->fade(-mt_rand(15,20), 0);
    $loop2->fade(0, -mt_rand(15,20));
    $result = $loop1->mix($loop2,rand(0,1))->x(4);
    return $result;
};
