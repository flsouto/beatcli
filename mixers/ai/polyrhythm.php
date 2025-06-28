<?php

return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);

    // Create polyrhythmic effect by splitting into different numbers of segments
    $segments_a = mt_rand(3, 5);
    $segments_b = mt_rand(6, 8);
    
    $parts_a = $a->split($segments_a);
    $parts_b = $b->split($segments_b);
    
    $out = silence(0);
    
    // Weave the segments together
    $max_segments = max($segments_a, $segments_b);
    for ($i = 0; $i < $max_segments; $i++) {
        $seg_a = $parts_a[$i % $segments_a]();
        $seg_b = $parts_b[$i % $segments_b]();

        // Apply random effects to each segment
        if (mt_rand(0, 1)) {
            $seg_a->pitch(mt_rand(-200, 200));
        }
        if (mt_rand(0, 1)) {
            $seg_b->lowpass(mt_rand(800, 2000));
        }
        if (mt_rand(0, 2) === 0) {
            $seg_a->chop(mt_rand(2, 4));
        }
        if (mt_rand(0, 2) === 0) {
            $seg_b->reverse();
        }

        // Mix the segments
        $out->add($seg_a->mix($seg_b));
    }

    // Apply a final effect
    if (mt_rand(0, 1)) {
        $out->reverb(mt_rand(20, 50));
    }

    return $out->x(4);
};
