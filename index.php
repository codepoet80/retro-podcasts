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
<title>webOS Podcast Directory</title>
</head>
<body onload="document.getElementById('txtSearch').focus()">
<?php
include ("common.php");

$action_path = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$search_path = str_replace("index.php", "search.php", $action_path);
$feed_path = str_replace("index.php", "tiny.php", $action_path);
$image_path = str_replace("index.php", "image.php", $action_path);

$max=15;
if (isset($_GET['max']))
	$max=$_GET['max'];
if ($_GET['search'] != null)
{
    	$app_path = $search_path . "?max=" . $max ."&q=" . urlencode($_GET['search']);
	$app_file = fopen($app_path, "rb");
	$app_content = stream_get_contents($app_file);
	fclose($app_file);
	$app_response = json_decode($app_content, true);
}

?>
    <p align='middle' style='margin-top:50px;'><a href="/"><img src='icon-128.png' style="width:128px; height: 128px;" border="0"></a></p>
    <p align='middle' style='margin-bottom:30px;'><i>Search for podcasts by title</i></p>
    <form action="<?php echo $action_path; ?>" method="get">
        <div style="margin-left:auto;margin-right:auto;text-align:center;">
        <input type="text" id="txtSearch" name="search" class="search" placeholder="Just type...">
        <input type="submit" class="search-button" value="Search">
        </div>
    </form>
<?php
if (count($app_response["feeds"]) > 0)
{
    echo("<table cellpadding='5'>");
    foreach($app_response["feeds"] as $app) {
        echo("<tr><td align='center' valign='top'><img style='width:64px; height:64px' src='". $image_path . "?img=" . base64url_encode($app["image"]) . "' border='0'>");
        echo("<td width='100%' style='padding-left: 14px'><b>{$app["title"]}</b><br/>");
        echo("<i>" . $app["description"] . "...</i><br/>");
        echo("<a href='{$app["url"]}' target='_blank'>Full Feed</a> | ");
        echo("<a href='" . $feed_path . "?url=" . base64url_encode($app["url"]) . "' target='_blank'>Tiny Feed</a>");
        echo("</td></tr>");
    }
    echo("</table>");
}
?>
    <br>&nbsp;
    <p align='middle'><small>Search Provided by <a href='https://podcastindex.org/'>Podcast Index.org</a> | Download the <a href='http://appcatalog.webosarchive.com/showMuseum.php?search=podcast+directory'>webOS App</a></small></p>

</body>
</html>
