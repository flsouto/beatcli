<?php

return function($loop1,$loop2){
    normalize_speed($loop1,$loop2);
    $a = $loop1->mod('highpass '.mt_rand(5000,8000).' gain -5');
    $b = $loop2->mod('lowpass '.mt_rand(100,300));
    return $a->mix($b);
};
