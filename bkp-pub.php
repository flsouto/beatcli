<?php

$config = require(__DIR__."/config.php");

foreach($config['backup_paths'] as $to){
    $from = $config['pub_path'];
    passthru("rsync -avhr --ignore-existing $from $to");
}
