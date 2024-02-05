<?php

require('utils.php');
$s = sampler($argv[1]??'.ytdl/out.wav');

shell_exec("rm chop -Rf 2>/dev/null; mkdir chop");

foreach($s->split(ceil($s->len()/($argv[2]??60))) as $i => $p){
    echo "At $i\n";
    $p->save("chop/$i.wav");
}

