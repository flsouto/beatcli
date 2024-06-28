<?php
return function($loop1,$loop2,$loop3){

$pick = function() use($loop1,$loop2,$loop3){
	$s = ${'loop'.mt_rand(1,3)};
	return $s->pick('1/'.(mt_rand(0,1)?'32':'64'));
};

$k = $pick();
$h = $pick()->resize($k->len());
$h->part('1/2')->fade(0,-10)->sync();
$s = $pick()->resize($k->len());
$_ = $k()->mod('gain -100');

$str = $k();
$parts = [];

$_flush = function() use(&$parts, &$str){
	if($str){
		$parts[] = $str;
		$str = null;
	}
};

$add = function($smp) use(&$str){
	if($str){
		$str->add($smp);
	} else {
		$str = $smp();
	}
};

$h_prob = rand(1,4);

for($i=1;$i<=8;$i++){
	if(rand(0,$h_prob)){
		$tmp = $h();
		if(!rand(0,4)){
			$tmp->cut(0,'1/2')->x(2);
			if(rand(0,1)){
				$tmp->chop(mt_rand(0,5)?rand(3,16):rand(3,100));
			}
		}
		$add($tmp);
	} else {
		switch(rand(1,3)){
			case 1:
				$_flush();
				$add($k);
			break;
			case 2:
				$_flush();
				$add($s);
			break;
			case 3:
				$add($_);
			break;
		}
	}
}

$_flush();

foreach($parts as $smp){

	$bkg = ${'loop'.mt_rand(1,3)};
	$bkg = $bkg->pick($smp->len()/rand(1,4))->resize($smp->len());
    if(mt_rand(0,1)){
        $bkg->mod('reverse');
    }
	$smp->mix($bkg->mod('gain -5 reverb oops fade 0.01 0 0.01'),false);

	$add($smp);
}

return $str->x(4)->mod('speed '.(rand(10,20)/10));

};
