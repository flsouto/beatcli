<?php
if(!function_exists('melodynth')){
function melodynth($seed){
    srand(time());
    $id = uniqid();
    passthru("RANDOM=".crc32(uniqid())." sh ".__DIR__."/melodynth.sh $id 2>&1");
    srand($seed);
    $loop = new FlSouto\Sampler("$id.wav");
    $loop->mod("tremolo ".mt_rand(2,19)." ".mt_rand(10,99)." tempo ".mt_rand(8,32)." repeat 3");
    return $loop;
}}

return function(){
    $s = crc32(uniqid());
    $a = melodynth($s);
    $b = melodynth($s);

    if(mt_rand(0,1)){
        $s = crc32(uniqid());
    }
    switch(mt_rand(1,3)){
        case 1:
            $c = melodynth($s)->resize($a->len());
            $d = melodynth($s)->resize($a->len());
        break;
        case 2:
            $c = $a();
            $d = melodynth($s)->resize($a->len());
        break;
        case 3:
            $c = $a();
            $d = $b();
        break;
    }

    $out = $a->add($b)->add($c)->add($d)->x(2*mt_rand(1,2))->mod('reverb');
    $out->gain('-15');
    $out->keepgain = true;
    return $out;
};

