<?php

return function($a,$b,$c){

    $chopchance = mt_rand(0,50);
    $cut = mt_rand(1,1);
    for($i=1;$i<=16;$i++){
        echo "At $i\n";
        $s = [$a,$b,$c][mt_rand(0,2)];
        $s = $s->pick(mt_rand(1,50)/100);
        if(mt_rand(0,1)) apply_fx($s);
        if(!mt_rand(0,$chopchance)) $s->chop(mt_rand(50,100));
        if($cut && $i > 1 && !mt_rand(0,1)) $s->mod('gain -400');
        $out[] = $s->file;
    }

    $f = '/tmp/'.uniqid().'.wav';
    exec('sox '.implode(' ', $out)
        ." $f repeat 3 synth triangle amod ".mt_rand(10,500)." ".mt_rand(10,90)." oops tremolo ".mt_rand(4,500)." ".mt_rand(4,99)."  highpass 500 overdrive ".mt_rand(40,44)." tempo ".(mt_rand(80,150)/100)." gain -28");
    $s->file = $f;
    $len = $s->len();
    $s->delay('.2')->cut(0,$len);
    $a = $s;
    $speed = mt_rand(2,3)/10;
    $len = $a->len() / $speed;
    $repeat = floor(((mt_rand(8,15) * 60) / $len)) -1;
    $a = $a->x($repeat);

    $a->mod('reverb speed '.$speed.' lowpass '.mt_rand(400,500).' delay .'.($d=mt_rand(2,6)));
    $a->cut(0,'-.'.$d);
    $a->keepgain = true;
    return $a;
};


