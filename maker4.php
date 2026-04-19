<?php

require_once("utils.php");

$arg = $argv[1]??'';

if($arg=='save'){
    if(file_exists('.m4edit')){
        $stage = file_get_contents('.m4edit');
    } else {
        $stages = glob("stage{".implode(',',range(1,99))."}.wav",GLOB_BRACE);
        $stage = count($stages) + 1;
    }

    copy("stage.wav",$f="stage$stage.wav");
    echo "Saved $f\n";

    $f = glob("stage-layer*.wav")[0];
    $f2 = str_replace('stage-','',$f);
    rename($f, $f2);
    if(!is_dir($d="snap$stage")){
        mkdir($d);
    }
    shell_exec("cp layer*.wav snap$stage");
    return;
}
if($arg=='clear'){
    shell_exec("rm stage-layer*.wav");
    shell_exec("rm layer*.wav");
    shell_exec('rm stage*.wav');
    shell_exec('rm snap* -Rf');
    shell_exec('rm .m4edit');
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

    if(getenv('notail')){
        $tail = FlSouto\Sampler::silence(0);
    } else {
        $tail = new FlSouto\Sampler("layer".(getenv('tail')?:mt_rand(1,3)).".wav");
        $tail->fade(0,-40);
        if($len = getenv('len')){
            $tail->resize($len);
        }

    }
    shell_exec("sox ".implode(" ",$stages)." $tail->file stage.wav; play stage.wav");
    return;
}


if($arg=='rebuild'){
    build();
    return;
}


shell_exec("rm stage-layer*.wav");

function gen(){
    $pattern = getenv('p')?:'rand';
    $source = getenv('s')?:'';
    if($source){
        $source = "'$source'";
    }
    $keepgain=getenv('keepgain')?'1':'';
    shell_exec("noplay=1 keepgain=$keepgain php mix.php $pattern $source");
    $st = new FlSouto\Sampler("stage.wav");
    if($m=getenv('m')){
        $st->mod($m);
    }
    $st->resize(16);
    return $st;
}

$st = gen();

$layer = getenv('l') ?: pick_layer();
$st->save("stage-layer$layer.wav");


function pick_layer(){
    for($i=1;$i<=3;$i++){
        if(!file_exists("layer$i.wav")){
            return $i;
        }
    }
    return mt_rand(1,3);
}

function mixlayer($layer, $to){
    $s = null;
    if(getenv('solo')!=$layer && file_exists($f="stage-layer$layer.wav")){
        $s = new FlSouto\Sampler($f);
    } else if(file_exists($f="layer$layer.wav")){
        $s = new FlSouto\Sampler($f);
    } else {
        return false;
    }
    if(getenv('chop')==$layer || getenv('chop')=='rand'){
        $s->chop(16);
    }
    if(getenv('remix')==$layer || getenv('remix')=='rand'){
        $s = (require('mixers/remix.php'))($s);
    }
    if($s){
        $to->mix($s, false);
    }
}

function build(){

    $out = FlSouto\Sampler::silence(0);

    if($i = getenv('solo')){
        mixlayer($i, $out);
    } else {
        for($i=1;$i<=9;$i++){
            mixlayer($i,$out);
        }
    }

    if($len=getenv('len')){
        $out->resize($len);
    }

    if($cut=getenv('cut')){
        $out->cut(0,$cut);
    }

    $out->save($f="stage.wav");

    return $out;

}

build()->play();
