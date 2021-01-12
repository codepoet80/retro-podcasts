<?php
/* See https://github.com/Podcastindex-org/example-code for more examples */
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (E_ALL);
//Figure the query
$maxResults = 15;
if (isset($_GET['q'])) {
	$the_query = $_GET['q'];
	if (isset($_GET['max'])) {
		$the_query = $the_query . "&max=" . $_GET['max'];
	}

} else {
	$the_query = $_SERVER['QUERY_STRING'];
}
$the_query = "https://api.podcastindex.org/api/1.0/search/byterm?q=" . $the_query;

//API Credentials
include ("secrets.php");
$apiHeaderTime = time();
//Hash them to get the Authorization token
$hash = sha1($apiKey.$apiSecret.$apiHeaderTime);

//Set the required headers
$headers = [
    "User-Agent: webOSPodcastDirectory/1.0",
    "X-Auth-Key: $apiKey",
    "X-Auth-Date: $apiHeaderTime",
    "Authorization: $hash"
];

//Make the request to an API endpoint
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $the_query);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//Collect and show the results
header('Content-Type: application/json');
$response = curl_exec ($ch);
curl_close ($ch);
print_r($response);

?>
