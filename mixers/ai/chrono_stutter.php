<?php
return function($a) {
    first_segment($a);
    
    $num_segments = mt_rand(8, 16);
    $segments = $a->split($num_segments);
    
    $out = silence(0);
    $time_stretch = 1.0;
    $pitch_shift = 0;
    $gate_time = 0.1;

    foreach($segments as $i => $segment) {
        $processed_segment = $segment();

        // Time-stretch
        $processed_segment->mod("tempo " . $time_stretch);

        // Pitch-shift
        $processed_segment->pitch($pitch_shift);

        // Gating
        $processed_segment->mod("tremolo " . (1 / $gate_time) . " 50");

        // Add to output
        $out->add($processed_segment);

        // Evolve parameters
        $time_stretch += (mt_rand(0, 1) ? 0.05 : -0.05);
        $pitch_shift += mt_rand(-20, 20);
        $gate_time *= 0.9;
    }

    if (mt_rand(0, 1)) {
        $out->reverb(mt_rand(20, 50), mt_rand(50, 90));
    }

    return $out->x(4);
};
