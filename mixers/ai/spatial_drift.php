<?php

return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);

    $segments = mt_rand(8, 12);
    $parts_a = $a->split($segments);
    $parts_b = $b->split($segments);
    
    $out = silence(0);
    
    $pan_a = -1.0; // Start hard left
    $pan_b = 1.0;  // Start hard right
    $pan_drift = 2.0 / $segments; // Amount to shift pan each step

    foreach ($parts_a as $i => $seg_a_base) {
        $seg_b_base = $parts_b[$i];

        $seg_a = $seg_a_base();
        $seg_b = $seg_b_base();

        // Pan the segments to opposite sides using remix
        $vol_a_l = pow(cos(($pan_a + 1) * M_PI / 4), 2);
        $vol_a_r = pow(sin(($pan_a + 1) * M_PI / 4), 2);
        $vol_b_l = pow(cos(($pan_b + 1) * M_PI / 4), 2);
        $vol_b_r = pow(sin(($pan_b + 1) * M_PI / 4), 2);

        $seg_a->mod("remix 1p" . $vol_a_l . " 1p" . $vol_a_r);
        $seg_b->mod("remix 1p" . $vol_b_l . " 1p" . $vol_b_r);

        // Apply a subtle, channel-specific effect for more separation
        if (mt_rand(0, 2) === 0) {
            $seg_a->mod('delay 0.015'); // slight delay on the left channel
        }
        if (mt_rand(0, 2) === 0) {
            $seg_b->mod('reverb 10 50'); // tiny reverb on the right channel
        }

        // Add both panned segments to the output
        $out->add($seg_a);
        $out->add($seg_b);

        // Drift the panning towards the opposite side for the next segment
        $pan_a += $pan_drift;
        $pan_b -= $pan_drift;
    }

    // A final chorus can help blend the drifting parts smoothly
    if (mt_rand(0, 1)) {
        $out->mod('chorus 0.7 0.8 50 0.4 0.3 2 -t');
    }

    return $out->x(4);
};
