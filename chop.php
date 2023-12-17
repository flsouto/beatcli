<?php

require('utils.php');

if(!is_dir("chop")) mkdir("chop");

$s = sampler($argv[1]);
foreach($s->split(ceil($s->len()/60)) as $i => $p){
    echo "At $i\n";
    $p->save("chop/$i.wav");
}

