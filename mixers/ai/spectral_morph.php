<?php

return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);

    // 1. Isolate the frequency bands
    $lows_from_a = $a()->lowpass(350);
    $highs_from_b = $b()->highpass(2500);

    // Isolate the mid-range from both sounds
    $mids_a = $a()->bandpass(1425, 2150); // Approx. 350Hz to 2500Hz
    $mids_b = $b()->bandpass(1425, 2150);

    // 2. Create the mid-range morph
    $num_segments = 4;
    $parts_a = $mids_a->split($num_segments);
    $parts_b = $mids_b->split($num_segments);

    $morphed_mids = silence(0);
    // First half of the mids from $a
    $morphed_mids->add($parts_a[0]());
    $morphed_mids->add($parts_a[1]());
    // Second half of the mids from $b
    $morphed_mids->add($parts_b[2]());
    $morphed_mids->add($parts_b[3]());

    // 3. Recombine the final sound
    $out = $lows_from_a->mix($morphed_mids)->mix($highs_from_b);

    // Add a final gain adjustment to prevent clipping from the mix
    $out->gain(-3);

    return $out->x(4);
};
