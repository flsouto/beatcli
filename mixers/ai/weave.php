<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);
    
    $segments = 16;
    $parts_a = $a->split($segments);
    $parts_b = $b->split($segments);
    
    $out = silence(0);
    $weave_pattern = array_fill(0, $segments, mt_rand(0,1));
    
    for($i = 0; $i < $segments; $i++) {
        $seg_a = $parts_a[$i]();
        $seg_b = $parts_b[$i]();
        
        // Alternate effects for odd/even segments
        if($i % 2 == 0) {
            $seg_a->mod('pitch '.mt_rand(-30,30));
            $seg_b->mod('tempo '.mt_rand(8,12)/10);
        } else {
            $seg_a->mod('tempo '.mt_rand(8,12)/10);
            $seg_b->mod('pitch '.mt_rand(-30,30));
        }
        
        // Weave based on pattern
        if($weave_pattern[$i]) {
            $seg_a->fade(0, -10);
            $seg_b->fade(-10, 0);
        } else {
            $seg_a->fade(-10, 0);
            $seg_b->fade(0, -10);
        }
        
        $out->add($seg_a->mix($seg_b));
    }
    
    if(!mt_rand(0,2)) $out->mod('oops');
    if(mt_rand(0,1)) $out->reverb();
    
    return $out->x(4);
}; 