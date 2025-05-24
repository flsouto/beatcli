<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);
    
    $segments = [16, 32, 64][mt_rand(0,2)];
    $parts_a = $a->split($segments);
    $parts_b = $b->split($segments);
    
    $out = silence(0);
    $mirror_point = count($parts_a) / 2;
    
    for($i = 0; $i < $mirror_point; $i++) {
        // Forward section
        $seg_a = $parts_a[$i];
        $seg_b = $parts_b[$i];
        $mixed = $seg_a->mix($seg_b()->mod('gain -'.mt_rand(5,15)));
        
        // Mirror section
        $mirror_idx = count($parts_a) - 1 - $i;
        $seg_a_mirror = $parts_a[$mirror_idx]()->mod('reverse');
        $seg_b_mirror = $parts_b[$mirror_idx]()->mod('reverse');
        
        // Apply subtle pitch variations to mirror
        if(mt_rand(0,1)) {
            $seg_a_mirror->pitch(mt_rand(-20,20));
            $seg_b_mirror->pitch(mt_rand(-20,20));
        }
        
        $mixed_mirror = $seg_a_mirror->mix($seg_b_mirror()->mod('gain -'.mt_rand(5,15)));
        
        // Pan opposite sides
        $mixed->mod('gain -5');
        $mixed_mirror->mod('gain -5');
        
        $out->add($mixed)->add($mixed_mirror);
    }
    
    if(mt_rand(0,1)) $out->reverb();
    return $out->x(4);
}; 