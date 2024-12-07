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
    mkdir("snap$next");
    shell_exec("cp layer*.wav snap$next");
    return;
}
if($arg=='clear'){
    shell_exec("rm stage-layer*.wav");
    shell_exec("rm layer*.wav");
    shell_exec('rm stage*.wav');
    shell_exec('rm snap* -Rf');
    return;
}
if($arg=='export'){
    $to = $argv[2]??'';
    if(!$to || !is_dir($to)){
        die("usage: cmd export <destination>\n");
    }
    $stages = glob("stage{".implode(',',range(1,99))."}.wav",GLOB_BRACE);
    foreach($stages as $stage){
        $md5 = md5(file_get_contents($stage));
        copy($stage, $f="$to/$md5.wav");
        echo "Saving $f \n";
    }
    return;
}


if($arg=='finish'){
    $stages = glob("stage{".implode(',',range(1,99))."}.wav",GLOB_BRACE);
    $tail = new FlSouto\Sampler("layer".(getenv('tail')?:mt_rand(1,3)).".wav");
    $tail->fade(0,-40);
    shell_exec("sox ".implode(" ",$stages)." $tail->file stage.wav; play stage.wav");
    return;
}

if($arg=='remix'){
    $stages = glob("stage{".implode(',',range(1,99))."}.wav",GLOB_BRACE);
    $out = [];
    foreach($stages as $stage){
        $stage = new FlSouto\Sampler($stage);
        switch(mt_rand(1,3)){
            case 1 :
                $out[] = $stage;
            break;
            case 2:
                $out[] = $stage->split(2)[0];
            break;
            case 3:
                if($prev){
                    [$a,$_,$c,$_] = $stage->split(4);
                    [$_,$b,$_,$d] = $prev->split(4);
                    $out[] = $a::join([$a,$b,$c,$d]);
                } else {
                    $out[] = $stage;
                }
            break;
        }
        $prev = $stage;
    }
    FlSouto\Sampler::join($out)->play();
}


shell_exec("rm stage-layer*.wav");

function gen(){
    $pattern = getenv('p')?:'rand';
    $source = getenv('s')?:'';
    if($source){
        $source = "'$source'";
    }
    shell_exec("noplay=1 php mix.php $pattern $source");
    $st = new FlSouto\Sampler("stage.wav");
    if($m=getenv('m')){
        $st->mod($m);
    }
    $st->resize(16);
    return $st;
}

$st = gen();

$layer = getenv('l') ?: mt_rand(1,3);
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
