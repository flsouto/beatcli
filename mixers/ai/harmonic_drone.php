<?php

return function($a) {
    first_segment($a);

    // 1. Create the harmonic layers
    $fundamental = $a();
    
    // Sub-octave layer for bass
    $sub_octave = $a()->pitch(-1200)->lowpass(250)->gain(-4);

    // Perfect fifth for harmonic color
    $fifth = $a()->pitch(700)->gain(-7);

    // Super-octave for brightness
    $super_octave = $a()->pitch(1200)->gain(-8);

    // Shimmer layer for high-end texture
    $shimmer = $a()->pitch(2400)->highpass(4000)->reverb(80, 90)->gain(-15);

    // 2. Mix the layers together
    $out = $fundamental
        ->mix($sub_octave)
        ->mix($fifth)
        ->mix($super_octave)
        ->mix($shimmer);

    // 3. Apply a final chorus to blend the layers
    if (mt_rand(0, 1)) {
        $out->mod('chorus 0.6 0.9 50 0.3 0.2 1.5 -s');
    }

    // Ensure the final length is correct
    $out->resize($a->len());

    return $out->x(4);
};
