<?php
use FlSouto\Sampler;
parse_str(implode('&',$argv), $params);
if(empty($params['p'])) die("missing p(attern) parameter\n");

$config = require(__DIR__."/config.php");
require_once($config['smp_path']);

$seed = (int)date('Ymd').($params['s']??1);
srand($seed);

$len = $params['l'] ?? .15;

$pool = [];
foreach(range('a','z') as $k){
    $smps = glob($config['ipt_glob'],GLOB_BRACE);
    $smps = $smps[array_rand($smps)];
    $pool[$k] = Sampler::select($smps)
            ->pick($len)
            ->mod('fade .015 0 .015');
}

$pool['_'] = $pool['a']()->mod('gain -100');

$out = Sampler::silence(0);
$pattern = str_split($params['p']);

for($i=0;$i<count($pattern);$i++){
    $k = $pattern[$i];
    $s = $pool[$k];
    if(isset($pattern[$i-1])){
        $p = $pool[$pattern[$i-1]];
        $s = $s()->mix($p()->mod('gain -5'));
    }
    $out->add($s);
}

$mod = 'speed .7 '.($params['m']??'');

$out = $out->mod($mod)->x(4);

$out->save($f=__DIR__."/stage.wav");

Sampler::select($f)->play();

