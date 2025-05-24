<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);
    
    $segments = 16;
    $parts_a = $a->split($segments);
    $parts_b = $b->split($segments);
    
    $out = silence(0);
    $delay_time = 0.02;
    $pitch_drop = 0;
    
    foreach($parts_a as $i => $seg_a) {
        $seg_b = $parts_b[$i];
        
        // Create falling effect
        $cascade = $seg_a()->mix($seg_b);
        $cascade->pitch($pitch_drop);
        $cascade->fade(0, -mt_rand(10,20));
        
        // Add delay
        if($i > 0) {
            $cascade->mod('delay '.$delay_time);
            $delay_time += 0.02;
        }
        
        $pitch_drop -= mt_rand(5,10);
        $out->add($cascade);
    }
    
    // Add subtle reverb for space
    if(mt_rand(0,1)) $out->reverb();
    
    return $out->x(4);
}; 