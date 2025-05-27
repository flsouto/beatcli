<?php
return function($a, $b) {
    first_segment($a, $b);
    normalize_speed($a, $b);
    
    $segments = 24; // Different segment count for variation
    $parts_a = $a->split($segments);
    $parts_b = $b->split($segments);
    
    $out = silence(0);
    $effects = [
        // Echo: gain-in gain-out delay decay
        fn($s) => $s->mod('echo 0.8 0.5 '.mt_rand(30,80).' 0.3'),
        // Pitch change (safer range)
        fn($s) => $s->pitch(mt_rand(-20,20)),
        // Tempo change (safer range)
        fn($s) => $s->mod('tempo '.mt_rand(85,115)/100),
        // Simple flanger
        fn($s) => $s->mod('flanger'),
    ];
    
    $wave_position = 0;
    foreach($parts_a as $i => $seg) {
        $processed = $seg();
        
        // Wave-like probability using sine function
        $wave = abs(sin($wave_position));
        $wave_position += 0.5;
        
        // Apply effects based on wave probability
        if($wave > 0.5) {
            $effect = $effects[mt_rand(0, count($effects)-1)];
            $effect($processed);
        }
        
        // Dynamic mixing with wave influence
        if($wave > 0.3) {
            $mix_gain = round($wave * -10);
            $processed->mix($parts_b[$i]()->mod("gain $mix_gain"));
        }
        
        $out->add($processed);
    }
    
    // Final effects with wave-based probability
    if($wave > 0.4) $out->mod('reverb 50 50');
    if($wave > 0.6) $out->mod('tremolo 4');
    
    return $out->x(4);
}; 