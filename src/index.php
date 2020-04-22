<?php 
    define('setting', 1);
    require('admin/settings.php');
    if (!isset($installed)) {
        $install_addr = ($_SERVER['HTTPS']) ? 'https://':'http://' . $_SERVER['SERVER_NAME'].str_replace('/index.php', '/install.php', $_SERVER['PHP_SELF']);
        echo "You must run the installation file (install.php) at <a href='$install_addr'>$install_addr</a>";
    }
?>