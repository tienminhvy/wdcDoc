<?php 
    define('isSet', 1);
    if(!defined('isSet')){
        die('<h1>Direct access is not allowed!</h1>');
    }
    
    $db_server = $_SESSION['db_server'];
    $db_username = $_SESSION['db_username'];
    $db_password = $_SESSION['db_password'];
    $db_name = $_SESSION['db_name'];
    $db_port = $_SESSION['db_port'];

    if (!@mysqli_connect($db_server, $username, $db_password, $db_name, $db_port)){
        die('Database connection error!');
    }
    
    echo 'Connect to database successfully!';
?>