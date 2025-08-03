<?php
return function($a, $b) {
    // Ensure inputs have a comparable length
    first_segment($a, $b);
    normalize_speed($a, $b);

    $texture = $a;
    $rhythm = $b;

    $output = $texture();

    $num_fragments = mt_rand(15, 30);
    $rhythm_fragment_len = $rhythm->len() / $num_fragments;

    for ($i = 0; $i < $num_fragments; $i++) {
        // Pick a small fragment from the rhythmic source
        $fragment = $rhythm->cut($i * $rhythm_fragment_len, $rhythm_fragment_len)();

        // Pitch-shift it to create melodic variation
        $pitch = mt_rand(-1200, 1200);
        $fragment->pitch($pitch);

        // Apply a subtle effect to help it blend or stand out
        if (mt_rand(0, 2) == 0) {
            $fragment->mod('phaser 0.6 0.6 2 0.5 -t');
        } else {
            $fragment->reverb(mt_rand(10, 30), mt_rand(40, 80));
        }
        
        $fragment->gain(mt_rand(-6, -2));

        // Overlay the fragment onto the main texture
        $output->mix($fragment, false, ($i * $rhythm_fragment_len));
    }

    if (mt_rand(0, 1)) {
        $output->lowpass(mt_rand(4000, 8000));
    }

    return $output->norm()->x(4);
};
