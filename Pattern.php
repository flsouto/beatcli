<?php

class Pattern{
    protected $slen = .1;
    protected $samples = [];
    function __construct(protected array $pattern){}
    function with(array $samples){
        $this->samples = [...$this->samples, ...$samples];
        return $this;
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
function pattern($pattern){
    if(!is_array($pattern)){
        $pattern = str_split($pattern);
    }
    return new Pattern($pattern);
}

