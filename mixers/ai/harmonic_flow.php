<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);
    
    // Create base layers
    $base_a = $a->pick(3);
    $base_b = $b->pick(3);
    
    $layers = [];
    
    // Harmonic layer construction
    $pitches = [-12, -7, 0, 4, 7];
    foreach($pitches as $pitch) {
        $layer = $base_a();
        
        // Basic processing
        $layer->mod('gain -12');  // Start quiet for layering
        $layer->mod('pitch ' . $pitch);
        
        // Add movement
        if(!mt_rand(0,1)) {
            $layer->chop(3);
            $layer->mod('tempo ' . (mt_rand(0,1) ? '0.75' : '1.5'));
        }
        
        // Filter based on pitch range
        if($pitch < 0) {
            $layer->mod('lowpass 800');
        } elseif($pitch > 4) {
            $layer->mod('highpass 1200');
        }
        
        // Additional gain reduction for more extreme pitches
        if(abs($pitch) > 7) {
            $layer->mod('gain -3');
        }
        
        $layers[] = $layer;
    }
    
    // Create contrasting texture from second source
    $texture = $base_b();
    $texture->mod('gain -15');
    $texture->chop(6);
    $texture->mod('reverse');
    $texture->mod('pitch 12');
    $texture->mod('highpass 2000');
    
    // Combine all layers
    $out = silence(0);
    foreach($layers as $layer) {
        $out->mix($layer);
    }
    $out->mix($texture);
    
    // Add space and movement
    $out->mod('reverb 50');
    
    // Careful final gain staging
    $out->mod('gain -6');  // Additional reduction to account for layer summing
    
    // Keep reasonable length
    while($out->len() > 12) {
        $out->mod('tempo 1.25');
    }
    
    // Add subtle variation on repeats
    $final = $out();
    $var1 = $out();
    $var1->mod('pitch 2');
    $var2 = $out();
    $var2->mod('pitch -2');
    
    return $a::join([$final, $var1, $var2, $final]);
}; 