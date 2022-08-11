<?php
//Figure out what protocol the client wanted
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    echo file_get_contents("https://www.webosarchive.org/menu.php?content=podcasts");
else
    echo file_get_contents("http://www.webosarchive.org/menu.php?content=podcasts");
?>