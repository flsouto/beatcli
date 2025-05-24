<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);
    
    $base_segments = 4;
    $layers = mt_rand(2,3);
    $out = silence(0);
    
    for($layer = 0; $layer < $layers; $layer++) {
        // Each layer gets progressively more segments
        $segments = $base_segments * ($layer + 1);
        $parts_a = $a->split($segments);
        $parts_b = $b->split($segments);
        
        $layer_out = silence(0);
        foreach($parts_a as $i => $seg_a) {
            $seg_b = $parts_b[$i];
            
            // Create spiral segment
            $spiral = $seg_a();
            
            // Add increasing intensity with each layer
            $spiral->mod('gain -'.(10 * $layer));
            
            // Add frequency modulation that increases with each layer
            if($layer > 0) {
                $spiral->mod('synth sin fmod '.($layer * mt_rand(100,200)).' '.mt_rand(20,40));
            }
            
            // Add some pitch spiral
            $pitch_amount = sin(($i / $segments) * 2 * pi()) * (10 + $layer * 5);
            $spiral->pitch($pitch_amount);
            
            // Mix with second source
            if(!mt_rand(0,2)) {
                $spiral->mix($seg_b()->mod('gain -15 reverse'));
            }
            
            // Add some temporal variation
            if(!mt_rand(0,2)) {
                $spiral->mod('tempo '.(mt_rand(9,11)/10));
            }
            
            $layer_out->add($spiral);
        }
        
        // Add some layer-specific effects
        if($layer > 0) {
            $layer_out->mod('delay 0.'.mt_rand(1,2));
        }
        if($layer == $layers - 1) {
            $layer_out->mod('overdrive '.mt_rand(20,30));
        }
        
        $out->add($layer_out);
    }
    
    if(mt_rand(0,1)) $out->reverb();
    return $out->x(2);
}; 