<?php
/* See https://github.com/Podcastindex-org/example-code for more examples */

//Figure the query
$maxResults = 15;
$the_query = $_SERVER['QUERY_STRING'];
if (strpos($the_query, "max=") === false){
    $the_query = $the_query . "&max=" . $maxResults;
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