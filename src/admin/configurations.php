<?php 
    session_start();
    define('isSet', 1);
    define('setting', 1);
    require_once('settings.php');
    require_once('db_connect.php');
    require_once('../loginCheck.php');
    if (!isset($installed)) {
        die("You must run the installation file (install.php) in the admin directory in order to run this file.");
    }
    // Kiểm tra đăng nhập
    loginCheck($wdc_id,$wdc_token,$db,true);
    require_once('services/categoryConvert.php');
?>

<?php 
    
?>