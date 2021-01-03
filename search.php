<?php
header('Content-Type: application/json');
$the_query = $_SERVER['QUERY_STRING'];

//Required values  
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
curl_setopt($ch, CURLOPT_URL,"https://api.podcastindex.org/api/1.0/search/byterm?max=15&q=" . $the_query);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  

//Collect and show the results  
$response = curl_exec ($ch);  
curl_close ($ch);  
print_r($response);
//echo print_r(json_decode($response), TRUE); 

?>