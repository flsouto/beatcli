<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);
    
    $segments = mt_rand(8,16);
    $parts_a = $a->split($segments);
    $parts_b = $b->split($segments);
    
    $out = silence(0);
    $pulse_rate = mt_rand(2,4);
    
    foreach($parts_a as $i => $seg_a) {
        $seg_b = $parts_b[$i];
        
        // Create pulsing effect with reduced gains
        if($i % $pulse_rate == 0) {
            $seg_a->mod('gain -10');
            $seg_b->mod('gain -'.mt_rand(25,35));
        } else {
            $seg_a->mod('gain -'.mt_rand(25,35));
            $seg_b->mod('gain -10');
        }
        
        // Add some rhythmic variation
        if(!mt_rand(0,2)) {
            $seg_a->mod('tempo '.mt_rand(8,12)/10);
        }
        if(!mt_rand(0,2)) {
            $seg_b->chop(mt_rand(2,4));
        }
        
        // Mix with slight delay for groove
        $mixed = $seg_a()->mix($seg_b);
        if($i > 0 && !mt_rand(0,2)) {
            $mixed->mod('delay 0.01');
        }
        
        $out->add($mixed);
    }
    
    // Reduced overdrive amount
    if(mt_rand(0,1)) $out->mod('overdrive '.mt_rand(10,20));
    $out->mod('gain -10'); // Additional gain reduction at the end
    return $out->x(4);
}; 