<?php

return function($s) {

    [$a,$b,$c,$d] = $s->split(4);

    if(mt_rand(0,1)){
        $x = $b;
        $y = $d;
    } else {
        $x = $c;
        $y = $d;
    }


    $r = fn() => pow(2,mt_rand(1,getenv('remax')?:4));

    $f = mt_rand(0,1) ? fn($s) => apply_fx($s) : function($s) {
        $t = $s();
        while($t->len() < 1) $t=$t->x(2);
        $mixers = ['altpitch','xom','altpass','patterns','meshterns','adrenaline','4t4r1','noise','bender'];
        $mixer = pick($mixers);
        $func = require __DIR__."/$mixer.php";
        $s2 = $func($t,$t,$t,$t);
        if(mt_rand(0,1)){
            $s2->cut(0,'1/'.(mt_rand(1,2)*2) );
        }
        $s2->resize($s->len());
        $s->file = $s2->file;
        return $s;
    };

    $f($x->part('-1/'.$r()))->sync();
    if(!isset($s->skip_last_remix)){
        $f($y->part('-1/'.$r()))->sync();
    }

    return $s::join([$a,$b,$c,$d]);

};
