<?php
return function($a, $b) {
    first_segment($a, $b);

    // Create three distinct spectral layers
    $layer1 = $a()->bandpass(mt_rand(300, 700), mt_rand(400, 800));
    $layer2 = $b()->bandpass(mt_rand(1000, 1500), mt_rand(500, 1000));
    $layer3 = $a()->bandpass(mt_rand(2000, 3000), mt_rand(800, 1200));

    // Apply different effects to each layer
    $layer1->mod('tremolo ' . (mt_rand(1, 4)) . ' 40');
    $layer2->mod('phaser ' . (mt_rand(5, 9) / 10) . ' ' . (mt_rand(8, 12) / 10) . ' ' . mt_rand(1, 4) . ' ' . (mt_rand(5, 9) / 10) . ' -s');
    $layer3->mod('chorus 0.5 0.9 60 0.4 0.25 3 -t');

    // Weave the layers together
    $out = $layer1->mix($layer2, false)->mix($layer3, false);

    // Add a final reverb for space
    if (mt_rand(0, 1)) {
        $out->reverb(mt_rand(40, 70), mt_rand(80, 100));
    }

    return $out->x(4);
};
