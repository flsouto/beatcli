<?php

class Pattern{

    static $pool = [];

    protected $slen = .1;
    protected $samples = [];

    function __construct(protected array $pattern){}
    function with(array $samples){
        $this->samples = [...$this->samples, ...$samples];
        return $this;
    }
    function replace($find, $replace){
        return new self(
            array_map(fn($k) => $k == $find ? $replace : $k, $this->pattern)
        );
    }

    function resize($time){
        $this->slen = $time / count($this->pattern);
        return $this;
    }
    function len(){
        return count($this->pattern) * $this->slen;
    }
    function make(){
        $out = null;
        $len = $this->len();
        foreach($this->pattern as $i => $k){
            if($k == '_') continue;
            if(!isset($this->samples[$k])){
                throw new \Exception("Sample '$k' not provided for pattern: $this.");
            }
            $s = $this->samples[$k];
            if(!is_callable($s)){
                throw new \Exception("Sample '$k' is invalid for pattern: $this.");
            }
            $s = $s();
            $lpad = ($i * $this->slen);
            $rpad = $len - $lpad - $this->slen;
            if($rpad < 0.00001) $rpad = 0;
            $layer = $s()->pad($pad = "$lpad $rpad");
            if($layer->len() > $len){
                $layer->cut(0,$len);
            }
            if($out){
                $out->mix($layer, false);
            } else {
                $out = $layer;
            }
        }
        return $out;
    }
    function __invoke(){
        return $this->make();
    }
    function __toString(){
        return implode($this->pattern);
    }
}

function compile_pattern($pattern){
    preg_replace_callback("/{}/");
}

const L = 'L';

function pattern($pattern=null){
    $pool = Pattern::$pool;
    if(isset($pool[L])){
        $pattern = $pool[L];
    }
    if(!$pattern){
        $pattern = $pool[array_rand($pool)];
    } else if(isset($pool[$pattern])){
        $pattern = $pool[$pattern];
    }
    if(!is_array($pattern)){
        $pattern = str_split($pattern);
    }
    $pattern = array_filter($pattern,'trim');
    return new Pattern($pattern);
}


Pattern::$pool = [
    "a_b_a__ca_b_c___",
    "a_b_a__ca_b_d___",
    "a_a_b__ca_b_d___",
    "aa__b__ca_c_d___",
    "aa__b__aa___c___",
    "aa__b__ad___c___",
    "ababbabb",
    "dabab_dbabc_",
    "cabab_d_",
    "abcabcab",
    "abcabcaa",
    "ab_c_b__",
    "aa_b_cb_",
    "aaaaa_____b_ac__",
    "d_aa_bb_cccc____",
    "aabac_a_a_abad__",
    "aa_ac_a_a_abac__",
    "abbbcb_aabbbcac_",
    "abbbcbaabbbcac__",
    "a_b_bbb_c_d_d_d_",
    "a_b_d_b_c_b_bdb_",
    "a_b_a_b_c_b_bcbb",
    "a_b_a_b_a_b_b_a_b_b_a_b_a_a_a_a_",
    "a_a_b_a_a_b_a_b_",
    "a_a_b_a_a_b_a_c_",
    "a_a_b_a_a_b_aabc",
    "a_a_b_a_c_b_c_b_",
    "a_a_b_a_c_b_ccba",
    "a__b_a_a_b__",
    "a__b_aca_d__",
    'a_ba_ab_',
    'a_bacab_',
    'adbacabd',
    'adcbcabd',
    'a_b_a_baa_baaaba',
    'aa__b___d_b_c___',
    'abcb',
    'abbbcbbb',
    'a_b_',
    'aaaab__aaaaa__b_',
    'aabaabaaaababbba',
    'aaa_b_aaaa__b___',
    'aaa_b_aaaaa_b___',
    'aaa_b_aaaaa___b_',
    'aaaabaaaaaaa_aaa',
    'aaaabaaaaaaa_aab',
    'aaaabaaaaaaa_aca',
    'a_a_b_abcbaab_a_',
    'a_a_b_abcbaab_c_',
    'a_c_b_cbcbacb_c_',
    'a___b__bcbaab___',
    'aaaab_a_aaab_ab_',
    'aabcabac_abcabac',
    'aabcabac_abcab_d',
    'aaaa_aaaa_bababa',
    'a_a_b_a___a_b___',
    'aa_baa_baa_baa_a',
    'ab_ab_ab_ab_a_b_',
    'ab_ab_ab_ab_baaa',
    'a___b__a_aa_b___',
    'aaaabaababaabbbb',
    'baaabaaabaab_b_b',
    'a_aba_a_aba_b_b_',
    'aa_ab_aa_a_abab_',
    'abbaaaabbaaaa_b_',
    'a__ab_a___abab__',
    'a_a___b__a_ba__b',
    'babab_babab_bab_',
    'babab_bab_babab_',
    'abbaaabb',
    'a_ba_bb_',
    'a_ba_bbaabbabbbb',
    'a_ba_ab_',
    'aa_bb_aa_bbbbbb_',
    'ab_baab_',
    'a_b_b_ab_bb__b__',
    'aa_a_aa_',
    'a_baca__',

    // GENERATED PATTERNS
    'a_b_ab__c_d_a_d_',
    'a_a_b___aba_d_b_',
    'aaa_b_abcbcab___',
    'a___cba_a_a_a___',
    'a_a_bbb_cbd_d_d_',
    'aab_b__ca_b_c_b_'
];

