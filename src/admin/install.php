<?php 
    switch ($_GET['step']) {
        case 1: // bước 1
            define('setting', 1);
            @require('settings.php'); // gọi file setting
            switch ($installed) { // Check if user have been installed this software yet.
                case true: // Nếu script đã cài đặt rồi
                    $html = '<b style="line-height: 50px;">This software had been installed successfully, please delete this file to avoid security problems</b>'; // Thông báo khi đã cài đặt phần mềm
                    $js = '';
                    break;
                default: // Else
                    // Hiện bảng nhập phần cài đặt CSDL
                    $html = "<h2>Step 1: Enter database configuration</h2>
                    <form method='POST'>
                    <p><label for='db_hostname'>Database hostname:</label> <input type='text' name='db_hostname' value='localhost'> Port: <input type='number' name='db_port' value='3306' ></p>
                    <p><label for='db_name'>Database name:</label> <input type='text' name='db_name'></p>
                    <p><label for='db_username'>Database username:</label> <input type='text' name='db_username' placeholder='root'></p>
                    <p><label for='db_password'>Database password:</label> <input type='password' name='db_password'></p>
                    <button id='wdc_next' onclick='next()'>Next</button></form>";
                    $js = "document.querySelector('#wdc_next').addEventListener('click', function (){form = document.querySelector('form');form.action = '?step=2';form.submit();});";
                break;
            }
        break;
        case 2: // bước 2
            define('setting', 1);
            @require('settings.php'); 
            switch ($installed) {
                case true:// Nếu script đã cài đặt rồi
                    $html = '<b style="line-height: 50px;">This software had been installed successfully, please delete this file to avoid security problems</b>';
                    $js = '';
                    break;
                default:
                    // Lấy dữ liệu từ step 1

                    $db_server = $_POST['db_hostname'];
                    $db_name = $_POST['db_name'];
                    $db_username = $_POST['db_username'];
                    $db_password = $_POST['db_password'];
                    $db_port = $_POST['db_port'];
                    $site_addr = ($_SERVER['HTTPS']) ? 'https://':'http://' . $_SERVER['SERVER_NAME'].str_replace('/admin/install.php', '', $_SERVER['PHP_SELF']);

                    define('isSet', 1);
                    require('db_connect.php'); // php script cho csdl
                    $db = new dataBase($db_server, $db_name,$db_username, $db_password, $db_port); // tạo kết nối mới
                    if ($db->checkDbConnection() == false) { // Nếu kết nối lỗi
                        $html = "<h2>Step 1: Enter database configuration</h2>
                        <form method='POST'>
                        <p><label for='db_hostname'>Database hostname:</label> <input type='text' name='db_hostname' value='localhost'> Port: <input type='number' name='db_port' value='3306' ></p>
                        <p><label for='db_name'>Database name:</label> <input type='text' name='db_name'></p>
                        <p><label for='db_username'>Database username:</label> <input type='text' name='db_username' placeholder='root'></p>
                        <p><label for='db_password'>Database password:</label> <input type='password' name='db_password'></p>
                        <button id='wdc_next' onclick='next()'>Next</button></form>";
                        $html .= "<b style='line-height: 50px'>Database connection error!</b>";
                        $js = "document.querySelector('#wdc_next').addEventListener('click', function (){form = document.querySelector('form');form.action = '?step=2';form.submit();});";
                    } else { // Nếu kết nối thành công, lưu cài đặt vào file settings.php
                        $setting = "\n".'$db_server = '."'$db_server'".";\n".
                        '$db_name = '."'$db_name'".";\n".
                        '$db_username = '."'$db_username'".";\n".
                        '$db_password = '."'$db_password'".";\n".
                        '$db_port = '.$db_port.";\n".
                        '$site_addr = '."'$site_addr'".";";

                        // Mở và ghi vào file settings.php
                        // mở file
                        $setting_f = fopen('settings.php', 'a');
                        fwrite($setting_f, $setting); // ghi file
                        $html = "<h2>Step 2: Set the global configuration</h2>
                        <form method='POST'>
                        <p><label for='sitename'>Site name:</label> <input type='text' name='sitename' value='wdcDoc'></p>
                        <p><label for='site_email'>Site email:</label> <input type='email' name='site_email' placeholder='doc@yoursite.com'></p>
                        <p><label for='admin_username'>Administrator username:</label> <input type='text' name='admin_username'></p>
                        <p><label for='admin_email'>Administrator email:</label> <input type='email' name='admin_email'></p>
                        <p><label for='admin_password'>Administrator password:</label> <input type='password' name='admin_password'></p>
                        <button id='wdc_next'>Next</button></form>";
                        $js = "document.querySelector('#wdc_next').addEventListener('click', function (){form = document.querySelector('form');form.action = '?step=3';form.submit();});";
                    }
                break;
            }
        break;
        case 3: // bước 3
            define('setting', 1);
            @require('settings.php');
            switch ($installed) {
                case true: // khi script đã được cài đặt
                    $html = '<b style="line-height: 50px;">This software had been installed successfully, please delete this file to avoid security problems</b>';
                    $js = '';
                    break; 
                default: // nếu ko
                    define('isSet', 1);
                    require('db_connect.php');
                    require('../validate.php'); // kéo file xác nhận vào
                    $db = new dataBase($db_server, $db_name,$db_username, $db_password, $db_port);
                    if ($db->checkDbConnection() == false) { // nếu kết nối lỗi
                        $js = "window.location.assign('install.php');";
                    } else { // nếu ko
                        $sitename = $_POST['sitename'];
                        $site_email = $_POST['site_email'];
                        $admin_username = $_POST['admin_username'];
                        $admin_email = $_POST['admin_email'];
                        $admin_password = $_POST['admin_password'];
                        
                        // Xác nhận dữ liệu từ user

                        function checkSiteName($sitename) { // kiểm tra tên site
                            if ($sitename == '') {
                                $GLOBALS['errSiteName'] = '<b>Site name must be fill out!</b>';
                                return false;
                            } elseif (strlen($sitename) > 50) {
                                $GLOBALS['errSiteName'] = '<b>Site name must be less than 50 characters</b>';
                                return false;
                            } elseif (!preg_match("/^[a-zA-Z0-9 ]*$/",$sitename)) {
                                $GLOBALS['errSiteName'] = '<b>Site name must contain only letters, numbers and white space</b>';
                                return false;
                            } else {return true;}
                        }
                        
                        function checkSiteEmail($site_email) { // kiểm tra site email
                            if ($site_email == '') {
                                $GLOBALS['errSiteEmail'] = '<b>Site email must be fill out!</b>';
                                return false;
                            } elseif (strlen($site_email) > 50) {
                                $GLOBALS['errSiteEmail'] = '<b>Site email must be less than 50 characters</b>';
                                return false;
                            } elseif (!filter_var($site_email, FILTER_VALIDATE_EMAIL)) {
                                $GLOBALS['errSiteEmail'] = '<b>Invalid Site email</b>';
                                return false;
                            } else {return true;}
                        }
                        // tạo obj mới từ class userChecking
                        $adminUserChecking = new userChecking($admin_username, $admin_email,$admin_password);

                        if (checkSiteName($sitename) && checkSiteEmail($site_email) && $adminUserChecking->checkUsername() && $adminUserChecking->checkEmail() && $adminUserChecking->checkPassword()) {
                            $setting = "\n".'$sitename = '."'$sitename';\n". // lưu phần cài đặt vào file settings.php
                            '$site_email = '."'$site_email';";
                            $setting_f = fopen('settings.php', 'a');
                            fwrite($setting_f, $setting);
                            // Tạo bảng
                            $db->createTable("CREATE TABLE users (
                                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                username varchar(50) NOT NULL UNIQUE,
                                email varchar(50) NOT NULL UNIQUE,
                                hash_password varchar(200) NOT NULL,
                                userrole varchar(20) NOT NULL,
                                ins_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                            )");
                            $db->createTable("CREATE TABLE posts (
                                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                title TEXT NOT NULL, 
                                content LONGTEXT NOT NULL,
                                author VARCHAR(50) NOT NULL,
                                date DATETIME NOT NULL,
                                PRIMARY KEY (id)
                            )");
                            $db->createTable("CREATE TABLE settings ( 
                                id INT UNSIGNED NOT NULL AUTO_INCREMENT, 
                                name TEXT NOT NULL, 
                                value TEXT NOT NULL, 
                                PRIMARY KEY (id)
                            )");
                            // các cột
                            $column = 'username, email, hash_password, userrole';
                            // chèn dữ liệu vào bảng
                            $db->insertTable('users', $column, $admin_username, $admin_email,$adminUserChecking->getHashPW(), 'Administrator');
                            $db->insertTable('posts', 'title, content, author, date', 'Welcome to your first post!', 'Thanks you for using wdcDoc!', 'wdcdoc', '2020-04-26 00:00:00');
                            $db->insertTable('settings', 'name, value', 'notify', 'Welcome to the Administrator Dashboard, thanks for using wdcDoc!');
                            $html = "<h2>Finish</h2>
                            <p>Congraturation! The installation has been finished successfully!</p>
                            <p>Click the button to return to your homepage: <a href='.'><button>Homepage</button></a></p>";
                            $setting = "\n".'$installed = true;';
                            $setting_f = fopen('settings.php', 'a');
                            fwrite($setting_f, $setting);
                        } else { // nếu ko
                            checkSiteName($sitename);// kt từ đầu.
                            checkSiteEmail($site_email);
                            $adminUserChecking->checkUsername();
                            $adminUserChecking->checkEmail();
                            $adminUserChecking->checkPassword();
                            $html = "<h2>Step 2: Set the global configuration</h2>
                            <form method='POST'>
                            <p><label for='sitename'>Site name:</label> <input type='text' name='sitename' value='wdcDoc'><br>".$GLOBALS['errSiteName']."</p>
                            <p><label for='site_email'>Site email:</label> <input type='email' name='site_email' placeholder='doc@yoursite.com'><br>".$GLOBALS['errSiteEmail']."</p>
                            <p><label for='admin_username'>Administrator username:</label> <input type='text' name='admin_username'><br>".$GLOBALS['errUsername']."</p>
                            <p><label for='admin_email'>Administrator email:</label> <input type='email' name='admin_email'><br>".$GLOBALS['errEmail']."</p>
                            <p><label for='admin_password'>Administrator password:</label> <input type='password' name='admin_password'><br>".$GLOBALS['errPassword']."</p>
                            <button id='wdc_next'>Next</button></form>";
                            $js = "document.querySelector('#wdc_next').addEventListener('click', function (){form = document.querySelector('form');form.action = '?step=3';form.submit();});";
                        }

                    }
                break;
            }
        break;
        default:
        define('setting', 1);
        @require('settings.php');
        switch ($installed) {
            case true: // nếu script đã cài rồi
                $html = '<b style="line-height: 50px;">This software had been installed successfully, please delete this file to avoid security problems</b>';
                $js = '';
                break;
            
            default: // nếu ko
                $html = "<h1>Welcome to the most simple Document Software</h1>
                <p>This script will help you to install the wdcDoc</p>
                <p>What are you waiting for? Press Next to start the installation now.</p>
                <p class='note'>This installation script only work well on PC/Laptop</p>
                <button id='wdc_next'>Next</button>";
                $js = "document.querySelector('#wdc_next').addEventListener('click', function (){window.location.assign('?step=1');});";
            break;
        }
    break;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wdcDocSoftware - Install</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #e6e6e6;
        }

        section {
            background: #fff;
            border-radius: 5px;
            padding: 15px;
        }
        
        #wdc_logo {
            display: block;
            margin: 0 auto;
        }

        h1,h2 {
            text-align: center;
            line-height: 50px;
        }

        p {
            line-height: 30px;
        }

        button {
            border: none;
            padding: 15px 30px;
            background-color: #d70751;
            color: white;
            cursor: pointer;
            outline: none;
            transition: ease-out .1s;
        }

        button:hover {
            background-color: #ff1869;
        }

        button:active {
            background-color: #920033;
        }

        #wdc_next {
            float: right;
        }

        .note {
            font-weight: bold;
        }

        label {
            display: inline-block;
            min-width: 43%;
            line-height: 50px;
        }
        input {
            padding: 10px;
            outline: none;
            border: none;
            border-bottom: 1px solid rgb(173, 173, 173);
        }
        input[type=number] {
            width: 15%;
        }
    </style>
</head>
<body>
    <noscript>
        <style>
            section {
                display: none !important
            }
        </style>
        <h1 style="color: red">Please turn on javascript to continue!</h1>
    </noscript>
    <section>
        <a href="wdcDoc.tld"><img src="https://tienminhvy.com/wp-content/uploads/2020/04/wdc.png" alt="wdcDoc" id="wdc_logo"></a>
        <h1>wdcDocSoftware - Install</h1>
        <hr>
        <?php echo $html; ?>
    </section>
    <script><?php echo $js ?></script>
</body>
</html>