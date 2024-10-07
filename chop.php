<?php

require('utils.php');
$s = sampler($argv[1]??'.ytdl/out.wav');

shell_exec("rm chop -Rf 2>/dev/null; mkdir chop");

$s = sampler($argv[1]??'.ytdl/out.wav');
foreach($s->split(ceil($s->len()/($argv[2]??16))) as $i => $p){
    echo "At $i\n";
    $p->rate('44100')->save("chop/$i.wav");
}

