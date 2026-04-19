<?php

use FlSouto\Sampler;
require_once(__DIR__."/utils.php");

passthru('noplay=1 seed='.$argv[1].' nofade=1 php mix.php _oblivion');


$part1 = new FlSouto\Sampler('stage.wav');
$part1->part(0,'1/4')->fade(-20,-5)->sync();

passthru('noplay=1 seed='.$argv[1].' mseed='.time().' php mix.php _oblivion');

$part2 = new Sampler('stage.wav');
$part2->resize($part1->len());
$part1->add($part2);
$part1->save('stage.wav');

passthru('play stage.wav');
