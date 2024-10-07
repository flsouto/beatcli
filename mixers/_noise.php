<?php

return function($a,$b,$c){

    $chopchance = mt_rand(5,50);
    for($i=1;$i<=100;$i++){
        echo "At $i\n";
        $s = [$a,$b,$c][mt_rand(0,2)];
        $s = $s->pick(mt_rand(1,50)/100);
        if(mt_rand(0,1)) apply_fx($s);
        if(!mt_rand(0,$chopchance)) $s->chop(mt_rand(50,100));
 //       if($i > 1 && !($i%2) && !mt_rand(0,2)) $s->mod('gain -400');
        $out[] = $s->file;
    }

    $f = '/tmp/'.uniqid().'.wav';
    exec('sox '.implode(' ', $out)
        ." $f repeat 3 synth triangle amod ".mt_rand(10,500)." ".mt_rand(10,90)." oops tremolo ".mt_rand(4,500)." ".mt_rand(4,99)." pitch ".mt_rand(-200,300)."  highpass 500 overdrive ".mt_rand(40,70)." tempo ".(mt_rand(80,150)/100)." delay .2");
    $s->file = $f;
    return $s;
};


