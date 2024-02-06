<?php

require('utils.php');

shell_exec("rm chop -Rf 2>/dev/null; mkdir chop");

$s = sampler($argv[1]??'.ytdl/out.wav');
foreach($s->split(ceil($s->len()/16)) as $i => $p){
    echo "At $i\n";
    $p->save("chop/$i.wav");
}

