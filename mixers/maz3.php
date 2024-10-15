<?php
return function($a,$b,$c){

    $layer1 = $a::silence(0);
    $layer2 = $a::silence(0);

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
                    $layer1->add($s()->mix($t));
                    $layer2->add($s()->gain('-100'));
                } else {
                    $layer1->add($s);
                    $layer2->add($t);

                }
            break;
            case 2:
                $layer1->add($s);
                $layer2->add($t()->gain('-100'));
            break;
            case 3:
                $layer1->add($s()->gain('-100'));
                if($speeder && !mt_rand(0,$speeder_rate) && !empty($b)){
                    $t->mod($speeder().' 2')->add(
                        $b[0]()->mod($speeder().' 2')
                    );
                }

                $layer2->add($t);
            break;
        }
    }

    return $layer1->mix($layer2,false);



};

