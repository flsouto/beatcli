<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);
    
    // Create initial segments
    $parts = [];
    $base_len = mt_rand(4,8);
    
    // Get source material
    $source_a = $a->pick($base_len/4);
    $source_b = $b->pick($base_len/4);
    
    // Create variations
    for($i = 0; $i < 8; $i++) {
        $part = silence(0);
        
        // Layer A processing
        $layer_a = $source_a();
        if($i % 2 == 0) {
            $layer_a->mod('tempo 1.5');
            $layer_a->mod('gain -8');
        } else {
            $layer_a->chop(mt_rand(2,4));
            $layer_a->mod('gain -5');
        }
        
        // Layer B processing
        $layer_b = $source_b();
        if($i % 3 == 0) {
            $layer_b->mod('reverse');
            $layer_b->mod('gain -10');
        } else {
            $layer_b->mod('tempo 0.75');
            $layer_b->mod('gain -8');
        }
        
        // Combine layers
        $part->add($layer_a);
        if(!mt_rand(0,2)) {
            $part->add($layer_b);
        }
        
        // Add some space
        if(!mt_rand(0,1)) {
            $part->mod('delay 0.1 20');
        }
        
        $parts[] = $part;
    }
    
    // Join all parts
    $out = $a::join($parts);
    
    // Final touches
    if(mt_rand(0,1)) {
        $out->mod('overdrive 15');
    }
    
    // Keep length reasonable
    while($out->len() > 12) {
        $out->mod('tempo 1.25');
    }
    
    return $out->x(2);
}; 