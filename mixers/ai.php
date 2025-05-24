<?php
return function(...$loops) {
    // Get list of available AI patterns
    $ai_patterns = glob(__DIR__.'/ai/*.php');
    
    // Check for environment variable override
    $selected_pattern = null;
    if(getenv('ai_mixer')) {
        $env_pattern = __DIR__.'/ai/'.getenv('ai_mixer').'.php';
        if(file_exists($env_pattern)) {
            $selected_pattern = $env_pattern;
        }
    }
    
    // If no env var or pattern not found, select random
    if(!$selected_pattern) {
        $selected_pattern = $ai_patterns[array_rand($ai_patterns)];
    }
    
    // Load and execute the selected pattern
    $pattern = require($selected_pattern);
    return $pattern(...$loops);
}; 