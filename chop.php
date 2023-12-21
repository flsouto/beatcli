<?php

require('utils.php');

shell_exec("rm chop -Rf 2>/dev/null; mkdir chop");

$s = sampler($argv[1]);
foreach($s->split(ceil($s->len()/60)) as $i => $p){
    echo "At $i\n";
    $p->save("chop/$i.wav");
}

