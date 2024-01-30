<?php

return function($s1,$s2){

    $s1 = $s1->resize(20)->pick(5);

    $s2 = $s2->resize(20)->pick(2.5);

    if(rand(0,1)){
    	$tmp = $s1;
    	$s1 = $s2;
    	$s2 = $tmp;
    }

    $s1->sway([-10,-1,0,-18]);
    $s1->mix($s1()->mod('overdrive '.rand(50,99).' gain -30')->fade(0,-20),false);
    $s2->fade(-20,0);
    $s2->mix($s2()->mod('overdrive '.rand(50,99).' gain -30')->fade(-20,0),false);

    if(0&&rand(0,1)){
    	$p = $s2->part((rand(0,1)?6:10).'/16',(rand(0,1)?2:4).'/16')->chop(16);
    	if(rand(0,1)){
    		$p->mod('gain -999');
    	}
    	$p->sync();
    }


    $s1->mix($s2,false)->x(4)->resize(mt_rand(9,20));

    return $s1;

};
