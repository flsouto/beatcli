<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);
    
    // Create source material pools
    $source_a = $a->pick(4);
    $source_b = $b->pick(4);
    
    // Initialize output
    $out = silence(0);
    
    // Create granular layers
    $grain_sizes = [4, 6, 8, 12];
    foreach($grain_sizes as $size) {
        // Main granular texture
        $grain = $source_a();
        $grain->chop($size);
        
        // Careful gain staging
        $grain->mod('gain -15');
        
        // Add pitch variation
        $pitch = mt_rand(-5, 5);
        $grain->mod('pitch ' . $pitch);
        
        // Filter based on grain size
        if($size < 6) {
            $grain->mod('highpass 1000');
        } elseif($size > 8) {
            $grain->mod('lowpass 2000');
        }
        
        // Add complementary texture from source B
        if(!mt_rand(0,2)) {
            $complement = $source_b();
            $complement->chop($size * 2);
            $complement->mod('gain -18');
            $complement->mod('pitch ' . ($pitch + 7)); // Harmony relationship
            
            if(!mt_rand(0,1)) {
                $complement->mod('reverse');
            }
            
            $grain->mix($complement);
        }
        
        // Tempo variations for movement
        $tempo = [0.5, 0.75, 1.0, 1.25][$size % 4];
        $grain->mod('tempo ' . $tempo);
        
        $out->mix($grain);
    }
    
    // Create shimmer layer
    $shimmer = $source_b();
    $shimmer->mod('gain -20');
    $shimmer->chop(16);
    $shimmer->mod('pitch 12');
    $shimmer->mod('highpass 3000');
    $shimmer->mod('tempo 0.5');
    
    // Add subtle reverb to shimmer
    $shimmer->mod('reverb 40');
    
    $out->mix($shimmer);
    
    // Final processing
    $out->mod('reverb 30');
    
    // Ensure reasonable length
    while($out->len() > 10) {
        $out->mod('tempo 1.2');
    }
    
    // Create variations with pitch shifts
    $variations = [];
    $base = $out();
    
    // Original
    $variations[] = $base;
    $variations[] = $base;
    
    // Upward variation
    $up = $base();
    $up->mod('pitch 3');
    $up->mod('gain -1'); // Compensate for pitch increase
    $variations[] = $up;
    
    // Downward variation
    $down = $base();
    $down->mod('pitch -4');
    $variations[] = $down;
    
    return $a::join($variations);
}; 
