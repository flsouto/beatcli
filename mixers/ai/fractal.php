<?php
return function($a) {
    first_segment($a);
    
    $base_segment = $a->pick(mt_rand(4,8)/10);
    $out = silence(0);
    
    $fractal_levels = mt_rand(3,5);
    $speed_multiplier = 1;
    
    for($level = 0; $level < $fractal_levels; $level++) {
        $segment = $base_segment();
        
        // Each level gets progressively faster and pitched
        $segment->mod('tempo '.$speed_multiplier);
        $segment->pitch(mt_rand(-30,30) * $level);
        
        // Add some variation
        if(!mt_rand(0,2)) {
            $segment->mod('oops');
        }
        
        if(!mt_rand(0,2)) {
            $segment->chop(mt_rand(8,16));
        }
        
        $segment->fade(0, -($level * 5));
        $out->add($segment);
        
        $speed_multiplier *= 1.5;
    }
    
    if(mt_rand(0,1)) $out->reverb();
    return $out->x(4);
}; 