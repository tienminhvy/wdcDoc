<?php 
    define('setting', 1);
    require('admin/settings.php');
    session_start(); // đăng xuất
    $_COOKIE['logged'];
    if ($_COOKIE['logged']) {
        setcookie("logged", "", time()-3600,'/'); // xoá cookie
        setcookie("userrole", "", time()-3600,'/');
        setcookie("username", "", time()-3600,'/');
    } else {
        session_unset();
    }
    session_destroy();
    header("Location: $site_addr/login.php",TRUE,303);
    die('Logged out');
?>