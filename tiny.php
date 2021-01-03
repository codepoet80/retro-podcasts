<?php
include("common.php");
ini_set ('display_errors', 1);  
ini_set ('display_startup_errors', 1);  
error_reporting (E_ALL);  

//Extract the query
if (!isset($_GET["url"])) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
}
$cacheID = $_GET["url"];
$url = base64url_decode($cacheID);
$as = "xml";
if (isset($_GET["type"]))
    $as = $_GET["type"];
$maxItems = 10;
if (isset($_GET["max"]))
    $maxItems = $_GET["max"];

//Prepare the cache
$path = "cache";
if (!file_exists($path)) {
    mkdir($path, 0755, true);
}
$path = $path . "/" . $cacheID . ".xml";

//Fetch and cache the file if its not already cached (and the cache is not too old)
if (file_exists($path) && time()-filemtime($path) > 24 * 3600) {
    // file older than 24 hours
    unlink($path);
}  
if (!file_exists($path)) {
    file_put_contents($path, fopen($url, 'r'));
}
$rss = simplexml_load_file($path);

//Figure out some paths
$this_path = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$image_helper_path = str_replace(basename($_SERVER['PHP_SELF']), "image.php", $this_path);
$mp3_helper_path = str_replace(basename($_SERVER['PHP_SELF']), "mp3.php", $this_path);

if ($as == "json") {    //JSON RESPONSE

    //Build items list from RSS Feed
    $items = $rss->channel->item;
    $channel = $rss->channel;
    $i = 0;
    $data = array();
    foreach ($items as $item) { 
        //determine if the tiny client will need help with the enclosed mp3 file
        //TODO: exclude non-audio items
        $mp3_url = (string) $item->enclosure['url'];
        if (strpos($mp3_url, "https:") !== false) {
            $mp3_url = $mp3_helper_path . "?" . base64url_encode($mp3_url);
        }
        $data[] = array(
            'title' => (string) $item->title,
            'description' => (string) $item->description,
            'pubDate' => (string) $item->pubDate,
            'enclosure' => array(
                'enclosure:url' => $mp3_url,
                'enclosure:type' => (string) $item->enclosure['type']
            ),
            'duration' => (string) $item->children('itunes', true)->duration,
            
        );
        if (++$i == $maxItems) break;
    }
    //determine if the tiny client will need help with the images
    //TODO: Do we need the second (itunes) image?
    $image_url = (string)$channel->image->url;
    if (strpos($image_url, "https:") !== false) {
        $image_url = $image_helper_path . "?" . base64url_encode($image_url);
    }

    //Build the outer structure and add the inner structure of items
    $data_wrapper = array(
        'title' => (string)$channel->title,
        'link' => (string)$channel->link,
        'language' => (string)$channel->language,
        'copyright' => (string)$channel->copyright,
        'description' => (string)$channel->description,
        'itunes:category' => (string) $channel->children('itunes', TRUE)->category->attributes()->text,
        'image' => array (
            'url' => $image_url,
            'title' => (string)$channel->image->title,
            'link' => (string)$channel->image->link
        ),
        //Inner structure
        'items' => $data
    );

    //Return the result in JSON format
    header('Content-Type: application/json');
    echo json_encode($data_wrapper);
}
else {  //XML RESPONSE

    //Simplify remote RSS Feed
    $doc = new DOMDocument; 
    $doc->loadXML($rss->asXML());    
    $thedocument = $doc->documentElement;

    //determine if the tiny client will need help with the images
    $list = $thedocument->getElementsByTagNameNS('http://www.itunes.com/dtds/podcast-1.0.dtd', 'image');
    for ($i = $list->length; --$i >= 0; ) {
        $el = $list->item($i);
        $attr = $el->getAttribute('href');
        if (strpos($attr, "https:") !== false)
        {
            $el->setAttribute('href', $image_helper_path . "?" . base64url_encode($attr));
        }
    }
    $list = $thedocument->getElementsByTagName('url');
    for ($i = $list->length; --$i >= 0; ) {
        $el = $list->item($i);
        $image_url = $el->nodeValue;
        if (strpos($image_url, "https:") !== false)
        {
            $el->nodeValue = $image_helper_path . "?" . base64url_encode($image_url);
        }
    }
    
    //remove superflous data
    $cleanup = $thedocument->getElementsByTagName('summary');
    $list = removeXMLNodes($cleanup);
    $cleanup = $thedocument->getElementsByTagName('encoded');
    $list = removeXMLNodes($cleanup);
    $cleanup = $thedocument->getElementsByTagName('owner');
    $list = removeXMLNodes($cleanup);
    //$cleanup = removeXMLNodes($thedocument->getElementsByTagNameNS('http://www.itunes.com/dtds/podcast-1.0.dtd', '*'));

    //shorten the list of items
    $list = $thedocument->getElementsByTagName('item');
    for ($i = $list->length; --$i >= 0; ) {
        $el = $list->item($i);
        if ($i > $maxItems) {
            $el->parentNode->removeChild($el);
        }
    }

    //determine if the tiny client will need help with the enclosed mp3 file
    //TODO: exclude non-audio items
    $list = $thedocument->getElementsByTagName('enclosure');
    for ($i = $list->length; --$i >= 0; ) {
        $el = $list->item($i);
        $attr = $el->getAttribute('url');
        if (strpos($attr, "https:") !== false)
        {
            $el->setAttribute('url', $mp3_helper_path . "?" . base64url_encode($attr));
        }
    }

    //Return the result in XML format
    header('Content-Type: text/xml');        
    echo $doc->saveXML(); 
}

function removeXMLNodes(&$list)
{
    for ($i = $list->length; --$i >= 0; ) {
        $el = $list->item($i);
        $el->parentNode->removeChild($el);
    }
}

?>