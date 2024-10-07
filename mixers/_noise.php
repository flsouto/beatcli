<?php

return function($a,$b,$c){


    for($i=1;$i<=mt_rand(8,16);$i++){
        echo "At $i\n";
        $s = [$a,$b,$c][mt_rand(0,2)];
        $s = $s->pick(mt_rand(1,5)/10);
        if(mt_rand(0,1)) apply_fx($s);
        $out[] = $s->file;
    }

    $f = '/tmp/'.uniqid().'.wav';
    exec('sox '.implode(' ', $out)." $f repeat 3 reverb highpass 1000 overdrive 70 tempo .8768");
    $s->file = $f;
    return $s;
};
