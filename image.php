<?php
include ("common.php");

ini_set ('display_errors', 1);  
ini_set ('display_startup_errors', 1);  
error_reporting (E_ALL); 

if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != "")
    $mp3_info = $_SERVER['QUERY_STRING'];
else {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    die;
}
$cacheID = $mp3_info;
$url = base64url_decode($cacheID);

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
$path = $path . "/" . $cacheID . ".png";
if (!file_exists($path)) {
    file_put_contents($path, fopen($url, 'r'));
}

//Make image smaller so we don't crush tiny old devices
resize(128, $path, $path);

$fp = fopen($path, 'r');
// send the right headers
header("Content-Type: image/png");
header("Content-Length: " . filesize($path));
// dump the file and stop the script
fpassthru($fp);
//exit;

function resize($newWidth, $targetFile, $originalFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default: 
                    throw new Exception('Unknown image type.');
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

    $newHeight = ($height / $width) * $newWidth;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($targetFile)) {
            unlink($targetFile);
    }
    $image_save_func($tmp, $targetFile);
}
?>
