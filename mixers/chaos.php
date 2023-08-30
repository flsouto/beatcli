<?php

return function($loop1,$loop2,$loop3){
    $mixers = mixers();
    $keys = array_keys($mixers);
    shuffle($keys);
    $output = null;
    $inputs = [$loop1(),$loop2(),$loop3()];
    $versions = [];
    foreach($keys as $k){
        if(in_array($k,RECURSIVE_MIXERS)) continue;
        $mixer = $mixers[$k];
        shuffle($inputs);
        if(!$output){
            $output = $mixer(...$inputs);
        } else {
            $output = $mixer($inputs[mt_rand(0,2)], $output,  $inputs[mt_rand(0,2)]);
        }
        $versions[] = $output;
        if(count($versions) >= 4) break;
    }
    if(mt_rand(0,1)){
        first_segment(...$versions);
        $a = $versions[0]->split(4);
        $b = $versions[1]->split(4);
        $c = $versions[2]->split(4);
        $d = $versions[3]->split(4);
        $output = $a[0]->add($b[1])->add($c[2])->add($d[3])->x(4);
    }
    if($output->len() < 5){
        $output->resize(mt_rand(8,16));
    }
    return $output;
};
