<?php

$conf = require('config.php');

$wavs = glob($conf['pub_path']."/*.wav");

if(empty($argv[2])) die("Usage: cmd <hours> <destiny>\n");

$hours = $argv[1];
$destiny = $argv[2];

foreach($wavs as $wav){
    if(filemtime($wav) > time() - 60*60*24){
        passthru("cp -v $wav $destiny");
    }
}
