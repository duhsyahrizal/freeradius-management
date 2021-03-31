<?php
/**
 * Convert bytes to the unit specified by the $to parameter.
 * 
 * @param integer $bytes The filesize in Bytes.
 * @param string $to The unit type to convert to. Accepts K, M, or G for Kilobytes, Megabytes, or Gigabytes, respectively.
 * @param integer $decimal_places The number of decimal places to return.
 *
 * @return integer Returns only the number of units, not the type letter. Returns 0 if the $to unit type is out of scope.
 *
 */
function convert_bytes($bytes, $to, $decimal_places = 1) {
    $formulas = array(
        'K' => number_format($bytes / 1024, $decimal_places),
        'M' => number_format($bytes / 1048576, $decimal_places),
        'G' => number_format($bytes / 1073741824, $decimal_places)
    );
    return isset($formulas[$to]) ? $formulas[$to] : 0;
}

function convert_bytes_without_comm($bytes, $to, $decimal_places = 0) {
    $formulas = array(
        'K' => ($bytes / 1024),
        'M' => ($bytes / 1048576),
        'G' => ($bytes / 1073741824)
    );
    return isset($formulas[$to]) ? $formulas[$to] : 0;
}

function showQuota($username) {
    include('env.php');
    $conn = new mysqli($servername, $userdb, $passworddb, $database);

    $queryQuota = "SELECT SUM(acctinputoctets+acctoutputoctets) AS total_quota FROM radacct WHERE username = '".$username."'";
    $result = $conn->query($queryQuota);
    $res = $result->fetch_assoc();
    $res = $res['total_quota'];
    if($res == null) return 0;
    
    return convert_bytes($res, 'M', 0);
  }

function secondsToWords($seconds)
{
    if(empty($seconds)) {
        return '0 seconds';
    }
    $ret = "";

    /*** get the days ***/
    $days = intval(intval($seconds) / (3600*24));
    if($days> 0)
    {
        $ret .= "$days days ";
    }

    /*** get the hours ***/
    $hours = (intval($seconds) / 3600) % 24;
    if($hours > 0)
    {
        $ret .= "$hours hours ";
    }

    /*** get the minutes ***/
    $minutes = (intval($seconds) / 60) % 60;
    if($minutes > 0)
    {
        $ret .= "$minutes minutes ";
    }

    /*** get the seconds ***/
    $seconds = intval($seconds) % 60;
    if ($seconds > 0) {
        $ret .= "$seconds seconds";
    }

    return $ret;
}
?>