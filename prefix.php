<?php

$folder = $argv[1];
$glob = $folder."/*wav";
$prefix = $argv[2];
foreach(glob($glob) as $file){
    $name = basename($file);
    $new_name = $name;
    foreach(['amb','exc'] as $prefix2){
        $new_name = str_replace($prefix2, $prefix2.$prefix, $new_name);
    }
    if($new_name === $name){
        $new_name = $prefix.$name;
    }
    echo $name.' -> '.$new_name."\n";
    rename("pub/$name", "pub/$new_name");
}
