<?php 
    if(!defined('isSet')){
        die('<h1>Direct access is not allowed!</h1>');
    }
    require('settings.php');
    require('db_connect.php')
?>
<?php 
    $db = new dataBase($db_server,$db_name,$db_username,$db_password);
    // User 
    function loginCheck($username, $password)
    { 
        function usernameCheck($username)
        {
            $data = $GLOBALS['db']->selectValue('users', "username = '$username'",'username');
            $result = mysqli_fetch_assoc($data);
            if ($result['username'] == '') {
                echo 'error';
            }
            // echo count(mysqli_fetch_assoc($result));
        }
        function passwordCheck($username, $password)
        {
            $GLOBALS['db']->selectValue('users', "username = '$username'",'hash_password');
        }
        if (usernameCheck($username)&&passwordCheck($username, $password)) {
            
        }
    }
?>