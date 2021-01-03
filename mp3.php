<?php
include ("common.php");

if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != "")
    $mp3_info = $_SERVER['QUERY_STRING'];
else {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    die;
}
$cacheID = $mp3_info;
$url = urldecode(base64_decode($cacheID));

//Prepare the cache
$path = "cache";
if (!file_exists($path)) {
    mkdir($path, 0755, true);
}
//Make sure our filename isn't too long
$fullWritePath = getcwd() . "/" . $path . "/";
$availLength = 250 - strlen($fullWritePath);
$startPos = strlen($cacheID) - $availLength;
if ($startPos < 0)
    $startPos = 0;
$cacheID = substr($cacheID, $startPos);
//Fetch and cache the file if its not already cached
$path = $path . "/" . $cacheID . ".mp3";
if (!file_exists($path)) {
    file_put_contents($path, fopen($url, 'r'));
}

$fp = fopen($path, 'r');
// send the right headers
header("Content-Type: audio/mpeg3");
header("Content-Length: " . filesize($path));
// dump the file and stop the script
fpassthru($fp);
exit;

?>
