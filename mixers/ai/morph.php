<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);
    
    $segments = 32;
    $parts_a = $a->split($segments);
    $parts_b = $b->split($segments);
    
    $out = silence(0);
    
    // Create morphing parameters
    $pitch_morph = mt_rand(-50,50);
    $tempo_morph = (mt_rand(8,12)/10);
    
    foreach($parts_a as $i => $seg_a) {
        $seg_b = $parts_b[$i];
        $morph_amount = $i / ($segments - 1); // 0 to 1
        
        // Process source A
        $processed_a = $seg_a();
        $processed_a->mod('gain -'.(20 * $morph_amount));
        if($morph_amount > 0.3) {
            $processed_a->pitch($pitch_morph * (1 - $morph_amount));
            $processed_a->mod('tempo '.(1 + ($tempo_morph - 1) * (1 - $morph_amount)));
        }
        
        // Process source B
        $processed_b = $seg_b();
        $processed_b->mod('gain -'.(20 * (1 - $morph_amount)));
        if($morph_amount < 0.7) {
            $processed_b->pitch($pitch_morph * $morph_amount);
            $processed_b->mod('tempo '.(1 + ($tempo_morph - 1) * $morph_amount));
        }
        
        // Granular-style mixing
        if(!mt_rand(0,2)) {
            $processed_a->chop(mt_rand(4,8));
            $processed_b->chop(mt_rand(4,8));
        }
        
        $mixed = $processed_a->mix($processed_b);
        
        // Add some spectral effects during transition
        if($morph_amount > 0.3 && $morph_amount < 0.7) {
            if(!mt_rand(0,2)) {
                $mixed->mod('highpass '.mt_rand(500,2000));
            }
            if(!mt_rand(0,2)) {
                $mixed->mod('lowpass '.mt_rand(2000,8000));
            }
        }
        
        $out->add($mixed);
    }
    
    if(mt_rand(0,1)) $out->reverb();
    return $out->x(4);
}; 