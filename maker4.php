<?php

require_once("utils.php");

$arg = $argv[1]??'';

if($arg=='save'){
    $stages = glob("stage{".implode(',',range(1,99))."}.wav",GLOB_BRACE);
    $next = count($stages) + 1;
    copy("stage.wav",$f="stage$next.wav");
    echo "Saved $f\n";

    $f = glob("stage-layer*.wav")[0];
    $f2 = str_replace('stage-','',$f);
    rename($f, $f2);
    return;
}
if($arg=='clear'){
    shell_exec("rm stage-layer*.wav");
    shell_exec("rm layer*.wav");
    shell_exec('rm stage*.wav');
    return;
}

if($arg=='finish'){
    $stages = glob("stage{".implode(',',range(1,99))."}.wav",GLOB_BRACE);
    $head = new FlSouto\Sampler("layer".mt_rand(1,3).".wav");
    $head->fade(0,-40);
    shell_exec("sox ".implode(" ",$stages)." $head->file stage.wav; play stage.wav");
    return;
}

shell_exec("rm stage-layer*.wav");
shell_exec("noplay=1 php mix.php");

$layer = mt_rand(1,3);

$st = new FlSouto\Sampler("stage.wav");
$st->resize(16);
$st->save("stage-layer$layer.wav");

function mixlayer($layer, $to){
    global $arg;
    $s = null;
    if(file_exists($f="stage-layer$layer.wav")){
        $s = new FlSouto\Sampler($f);
    } else if(file_exists($f="layer$layer.wav")){
        $s = new FlSouto\Sampler($f);
    }
    if($arg === "chop$layer"){
        $s->chop(16);
    }
    if($s){
        $to->mix($s, false);
    }
}

$out = FlSouto\Sampler::silence(0);
mixlayer(1,$out);
mixlayer(2,$out);
mixlayer(3,$out);


$out->save($f="stage.wav");

shell_exec("play $f");
