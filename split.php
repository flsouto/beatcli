<?php
use FlSouto\Sampler;
require_once(__DIR__."/utils.php");

$s = new Sampler(__DIR__."/stage.wav");
$s->cut(0,'1/2');
$s->save('stage.wav');
