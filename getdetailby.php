<?php
/* See https://github.com/Podcastindex-org/example-code for more examples */

include ("common.php");

//Figure the query
if (isset($_GET['url'])) {
	$url = urldecode($_GET['url']);
	if (strpos($url, "tiny.php?url=") !== false) {
		$urlparts = explode("tiny.php?url=", $url);
		if (isset($urlparts[1])) {
			$url = $urlparts[1];
			$url = explode("&", $url);
			$url = $url[0];
			$url = base64url_decode($url);
		}
		//http://podcasts.webosarchive.org/tiny.php?url=aHR0cHM6Ly9mZWVkcy5tZWdhcGhvbmUuZm0vc3R1ZmZ5b3VzaG91bGRrbm93&max=25
	}
	$the_query = "byfeedurl?url=" . $url;
} 

if (isset($_GET['id'])) {
	$the_query = "byfeedid?id=" . $_GET['id'];
}
if (!isset($the_query)) {
	$the_query = $_SERVER['QUERY_STRING'];
}
$the_query = "https://api.podcastindex.org/api/1.0/podcasts/" . $the_query;

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

include ("substitutions.php");
if (isset($substitutions)) {
	$response_obj = json_decode($response);
	if (isset($response_obj->feed)) {
		if (isset($substitutions[$response_obj->feed->url])) {
			$new_feed = $substitutions[$response_obj->feed->url];
			foreach ($new_feed as $key => $value) {
				$response_obj->feed->$key = $value;
			}
		}
	}	
	$response = json_encode($response_obj);
} 	
print_r($response);

?>
