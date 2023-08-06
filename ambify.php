<?php
use FlSouto\Sampler;

$config = require(__DIR__."/config.php");
require_once($config['smp_path']);

$files = glob(__DIR__."/../looploader/queue/*.wav");
shuffle($files);

$out = Sampler::silence(0);
for($i=0;$i<3;$i++){
    $len = mt_rand(28,60);
    $lens[] = $len;
    $s = Sampler::select($files[$i])
            ->mod('lowpass '.mt_rand(666,1200))
            ->mod('tremolo '.(mt_rand(1,9)/10).' '.mt_rand(50,90))
            ->resize($len);
    if($i==2 && mt_rand(0,1)) $s->mod('reverse');
    $out->mix($s);
}
$out->cut(0,min(...$lens)/4)->mod('fade 0.05 0 0.05')->x(4)->mod('reverb')->maxgain();
$out->play();

