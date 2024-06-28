<?php


return function ($loop1, $loop2, $loop3){

    $s = $loop1;
    $arr0 = $s->cut(0,'1/4')->split(array_rand([4=>1,8=>1,16=>1,32=>1]));
    $a = $arr0[0];

    $s = $loop2;
    $arr = $s->cut(0,'1/4')->split(array_rand([4=>1,8=>1,16=>1,32=>1]));
    $b = $arr[1]->resize($a->len());

    $s = $loop3;
    $arr = $s->cut(0,'1/4')->split(array_rand([4=>1,8=>1,16=>1,32=>1]));
    $c = $arr[2]->resize($a->len());

    $a_half = $a->copy(0,'1/2')->mod('fade 0 0 .01');
    $b_half = $b->copy(0,'1/2')->mod('fade 0 0 .01');
    $c_half = $c->copy(0,'1/2')->mod('fade 0 0 .01');

    $get_c_half = function() use($c, $arr0){

    	if(rand(0,1)){
    		return $c->copy(0,'1/2')->mod('fade 0 0 .01');
    	} else {
    		return $arr0[2]->copy('1/2')->mod('fade 0 0 .01');
    	}

    };

    $get_b_half = function() use($b, $arr0){

    	if(rand(0,1)){
    		return $b->copy(0,'1/2');
    	} else {
    		return $arr0[1]->copy('1/2');
    	}

    };

    $mk = function() use( $a,$b,$c,$a_half,$b_half,$c_half, $get_c_half, $get_b_half ) {

    	$s = $a();

    	if(rand(0,1)){
    		$s->add($b()->mod('gain -5'));
    	} else {
    		if(rand(0,1)){
    			$s->add($a()->mod('gain -5'));
    		} else {
    			$s->mod('speed .5');
    		}
    	}

    	$s->add($c);
    	$s->add($get_b_half()->mod('gain -5'));

    	if(rand(0,1)){
    		$s->add($get_c_half());
    	} else {
    		$s->add($get_c_half()->chop(8));
    	}


    	$s->add($get_b_half());
    	$s->add($get_c_half());

    	$s->add($a_half());

    	if(rand(0,1)){
    		$s->add($get_b_half()->mod('gain -5'));
    	} else {
    		if(rand(0,1)){
    			$s->add($get_c_half()->mod('gain -5'));
    		} else {
    			$s->add($get_c_half()->chop(2)->mod('gain -5'));
    		}
    	}

    	$s->add($c);
    	if(rand(0,1)){
    		$s->add($b()->mod('gain -10'));
    	} else {
    		if(rand(0,1)){
    			$s->add($a()->mod('gain -10'));
    		} else {
    			$s->add($b()->chop(8)->mod('gain -10'));
    		}
    	}

    	return $s;
    };

    $s1 = $mk();
    $s2 = $mk();
    $s3 = $mk();

    if(rand(0,1)){
    	$s2->part('-1/16')->mod('gain -90')->sync();
    }
	$s3->part('-2/16')->mod('gain -90')->sync();


    $f = $s1()->add($s2)->add($s1)->add($s3)->resize(rand(12,16));
    return $f;


};
