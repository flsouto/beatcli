<?php

return function($a) {
    first_segment($a);
    $out = $a(); // Keep the original sound as the base

    $min_snippet_len = 0.15; // Maximum possible snippet length from mt_rand(5, 15) / 100

    // If the input sound is too short for the effect, return it with a simple mod.
    if ($a->len() <= $min_snippet_len) {
        if (mt_rand(0, 1)) {
            $out->reverse();
        }
        $out->mod('overdrive 2 2');
        return $out->x(4);
    }

    // 1. Extract a very short, random audio snippet
    do{
    $snippet_len = mt_rand(5, 15) / 100; // 0.05 to 0.15 seconds
    $start_pos = mt_rand(0, $a->len() - $snippet_len);
    } while(!$start_pos);

    $snippet = $a->pick($start_pos);

    // Failsafe in case the pick operation results in a zero-length snippet
    if ($snippet->len() == 0) {
        return $out->x(4); // Return original if snippet is empty
    }

    // 2. Process the snippet to create variation
    if (mt_rand(0, 1)) {
        $snippet->reverse();
    }
    if (mt_rand(0, 2) === 0) {
        $snippet->pitch(mt_rand(-400, 400));
    }
    if (mt_rand(0, 2) === 0) {
        $snippet->mod('highpass ' . mt_rand(600, 1200));
    }

    // 3. Repeat the snippet to create the stutter/drill effect
    $stutter_effect = silence(0);
    $repetitions = mt_rand(6, 12);
    for ($i = 0; $i < $repetitions; $i++) {
        $stutter_effect->add($snippet);
    }
    $stutter_effect->speed(mt_rand(11, 15) / 10); // Slightly speed up the stutter sequence

    // 4. Layer the stutter effect back onto the original track at a random position
    if ($out->len() > $stutter_effect->len()) {
        $insert_pos = mt_rand(0, $out->len() - $stutter_effect->len());
        $out->add($stutter_effect, $insert_pos);
    }

    // 5. Final touch
    if (mt_rand(0, 1)) {
        $out->mod('overdrive 5 5');
    }

    return $out->x(4);
};
