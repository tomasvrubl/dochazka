<?php

function reverseMe($n){

    $result = 0;
    for($i= 0; $i<4; $i++)
        {
            $result <<= 1;
            $result|= ($n & 1);
            $n >>= 1;
        }
        return $result;

    return $result;

}


function convToNew($hi, $lo){

    $h = dechex($hi);
    $l = dechex($lo);

    $l = strlen($l) < 8 ? "0".$l : $l;
    $h = strlen($h) < 2 ? "0".$h : $h;

    $o = convOldToNew(strtoupper($h.$l));
    $o->hi = $hi;
    $o->lo = $lo;

    return $o;
}


function convOldToNew($chip){
    $res = "";
    for($i=0; $i < strlen($chip); ++$i){    
        $rv = reverseMe(hexdec($chip[$i]));
        $res .= dechex($rv);
    }

    return  (object) array('chip'=> strtoupper($res), 'orig'=>$chip);
}


function convNewToOld($chip){

    $chip = substr($chip, 0, -2);
    $res = "";
    for($i=0; $i < strlen($chip); ++$i){    
        $rv = reverseMe(hexdec($chip[$i]));
        $res .= dechex($rv);
    }

    return  (object) array('chip'=> strtoupper($res), 'orig'=>$chip, 'hi'=>hexdec(substr($res, 0,2)), 'lo'=>hexdec(substr($res, 2)));
}

