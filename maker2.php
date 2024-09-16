<?php

require_once("utils.php");
$conf = require('config.php');

$loops = glob($conf['ipt_glob'],GLOB_BRACE);
srand($argv[1]??time());
shuffle($loops);

$dummy = sampler(array_pop($loops));
$layers = [$dummy];
$len = $dummy->len();
for($i=1;$i<=2;$i++){
    $layers[] = sampler(array_pop($loops))->resize($len);
}
$parts[] = $dummy()->fade(-30,0)->file;
$norm = false;
$total_len = 0;

function format_len($len){
    return sprintf("%02d:%02d", $len / 60, $len % 60);
}

$total_len = sampler($parts[0])->len();
$seeds = array_map(fn($s) => explode('=',$s), array_slice($argv, 2));

$last_seed_at = 0;

for($i=0;$i<=15;$i++){

    $fmtlen = format_len($total_len);
    echo "LEN: $fmtlen ($total_len)\n";
    if(!empty($seeds) && $seeds[0][0] <= $fmtlen){
        echo 'USE seed '.$seeds[0][1].' at '.$seeds[0][0]."\n";
        srand($seeds[0][1]);
        array_shift($seeds);
        shuffle($loops);
        $last_seed_at = $fmtlen;
    }
    $loop = array_shift($loops);

    echo "At $i of ".count($loops)."\n";
    $base = $layers[0]();
    if(!mt_rand(0,5)){
        echo 'XXXXXXXX'.PHP_EOL;
        $base->part('-12/16')->fade(0,-40)->sync();
    }
    if(mt_rand(0,1)){
        $part = $base->mix($layers[1], $norm)->mix($layers[2], $norm);
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
        $part = $base->mix($add, $norm);
    }
//    $part->maxgain();
    if(mt_rand(0,1)){
        $part->cut(0,'1/'.(2*mt_rand(1,2)));
    }
    $parts[] = $part->file;
    $total_len += $part->len();

    if(mt_rand(0,1)){
        shuffle($layers);
    }
    $layers[mt_rand(0,2)] = sampler($loop)->resize($len);
}
$parts[] = $layers[2]->fade(0,-40)->file;

exec("sox ".implode(" ", $parts)." stage.wav; play stage.wav trim ".$last_seed_at);

// 125 BPM Song Made With Loop Pack 2025-04-01 | 13 first loops used

