<?php

require('utils.php');
$s = sampler($argv[1]??'.ytdl/out.wav');

shell_exec("rm chop -Rf 2>/dev/null; mkdir chop");

<<<<<<< HEAD
$s = sampler($argv[1]??'.ytdl/out.wav');
foreach($s->split(ceil($s->len()/16)) as $i => $p){
=======
foreach($s->split(ceil($s->len()/($argv[2]??60))) as $i => $p){
>>>>>>> 3f22f916e4a0c08072bca3aa61465876078e0ae1
    echo "At $i\n";
    $p->save("chop/$i.wav");
}

