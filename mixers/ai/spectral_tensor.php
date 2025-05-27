<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);
    
    // Create spectral bands
    $bands = [
        ['lowpass', 500],

        ['highpass', 4000]
    ];
    
    // Initialize grain parameters
    $grain_sizes = [8, 12, 16, 24];
    $segments = 16;
    
    // Split source material
    $parts_a = $a->split($segments);
    $parts_b = $b->split($segments);
    
    $out = silence(0);
    
    // Process through spectral tensor
    foreach($parts_a as $i => $seg_a) {
        $processed = silence(0);
        
        // Create spectral layers
        foreach($bands as $band) {
            $layer = $seg_a();
            
            // Apply spectral filtering
            if(count($band) == 2) {
                $layer->mod($band[0].' '.$band[1]);
            } else {
                $layer->mod($band[0].' '.$band[1].' '.$band[2]);
            }
            
            // Granular processing
            $grain_size = $grain_sizes[array_rand($grain_sizes)];
            $layer->chop($grain_size);
            
            // Add movement
            if(!mt_rand(0,2)) {
                $layer->mod('tempo '.mt_rand(85,115)/100);
            }
            
            // Pitch variations based on spectral band
            $pitch = mt_rand(-12,12);
            $layer->mod('pitch '.$pitch);
            
            // Mix with corresponding part from B
            if(!mt_rand(0,2)) {
                $layer_b = $parts_b[$i]();
                $layer_b->mod($band[0].' '.$band[1]);
                $layer_b->chop($grain_size * 2);
                $layer_b->mod('gain -12');
                $layer->mix($layer_b);
            }
            
            // Dynamic gain staging based on spectral content
            $gain = $band[0] == 'lowpass' ? -6 : ($band[0] == 'highpass' ? -9 : -8);
            $layer->mod('gain '.$gain);
            
            $processed->mix($layer);
        }
        
        // Add spatial dimension
        if(!mt_rand(0,2)) {
            $processed->mod('echo 0.8 0.4 '.mt_rand(40,80).' 0.3');
        }
        
        // Occasional reverse for texture
        if(!mt_rand(0,3)) {
            $processed->mod('reverse');
        }
        
        $out->add($processed);
    }
    
    // Final processing
    if(!mt_rand(0,1)) {
        $out->mod('reverb 40 50');
    }
    
    // Create variations
    $variations = [];
    
    // Original
    $variations[] = $out();
    
    // Spectral stretch up
    $up = $out();
    $up->mod('pitch 5');
    $up->mod('tempo 0.95');
    $variations[] = $up;
    
    // Spectral stretch down
    $down = $out();
    $down->mod('pitch -7');
    $down->mod('tempo 1.05');
    $variations[] = $down;
    $variations[] = $up;
    
    return $a::join($variations);
}; 