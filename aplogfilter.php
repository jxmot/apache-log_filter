<?php
require_once('./cidrmatch.php');
/*
    Apache Log Filter - a utility that reads an Apache httpd 
    log file and outputs a "filtered" result.

    It uses a JSON file to contain an array of "known" IP 
    addresses. If the IP in a log line of text matches any
    of the filters then that line is skipped. Otherwise it 
    is written to the output file.
*/
function apLogFilter($logfile, $outfile, $filtsel = 'iplist') {
$ret = new stdClass();
$ret->r = false;
$ret->m = '';

    // aplogfilter.json contains an array of IP addresses or CIDR ranges
    $filter = json_decode(file_get_contents('./aplogfilter.json'));
    if(($filter === null) || (!isset($filter->iplist))) {
        $ret->m = 'bad filter file';
        $ret->r = false;
    } else {
        // haven't found anything yet...
        $ipfound = false;
        // open the output file first...
        if(($op = fopen($outfile, 'w')) === false) {
            $ret->m = 'cannot open for output - '.$outfile;
            $ret->r = false;
        } else {
            // open the log file...
            if(($lp = fopen($logfile, 'r')) !== false) {
                // read one line at a time...
                while(($line = fgets($lp)) !== false) {
                    // extract the IP address from the line
                    if(($end = strpos($line, ' -')) === false) { 
                        $ret->m = 'unknown line - '.$line;
                        $ret->r = false;
                        break;
                    } else {
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
                                $ret->m = 'error writing to '.$outfile;
                                $ret->r = false;
                                break;
                            }
                        }
                    }
                }
                fflush($op);
                fclose($op);
                fclose($lp);
                if($ret->m === '') {
                    $ret->m = $outfile . ' has been saved';
                    $ret->r = true;
                }
            } else {
                fclose($op);
                $ret->m = 'cannot open for input - '.$logfile;
                $ret->r = false;
            }
        }
    }
    return $ret;
}
?>
