<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);

    $segments_a = $a->split(mt_rand(4, 8));
    $segments_b = $b->split(mt_rand(4, 8));

    $out = silence(0);
    $max_segments = max(count($segments_a), count($segments_b));

    for ($i = 0; $i < $max_segments; $i++) {
        if (isset($segments_a[$i])) {
            $seg_a = $segments_a[$i];
            $seg_a->pitch(mt_rand(-300, 300));
            $seg_a->pan(-0.5);
            $out->add($seg_a);
        }

        if (isset($segments_b[$i])) {
            $seg_b = $segments_b[$i];
            $seg_b->lowpass(mt_rand(600, 1500));
            $seg_b->pan(0.5);
            $out->add($seg_b);
        }
    }

    if (mt_rand(0, 1)) {
        $out->reverb(mt_rand(10, 40));
    } else {
        $out->mod('phaser 0.8 0.7 4 0.5 2 -t');
    }

    return $out->x(2);
};
