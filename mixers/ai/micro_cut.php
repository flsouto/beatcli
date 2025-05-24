<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);
    
    // Get short source segments
    $parts = [];
    $slices_a = $a->split(8);
    $slices_b = $b->split(8);
    
    // Process pairs of slices
    for($i = 0; $i < 8; $i += 2) {
        $slice1_a = $slices_a[$i]();
        $slice2_a = $slices_a[$i + 1]();
        $slice1_b = $slices_b[$i]();
        $slice2_b = $slices_b[$i + 1]();
        
        // First half: quick cuts
        $part1 = $slice1_a;
        $part1->chop(4);
        $part1->mod('gain -6');
        
        if(!mt_rand(0,1)) {
            $part1->mix($slice1_b()->mod('gain -9'));
        }
        
        // Second half: stretched
        $part2 = $slice2_a;
        $part2->mod('tempo 0.75');
        $part2->mod('gain -4');
        
        if(!mt_rand(0,1)) {
            $part2->mix($slice2_b()->mod('gain -8 reverse'));
        }
        
        // Combine with tight transition
        $combined = silence(0);
        $combined->add($part1);
        $combined->add($part2);
        
        // Add micro-variations
        if(!mt_rand(0,2)) {
            $combined->mod('tempo 1.25');
        }
        
        $parts[] = $combined;
    }
    
    // Join all parts
    $out = $a::join($parts);
    
    // Add minimal effects
    if(!mt_rand(0,1)) {
        $out->mod('overdrive 10');
    }
    
    // Ensure consistent volume
    $out->mod('gain -3');
    
    // Keep length in check
    while($out->len() > 8) {
        $out->mod('tempo 1.5');
    }
    
    return $out->x(4);
}; 