<?php

return function($a) {
    first_segment($a);
    $original_len = $a->len();

    // 1. Create the two layers
    $dry_layer = $a();
    $texture_layer = $a();

    // 2. Process the texture layer heavily
    // Reverse it for an unnatural feel
    $texture_layer->reverse();
    // Filter it to create a more focused, resonant sound
    $texture_layer->mod('bandpass 1800 800');
    // Add heavy reverb to wash it out
    $texture_layer->reverb(80, 90);
    // Add a phaser for movement
    $texture_layer->mod('phaser 0.6 0.6 3 -t');
    // Reduce gain to keep it from overpowering the dry signal
    $texture_layer->gain(-6);

    // 3. Create the evolution by fading the texture in over the full duration
    $texture_layer->fade(mt_rand(-35, -25), 0); // Fade from a low volume to normal

    // 4. Combine the layers
    $out = $dry_layer->mix($texture_layer);

    // Ensure the final output length matches the original
    $out->resize($original_len);

    return $out->x(4);
};
