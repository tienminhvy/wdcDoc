<?php 
    define('setting', 1);
    require('admin/settings.php');
    if (!isset($installed)) {
        die("You must run the installation file (install.php) in the admin directory in order to run this file.");
    }
?>