<?php 
    session_start(); // tạo session
    define('setting', 1);
    define('isSet', 1);
    require('admin/settings.php');
    if (!isset($installed)) { // nếu chưa cài đặt
        die("You must run the installation file (install.php) in the admin directory in order to run this file.");
    }
    require('admin/functions.php');
    // lấy giá trị từ user
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = $_POST['remember'];
    // nếu user đã nhập usernam vs password
    if (isset($username)&&isset($password)) {
        $loginCheck = new loginCheck($username, $password, $remember, $db);
    }
    if ($_SESSION['logged']||$_COOKIE['logged']) { // nếu đã đăng nhập thì chuyển hướng đến trang chủ
        echo "<script>window.location.assign('.');</script>";
        die('Logged');
    }
?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Magic :3
        <?php if (isset($username)&&isset($password)) {echo $loginCheck->checkLogin();} ?>
    }, false);
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?php echo $sitename ?></title>
    <!-- Bootstrap 4.x CSS -->
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
    <link rel="stylesheet" href="wdc_content/lib/css/reset.css">
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #E6E6E6;
        }
        .row {
            min-height: 100vh;
        }
        label[for='wdc_iusername'], label[for='wdc_ipassword'] {
            width: 20%;
            text-align: right;
            padding-right: 10px;
        }
        #wdc_iusername, #wdc_ipassword {
            width: 80%;
            padding: 10px;
            border-radius: 100px;
            border: 1px solid #d70751;
            outline: none;
            margin: 10px 0 20px 0;
        }
        #wdc_login {
            width: 100%;
        }
        #wdc_login-box {
            background: white;
            border-radius: 10px;
            padding: 30px;
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
            outline: none;
        }
        #wdc_register, #wdc_forgotpw {
            margin: 10px 0;
            color: #d70751;
            transition: color .1s linear;
        }
        #wdc_register:hover, #wdc_forgotpw:hover{
            text-decoration: none;
            color: #ff1869;
        }
        #wdc_register:active, #wdc_forgotpw:active {
            color: #920033;
        }
        #wdc_registor_text, #wdc_forgotpw_text {
            margin: 10px 0 0 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row d-flex flex-row justify-content-center align-items-center">
            <div class="col-5" id="wdc_login-box">
                <h2 class="text-center">Login to sitename</h2>
                <form method="POST" id="wdc_loginForm" action="">
                    <label for="wdc_iusername">Username:</label><input type="text" name="username" id="wdc_iusername" placeholder="Enter your username">
                    <label for="wdc_ipassword">Password:</label><input type="password" name="password" id="wdc_ipassword" placeholder="Enter your password">
                    <button id="wdc_login">Login</button>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" class="custom-control-input" id="wdc_rememberme">
                        <label class="custom-control-label" for="wdc_rememberme">Remember me </label>
                    </div>
                    <p class="text-center" id="wdc_forgotpw_text"><a href="" id="wdc_forgotpw">Forgot password?</a></p>
                    <p class="text-center" id="wdc_registor_text"><a href="" id="wdc_register">Don't have account? Register here.</a></p>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap 4.x JS + Jquery 3.x-->
    <script src='https://code.jquery.com/jquery-3.4.1.min.js' integrity='sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=' crossorigin='anonymous'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js' integrity='sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1' crossorigin='anonymous'></script>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' crossorigin='anonymous'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
</body>
</html>