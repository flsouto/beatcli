<?php
return function($a,$b){

    $layer1 = $a::silence(0);
    $layer2 = $a::silence(0);

    $b->resize($a->len());
//    $size = mt_rand(0,1)?32:64;
    $size = 64;
    $a = $a->split($size);
    $b = $b->split($size);
    $normal = mt_rand(0,2);

    for($i=1;$i<=$size;$i++){
        $s = array_shift($a);
        $t = array_shift($b);
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
                $layer2->add($t);
            break;
        }
    }

    return $layer1->mix($layer2,false);



};

