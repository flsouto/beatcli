<?php
return function($a) {
    first_segment($a);

    $out = silence(0);
    $num_steps = mt_rand(16, 32);
    $steps = $a->split($num_steps);

    $pitch_drop = 0;
    $filter_freq = 8000;

    foreach ($steps as $i => $step) {
        // Apply pitch drop and filtering
        $step->pitch($pitch_drop);
        $step->lowpass($filter_freq);

        // Add a subtle phaser effect
        $step->mod('phaser 0.8 0.8 2 0.5 -t');

        $out->add($step);

        // Evolve parameters
        $pitch_drop -= mt_rand(10, 30);
        $filter_freq -= 200;
    }

    if (mt_rand(0, 1)) {
        $out->reverb(mt_rand(20, 50), mt_rand(80, 100));
    }

    return $out->x(4);
};
