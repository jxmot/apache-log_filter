<?php
/*
    Is the string a CIDR?
*/
function iscidr($cidr) {
    return (strpos($cidr, '/') === false ? false : true);
}

/*
    If an IP address is in range of the supplied CIDR
    then return true.
*/
function cidrmatch($ip, $cidr) {
    list($subnet, $bits) = explode('/', $cidr);
    if($bits === null) {
        $bits = 32;
    }
    $ip = ip2long($ip);
    $subnet = ip2long($subnet);
    $mask = -1 << (32 - $bits);
    $subnet &= $mask;
    return ($ip & $mask) == $subnet;
}
?>