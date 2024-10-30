<?php

$conf = require('config.php');


if(empty($argv[2])) die("Usage: cmd <hours> <destiny> [glob=*]\n");

$glob = $argv[3] ?? '*.wav';
$wavs = glob($conf['pub_path']."/$glob");

$hours = $argv[1];
$destiny = $argv[2];

foreach($wavs as $wav){
    if(filemtime($wav) > time() - 60*60*$hours){
    	if($destiny === '-d'){
			echo $wav."\n";
    	} else {
	        passthru("cp -v $wav $destiny");
    	}
    }
}
