<?php

return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);

    // The rhythmic loop to impose its rhythm
    $trigger_loop = $a();
    // The sustained loop that will be gated
    $gated_loop_base = $b();

    $num_segments = 16; // More segments for a more precise rhythmic gate
    $gated_parts = $gated_loop_base->split($num_segments);

    $processed_gated_loop = silence(0);

    // 1. Apply a sharp fade-in to each segment of the sustained loop
    foreach ($gated_parts as $part) {
        $segment = $part();
        // Apply a very fast fade-in to create the pumping effect
        $segment->fade(-30, 0);
        $processed_gated_loop->add($segment);
    }

    // 2. Mix the original trigger loop with the newly gated loop
    $out = $trigger_loop->mix($processed_gated_loop);

    // Add a final gain adjustment to prevent clipping
    $out->gain(-2);

    return $out->x(4);
};
