<html>
<head>
<link rel="shortcut icon" sizes="256x256" href="icon-256.png">
<link rel="shortcut icon" sizes="196x196" href="icon-196.png">
<link rel="shortcut icon" sizes="128x128" href="icon-128.png">
<link rel="shortcut icon" href="favicon.ico">
<link rel="icon"type="image/png" href="icon.png" >
<link rel="apple-touch-icon" href="icon.png"/>
<link rel="apple-touch-startup-image" href="icon-256.png">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="white" />

<link rel="stylesheet" href="style.css">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=1" />
<title>webOS Podcast Directory - Podcast Detail</title>
</head>
<body onload="document.getElementById('txtSearch').focus()">
<?php include ("menu.php"); ?>
<div class="content">
<?php
include ("common.php");

$action_path = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$search_path = str_replace("detail.php", "getdetailby.php", $action_path);
$feed_path = str_replace("detail.php", "tiny.php", $action_path);
$image_path = str_replace("detail.php", "image.php", $action_path);

if (isset($_SERVER['QUERY_STRING']))
{
    $app_path = $search_path . "?" . $_SERVER['QUERY_STRING'];
	$app_file = fopen($app_path, "rb");
	$app_content = stream_get_contents($app_file);
	fclose($app_file);
	$app_response = json_decode($app_content, true);
}

$back_path = $_SERVER['HTTP_REFERER'];
if (strpos($back_path, "?search=") === false) {
    $back_path = "index.php";
}
?>
<small>&lt; <a href="<?php echo $back_path ?>">Back to Search</a></small>
<?php
if (isset($app_response["feed"]))
{
    $feed = $app_response["feed"];
    //echo $feedimage;
    echo "<p align='middle' style='margin-top:30px;'><img src='" . $feed['image'] . "' style='width:128px; height: 128px;' border='0'></p>";
    echo "<p align='middle' style='margin-top:12px;margin-bottom:32px;'><b>" . $feed['title'] . "</b></p>";
    echo $feed['description'];

    echo "<ul>";
    echo "<li><b>Author:</b> " . $feed['author'] . "</li>";
    echo "<li><b>Episodes:</b> " . $feed['episodeCount'] . "</li>";
    if (isset($feed['categories'])) {
        echo "<li><b>Categories:</b> ";
        foreach ($feed['categories'] as $category) {
            echo $category . " ";
        }
    }
    echo "<li><b>Website:</b> <a href='" . $feed['link'] . "'>" . $feed['link'] . "</a></li>";
    echo("<li><b>Subscribe: </b><a href='{$feed["url"]}' target='_blank'><img src='rss-16.png' style='vertical-align: top;'> Full Feed</a> | ");
    echo("<a href='$feed_path?url=" . base64url_encode($feed["url"]) . "' target='_blank'><img src='rss-16.png' style='vertical-align: top;'> Tiny Feed</a></li>");
    if (isset($feed['substitution_reason'])) {
        echo "<li><small><b>Notes:</b> " . $feed['substitution_reason'] . "</small></li>";
    }
    echo "</ul>";
    echo "<!--" . json_encode($feed) . "-->";
}
?>
<?php include ("help.html")?>

<p align='middle' style="margin-top: 38px"><small>Search Provided by <a href='https://podcastindex.org/'>Podcast Index.org</a> | <a href="https://github.com/codepoet80/retro-podcasts">Host this yourself</a> | <a href='http://appcatalog.webosarchive.com/showMuseum.php?search=podcast+directory'>Download the webOS App</a></small></p>
</div>
</body>
</html>
