<?php
require_once(__DIR__."/../Pattern.php");

$machilea = function() {

    $patterns = Pattern::$pool;
    $patterns = array_values(array_filter($patterns,fn($p) => strlen($p) == 16));

    $length = strlen($patterns[0]);

    // Step 1: infer allowed characters per column
    $allowedPerPos = array_fill(0, $length, []);

    foreach ($patterns as $p) {
        for ($i = 0; $i < $length; $i++) {
            $allowedPerPos[$i][$p[$i]] = true;
        }
    }

    // convert sets to arrays
    for ($i = 0; $i < $length; $i++) {
        $allowedPerPos[$i] = array_keys($allowedPerPos[$i]);
    }

    // Step 2: enforce monotone closure
    $order = ['_', 'a', 'b', 'c', 'd'];
    $rank = array_flip($order);

    for ($i = 0; $i < $length; $i++) {
        $maxRank = 0;
        foreach ($allowedPerPos[$i] as $ch) {
            $maxRank = max($maxRank, $rank[$ch]);
        }

        $newSet = [];
        for ($r = 0; $r <= $maxRank; $r++) {
            $newSet[] = $order[$r];
        }

        $allowedPerPos[$i] = $newSet;
    }

    // Step 3: enforce "no leading underscore"
    $allowedPerPos[0] = array_values(
        array_filter($allowedPerPos[0], fn($ch) => $ch !== '_')
    );

    // safety fallback
    if (empty($allowedPerPos[0])) {
        $allowedPerPos[0] = ['a'];
    }

    // Step 4: generator
    $generatePattern = function($allowedPerPos, $order) {
        $length = count($allowedPerPos);
        $result = array_fill(0, $length, '_');

        $introduced = [];
        $maxIndex = 0;

        for ($i = 0; $i < $length; $i++) {
            $choices = $allowedPerPos[$i];

            $valid = [];
            foreach ($choices as $ch) {
                if ($ch === '_') {
                    $valid[] = $ch;
                    continue;
                }

                $idx = array_search($ch, $order);

                if (in_array($ch, $introduced)) {
                    $valid[] = $ch;
                } elseif ($idx === $maxIndex + 1) {
                    $valid[] = $ch;
                } elseif ($idx <= $maxIndex) {
                    $valid[] = $ch;
                }
            }

            if (empty($valid)) {
                $result[$i] = '_';
                continue;
            }

            $pick = $valid[array_rand($valid)];
            $result[$i] = $pick;

            if ($pick !== '_' && !in_array($pick, $introduced)) {
                $introduced[] = $pick;
                $maxIndex = max($maxIndex, array_search($pick, $order));
            }
        }

        return implode('', $result);
    };

    return $generatePattern($allowedPerPos, $order);

};

return function($a, $b, $c) use($machilea){

    $a->resize($len1=mt_rand(2,8));
    $b->resize($len2=mt_rand(2,8));
    $c->resize($len3=mt_rand(2,8));


    $samples = new StdClass;
    $samples->pool = [$a,$b,$c];
    $samples->i = 0;

    $factory = function($key) use($samples){
        return function() use($key, $samples){
            $samples->i++;
            if(empty($samples->$key)){
                $s = pick($samples->pool);
                $len = $s->len();
                $samples->$key = $s->pick($len/16)->fade(0, -mt_rand(10,20));
            }
            if($samples->i > 1 && !mt_rand(0,2) && getenv('meshfx')){
                return apply_fx(($samples->$key)());
            }
            return $samples->$key;
        };
    };

    foreach(['a','b','c','d'] as $k){
        $with[$k] = $factory($k);
    }

    if(mt_rand(0,1)){
        do{
            $p1 = pick(Pattern::$pool);
            $p2 = pick(Pattern::$pool);
        } while($p1 == $p2 ||  strlen($p1) != strlen($p2) || levenshtein($p1, $p2) < 5);

        $meshed = [];
        $p1 = str_split($p1);
        $p2 = str_split($p2);

        $selector = mt_rand(0,1) ? fn($i) => ($i+1) % 2 : fn() => mt_rand(0,1);

        foreach($p1 as $i => $k){
            if($selector($i)){
                $meshed[] = $k;
            } else {
                $meshed[] = $p2[$i];
            }
        }
        echo "p1:".implode($p1)."\np2:".implode($p2);
        $result = implode($meshed);
    } else {
        $result = $machilea();
    }

    echo "\nmx:".$result."\n";
    file_put_contents('.last_meshtern', $result);

    $p = (new Pattern(str_split($result)))->with($with);
    $p->resize(mt_rand(5,16)/10);
    $loop = $p()->x(4)->resize(mt_rand(5,16));
    if(mt_rand(0,1)) $loop->reverb();
    return $loop;

};
