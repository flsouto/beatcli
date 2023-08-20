<?php
require_once(__DIR__."/utils.php");
use FlSouto\Sampler;
parse_str(implode('&',$argv), $params);
if(empty($params['p'])) die("missing p(attern) parameter\n");

$config = require(__DIR__."/config.php");
require_once($config['smp_path']);

$seed = (int)date('Ymd').($params['s']??1);
srand($seed);

$len = $params['l'] ?? .15;
$pattern = str_split($params['p']);
$pool = [];
foreach($pattern as $k){
    if(isset($pool[$k])) continue;
    srand($seed + ord($k));
    $smps = glob($config['ipt_glob'],GLOB_BRACE);
    $smps = $smps[array_rand($smps)];
    $pool[$k] = Sampler::select($smps)
            ->pick($len)
            ->mod('fade .015 0 .015');
    if(strtoupper($k)==$k){
        get_fxs()['synth']($pool[$k]);
    }
}

$pool['_'] = Sampler::silence($len);

$out = Sampler::silence(0);

for($i=0;$i<count($pattern);$i++){
    $k = $pattern[$i];
    $s = $pool[$k];
    if(isset($pattern[$i-1])){
        $p = $pool[$pattern[$i-1]];
        $s = $s()->mix($p()->mod('gain -5'));
    }
    if(isset($pattern[$i-2])){
        $p = $pool[$pattern[$i-2]];
        $s = $s()->mix($p()->mod('gain -10'));
    }
    $out->add($s);
}

$mod = 'speed .7 '.($params['m']??'');

$out = $out->x(4)->mod($mod);

$out->save($f=__DIR__."/stage.wav");

Sampler::select($f)->play();

