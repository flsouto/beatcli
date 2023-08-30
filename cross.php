<?php

require_once(__DIR__."/utils.php");
use FlSouto\Sampler;

$config = require(__DIR__."/config.php");
require_once($config['smp_path']);

$files1 = glob($argv[1]);
$files2 = glob($argv[2]);

shuffle($files1);
shuffle($files2);

$count = min(count($files1), count($files2));

for($i=0;$i<$count;$i++){
    echo "At $i of $count\n";
    $s2 = new Sampler($files2[$i]);
    $len = $s2->len();
    $s1 = new Sampler($files1[$i]);
    $s1->resize($len);
    $s1->mix($s2,rand(0,1));
    $hash = basename($files1[$i]);
    $s1->save("crossed/$hash");
}


