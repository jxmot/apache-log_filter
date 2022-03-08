<?php
require_once('./aplogfilter.php');

// check the args, and input file...
if(!isset($argv[1])) {
    exit('need input log file name');
}

if(!file_exists($argv[1])) {
    exit($argv[1]. ' does not exist');
}

if(!isset($argv[2])) {
    exit('need output file name');
}

/*
    Let's filter!
*/
$ret = apLogFilter($argv[1], $argv[2]);
exit($ret->m);
?>