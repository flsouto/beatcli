<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);

    $core = $a()->lowpass(mt_rand(300, 500))->overdrive(mt_rand(10, 20));
    $texture = $b()->highpass(mt_rand(1000, 2000))->mod('oops');

    $out = silence(0);
    $segments = 32;
    $parts_core = $core->split($segments);
    $parts_texture = $texture->split($segments);

    $pulse_speed = 1.0;
    $pitch_instability = 0;

    for ($i = 0; $i < $segments; $i++) {
        $core_segment = $parts_core[$i];
        $texture_segment = $parts_texture[$i];

        // Modulate pulse speed
        if ($i % 4 === 0) {
            $pulse_speed = mt_rand(8, 12) / 10;
        }

        $core_segment->mod('tempo ' . $pulse_speed);
        $core_segment->pitch($pitch_instability);

        // Add textural glitches
        if (mt_rand(0, 3) === 0) {
            $texture_segment->chop(mt_rand(4, 8))->reverse();
        }

        // Unstable pitch shifting
        $pitch_instability += (mt_rand(-20, 20) / 10);

        $merged_segment = $core_segment->mix($texture_segment, false);
        $merged_segment->fade(0, -mt_rand(5, 15));

        $out->add($merged_segment);
    }

    if (mt_rand(0, 1)) {
        $out->reverb(mt_rand(20, 40));
    }

    return $out->x(4);
};
