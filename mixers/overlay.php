<?php

return function($loop1, $loop2){
    normalize_speed($loop1, $loop2);
    return $loop1->mix($loop2, true);
};
