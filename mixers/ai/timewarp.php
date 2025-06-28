<?php

return function($a) {
    first_segment($a);

    $segments = mt_rand(8, 16);
    $parts = $a->split($segments);
    
    $out = silence(0);
    $speed = (mt_rand(0, 1) == 0) ? 0.5 : 2.0; // Start slow or fast
    $speed_increment = ($speed > 1) ? -0.1 : 0.1;

    foreach ($parts as $part) {
        $segment = $part();

        // Apply the speed change
        $segment->speed($speed);

        // Add a warping effect
        if (mt_rand(0, 2) === 0) {
            $segment->phaser(mt_rand(1, 5) / 10, mt_rand(1, 5) / 10, mt_rand(1, 5));
        }

        // Add the processed segment to the output
        $out->add($segment);

        // Increment the speed for the next segment
        $speed += $speed_increment;
        if ($speed <= 0.1) $speed = 0.1; // Prevent silence
    }

    // Add a final touch
    if (mt_rand(0, 1)) {
        $out->mod('oops');
    }

    return $out->x(4);
};
