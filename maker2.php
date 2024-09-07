<?php

require_once("utils.php");
$conf = require('config.php');

$loops = glob($conf['ipt_glob'],GLOB_BRACE);
shuffle($loops);

$dummy = sampler(array_pop($loops));
$layers = [$dummy];
$len = $dummy->len();
for($i=1;$i<=2;$i++){
    $layers[] = sampler(array_pop($loops))->resize($len);
}
$parts[] = $dummy()->fade(-30,0)->file;
foreach($loops as $i => $loop){
    echo "At $i of ".count($loops)."\n";
    $base = $layers[0]();
    if(!mt_rand(0,5)){
        echo 'XXXXXXXX'.PHP_EOL;
        $base->part('-12/16')->fade(0,-40)->sync();
    }
    if(mt_rand(0,1)){
        $part = $base->mix($layers[1], false)->mix($layers[2], false);
    } else {
        [$a] = $layers[1]->split(4);
        [$b] = $layers[2]->split(4);
        if(mt_rand(0,1)){
            if(mt_rand(0,1)){
                $a->chop(pow(2,mt_rand(1,4)));
            }
            if(mt_rand(0,1)){
                $b->chop(pow(2,mt_rand(1,4)));
            }
        }
        $add = $a->add($b)->x(2);
        $part = $base->mix($add, false);
    }
    if(mt_rand(0,1)){
        $part->cut(0,'1/'.(2*mt_rand(1,2)));
    }
    $parts[] = $part->file;
    if(mt_rand(0,1)){
        shuffle($layers);
    }
    if($i === 15) break;
    $layers[mt_rand(0,2)] = sampler($loop)->resize($len);
}
$parts[] = $layers[2]->fade(0,-40)->file;

exec("sox ".implode(" ", $parts)." stage.wav; play stage.wav");

// 125 BPM Song Made With Loop Pack 2025-04-01 | 13 first loops used

