<?php
return function($a, $b, $c) {
    first_segment($a, $b, $c);
    normalize_speed($a, $b);
    $c->resize($a->len());
    
    // Create three dimensions of processing
    $dimensions = [
        ['tempo', mt_rand(8,12)/10],
        ['pitch', mt_rand(-50,50)],
        ['gain', '-'.mt_rand(5,15)]  // Changed from chop to gain
    ];
    
    $segments = 16;
    $parts_a = $a->split($segments);
    $parts_b = $b->split($segments);
    $parts_c = $c->split($segments);
    
    $out = silence(0);
    
    // Process each segment through the dimensions
    foreach($parts_a as $i => $seg_a) {
        $seg_b = $parts_b[$i];
        $seg_c = $parts_c[$i];
        
        // Create dimensional variations
        $dim1 = $seg_a()->mod($dimensions[0][0].' '.$dimensions[0][1]);
        $dim2 = $seg_b()->mod($dimensions[1][0].' '.$dimensions[1][1]);
        $dim3 = $seg_c()->mod($dimensions[2][0].' '.$dimensions[2][1]);
        
        // Add chopping as separate operation
        if(!mt_rand(0,2)) {
            $dim1->chop(mt_rand(8,16));
        }
        if(!mt_rand(0,2)) {
            $dim2->chop(mt_rand(8,16));
        }
        if(!mt_rand(0,2)) {
            $dim3->chop(mt_rand(8,16));
        }
        
        // Create tensor combinations
        $tensor = silence(0);
        
        // Dimension 1 x 2
        if(!mt_rand(0,2)) {
            $combo12 = $dim1()->mix($dim2);
            $combo12->mod('gain -10');
            $tensor->add($combo12);
        }
        
        // Dimension 2 x 3
        if(!mt_rand(0,2)) {
            $combo23 = $dim2()->mix($dim3);
            $combo23->mod('gain -10');
            $tensor->add($combo23);
        }
        
        // Dimension 1 x 3
        if(!mt_rand(0,2)) {
            $combo13 = $dim1()->mix($dim3);
            $combo13->mod('gain -10');
            $tensor->add($combo13);
        }
        
        // Add some spectral variation
        if(!mt_rand(0,2)) {
            $tensor->mod('highpass '.mt_rand(500,2000));
        }
        
        // Add some temporal variation
        if(!mt_rand(0,2)) {
            $tensor->mod('delay 0.'.mt_rand(1,3));
        }
        
        // Rotate dimensions occasionally
        if(!mt_rand(0,4)) {
            shuffle($dimensions);
        }
        
        $out->add($tensor);
    }
    
    if(mt_rand(0,1)) $out->reverb();
    if(!mt_rand(0,2)) $out->mod('oops');
    
    return $out->x(4);
}; 