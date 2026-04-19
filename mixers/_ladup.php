<?php

return function($a,$b,$c,$d){
    $b->part(0, '1/4')->gain('-100')->sync();
    $c->part(0, '2/4')->gain('-100')->sync();
    $d->part(0, '3/4')->gain('-100')->sync();
    return $a->mix($b,false)->mix($c,false)->mix($d,false);
};
