<?php
return function($a,$b,$c){

    $l1 = [];
    $l2 = [];

    $b->resize($len=$a->len());
    $c->resize($len);
//    $size = mt_rand(0,1)?32:64;
    $size = 64;
    $a = $a->split($size);
    $b = $b->split($size);
    $c = $c->split($size);
    $normal = mt_rand(0,2);
    $speeder = null;
    if(!mt_rand(0,2)){
        if(mt_rand(0,1)){
            $speeder = mt_rand(0,1) ? fn() => 'tempo' : fn() => 'speed';
        } else {
            $speeder = fn() => mt_rand(0,1) ? 'tempo' : 'speed';
        }
        $speeder_rate = mt_rand(1,4);
    }
    $arr = [$a,$b,$c];

    $clone_t = mt_rand(0,1);

    for($i=1;$i<=$size;$i++){
        shuffle($arr);
        [$a,$b] = $arr;
        $s = array_shift($a);
        $t = array_shift($b);
        if(!mt_rand(0,9)){
            //$s = $s()->chop(mt_rand(2,5));
        }
        switch(mt_rand(1,3)){
            case 1:
                if($normal==1 || ($normal && mt_rand(0,1))){
                    $l1[] = $s()->mix($t);
                    $l2[] = $s()->gain('-100');
                } else {
                    $l1[] = $s;
                    $l2[] = $clone_t ? $t() : $t;
                }
            break;
            case 2:
                $l1[] = $s;
                $l2[] = $t()->gain('-100');
            break;
            case 3:
                $l1[] = $s()->gain('-100');
                if($speeder && !mt_rand(0,$speeder_rate) && !empty($b)){
                    $t->mod($speeder().' 2')->add(
                        $b[0]()->mod($speeder().' 2')
                    );
                }

                $l2[] = $clone_t ? $t() : $t;
            break;
        }
    }

    $layer1 = $s::join($l1);
    $layer2 = $s::join($l2);

    return $layer1->mix($layer2,false);



};

