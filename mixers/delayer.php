<?php

return function($loop1, $loop2){
    normalize_speed($loop1,$loop2);
    first_segment($loop1, $loop2);
    $len = $loop1->len();
    $divs = range(12, 16, 2);
    $div = $divs[array_rand($divs)];
    $delayed = silence($len/$div)->add($loop2)->cut(0, $len);
    return $loop1->mix($loop2)->x(4);
};
