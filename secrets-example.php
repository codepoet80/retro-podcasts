<?php
//Get your own API credentials here: https://api.podcastindex.org/signup
$apiKey = "YOURAPIKEY";    //PodcastIndex API Key
$apiSecret = "YOURAPISECRET";    //PodcastIndex API Secret
//Uses server redirect capabilities to hide the actual file path of the MP3.
//  Leave true for older clients that can't handle redirects (server must support Apache X-Sendfile or X-Accel-Redirect)
//  If false, an http redirect will be used, so you must expose the cache folder
$hideFilePath = false; 
?>
