<?php
require_once('./cidrmatch.php');

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
    Apache Log Filter - a utility that reads an Apache httpd 
    log file and outputs a "filtered" result.

    It uses a JSON file to contain an array of "known" IP 
    addresses. If the IP in a log line of text matches any
    of the filters then that line is skipped. Otherwise it 
    is written to the output file.
*/
function apLogFilter($logfile, $outfile) {
    // filter.json contains an array of IP addresses or CIDR ranges
    $filter = json_decode(file_get_contents('./filter.json'));
    if(($filter === null) || (!isset($filter->iplist))) exit('bad filter file');
    // haven't found anything yet...
    $ipfound = false;
    // open the output file first...
    if(($op = fopen($outfile, 'w')) === false) exit('cannot open for output - '.$outfile);
    // open the log file...
    if(($lp = fopen($logfile, 'r')) !== false) {
        // read one line at a time...
        while(($line = fgets($lp)) !== false) {
            // extract the IP address from the line
            if(($end = strpos($line, ' -')) === false) { 
                exit('unknown line - '.$line);
            }
            $unkip = substr($line, 0, $end);
            // compare the unknown IP address against the filters...
            foreach($filter->iplist as $filterip) {
                if(iscidr($filterip)) {
                    if(cidrmatch($unkip, $filterip)) {
                        $ipfound = true;
                        break;
                    }
                } else {
                    if($filterip === $unkip) {
                        $ipfound = true;
                        break;
                    } 
                }
            }
            // all filters checked, was there a match?
            if($ipfound === true) {
                // yes, found a match. reset and continue...
                $ipfound = false;
            } else {
                // no matches, write the line into the output file
                if(fwrite($op, $line, strlen($line)) === false) {
                    exit('error writing to '.$outfile);
                }
            }
        }
        fflush($op);
        fclose($op);
        fclose($lp);
        exit($outfile . ' has been saved');
    } else exit('cannot open for input - '.$logfile);
}

/*
    Let's filter!
*/
apLogFilter($argv[1], $argv[2]);
?>
