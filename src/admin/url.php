<?php 
    define('isSet', 1);
    define('setting', 1);
    require_once('settings.php');
    require_once('db_connect.php');

    $type = $_POST['type'];
    $id = $_POST['id'];
    $url = $site_addr."/$type.php?id=$id";
    echo "<a href='$url' target='_blank'>$url</a>"
?>