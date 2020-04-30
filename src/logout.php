<?php 
    define('setting', 1);
    require('admin/settings.php');
    session_start(); // đăng xuất
    if (isset($_COOKIE['wdc_id'])||isset($_COOKIE['wdcToken'])) {
        setcookie("wdc_id", "", time()-3600,'/'); // xoá cookie
        setcookie("wdcToken", "", time()-3600,'/');
    } else {
        session_unset();
    }
    session_destroy();
    header("Location: $site_addr/login.php",TRUE,303);
    die('Logged out');
?>