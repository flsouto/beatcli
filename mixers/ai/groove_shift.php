<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);
    
    // Get core material
    $core_a = $a->pick(2);
    $core_b = $b->pick(2);
    
    // Create groove variations
    $parts = [];
    $groove_steps = [
        ['tempo' => 1, 'gain' => -6],
        ['tempo' => 2, 'gain' => -9],
        ['tempo' => 0.5, 'gain' => -3],
        ['tempo' => 1.5, 'gain' => -8]
    ];
    
    // Build pattern
    foreach($groove_steps as $step) {
        // Main groove element
        $groove = $core_a();
        $groove->mod('tempo '.$step['tempo']);
        $groove->mod('gain '.$step['gain']);
        
        // Add movement
        if(!mt_rand(0,1)) {
            $groove->chop(2);
        }
        
        // Layer with second source
        if(!mt_rand(0,2)) {
            $layer = $core_b();
            $layer->mod('tempo '.$step['tempo']);
            $layer->mod('gain '.($step['gain'] - 3));
            if(mt_rand(0,1)) {
                $layer->mod('reverse');
            }
            $groove->mix($layer);
        }
        
        $parts[] = $groove;
    }
    
    // Add variation
    foreach($groove_steps as $step) {
        $var = $core_b();
        $var->mod('tempo '.$step['tempo']);
        $var->mod('gain '.($step['gain'] - 2));
        
        if(!mt_rand(0,1)) {
            $var->chop(4);
        }
        
        $parts[] = $var;
    }
    
    // Join with minimal delay
    $out = $a::join($parts);
    
    // Add subtle space
    $out->mod('delay 0.08 15');
    
    // Keep length reasonable
    while($out->len() > 10) {
        $out->mod('tempo 1.25');
    }
    
    return $out->x(3);
}; 