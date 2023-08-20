<?php
use FlSouto\Sampler;

$config = require(__DIR__."/config.php");
require_once($config['smp_path']);

$files = glob(__DIR__."/out/*.wav");
shuffle($files);

$out = Sampler::silence(0);
$len = mt_rand(35,48);
for($i=0;$i<3;$i++){
    $lens[] = $len;
    $s = Sampler::select($files[$i])
            ->mod('lowpass '.mt_rand(666,1200))
            ->mod('tremolo '.(mt_rand(1,9)/10).' '.mt_rand(50,90))
            ->resize($len);
    if($i==2 && mt_rand(0,1)) $s->mod('reverse');
    if(0&&mt_rand(0,1)){
        $parts = [2,4,16];
        $s->cut(0,'1/'.$parts[array_rand($parts)])->resize($len);
    }
    $out->mix($s);
}
$out->cut(0,min(...$lens)/4)->mod('fade 0.05 0 0.05')->x(2)->mod('reverb')->maxgain();
$out->save('stage.wav');
$out->play();
