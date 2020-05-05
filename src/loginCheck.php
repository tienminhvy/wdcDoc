<?php 
    if(!defined('isSet')){
        die('<h1>Direct access is not allowed!</h1>');
    }
    if (isset($_SESSION['wdc_id'])&&isset($_SESSION['wdcToken'])) { // nếu đăng nhập bằng session
        $wdc_id = $_SESSION['wdc_id'];
        $wdc_token = $_SESSION['wdcToken'];
    } elseif (isset($_COOKIE['wdc_id'])&&isset($_COOKIE['wdcToken'])) { // nếu đăng nhập bằng cookie
        $wdc_id = $_COOKIE['wdc_id'];
        $wdc_token = $_COOKIE['wdcToken'];
    } elseif (!$loginpage) { // nếu không phải đang ở trang đăng nhập và chưa đăng nhập
        header("Location: $site_addr/login.php", true, 303);
        die('Authentication failed');
    }
    function loginCheck($wdc_id, $wdc_token, $db, $isAdminPage = false) // kiểm tra việc đăng nhập
    {                   //id        token   csdl    mặc định trang admin là false
        // bắt đầu lấy dữ liệu từ db
        // lấy dữ liệu về username, token_count, token
        $dataFDb = $db->selectValue('users', "wdc_id='$wdc_id'", 'COUNT(wdc_id) as wdc_id_count', 'username');
        $results = mysqli_fetch_assoc($dataFDb);
        $dataFTokenDb = $db->selectValue('users_token', "wdc_id='$wdc_id'", 'COUNT(token) as token_count', 'token');
        $tokenResults = mysqli_fetch_assoc($dataFTokenDb);
        // kết thúc lấy dữ liệu
        $GLOBALS['username'] = $results['username']; // lưu tên đăng nhập là biến toàn cục
        $site_addr = $GLOBALS['site_addr']; // lấy dữ liệu từ biến toàn cục
        if (!$results['wdc_id_count']>0 || !$tokenResults['token_count']>0 || $wdc_token!=$tokenResults['token']) {
            // nếu ko tồn tại user trong table users hoặc ko có token hoặc token hiện tại khác token trong db thì trở về trang đăng nhập
            if (isset($_COOKIE['wdc_id'])||isset($_COOKIE['wdcToken'])) { // nếu có cookie
                setcookie("wdc_id", "", time()-3600,'/'); // xoá cookie
                setcookie("wdcToken", "", time()-3600,'/');
            } else { // nếu ko
                session_unset();
            }
            session_destroy();
            header("Location: $site_addr/login.php", true, 303); // chuyển hướng sang trang đăng nhập
            die('Authentication failed'); 
        } elseif ($isAdminPage) { // nếu đang ở trang quản trị
            // lấy dữ liệu từ db 
            $dataFPermisionDb = $db->selectValue('users_permision', "wdc_id='$wdc_id'", 'admincp');
            $permisionResults = mysqli_fetch_assoc($dataFPermisionDb);
            switch ($permisionResults['admincp']) { // kiểm tra nếu user có quyền truy cập trang admin ko
                case 'no': // nếu ko thì chuyển hướng sang trang chủ
                    header("Location: $site_addr", true, 303);
                    die('Authentication failed'); 
                    break;
            }
        }
    }
    function logoutCheck($wdc_id, $wdc_token, $db) // kiểm tra xem có đăng xuất chưa
    {
        // Bắt đầu lấy dữ liệu từ db
        // lấy dữ liệu về wdc_id_count, username, token_count, token, admincp
        $dataFDb = $db->selectValue('users', "wdc_id='$wdc_id'", 'COUNT(wdc_id) as wdc_id_count', 'username');
        $results = mysqli_fetch_assoc($dataFDb);
        $dataFTokenDb = $db->selectValue('users_token', "wdc_id='$wdc_id'", 'COUNT(token) as token_count', 'token');
        $tokenResults = mysqli_fetch_assoc($dataFTokenDb);
        $dataFPermisionDb = $db->selectValue('users_permision', "wdc_id='$wdc_id'", 'admincp');
        $permisionResults = mysqli_fetch_assoc($dataFPermisionDb);
        // Kết thúc lấy dữ liệu
        $site_addr = $GLOBALS['site_addr']; // lấy dữ liệu từ biến toàn cục
        if ($results['wdc_id_count']>0 && $tokenResults['token_count']>0 && $wdc_token==$tokenResults['token']) {
        // nếu tồn tại user trong bảng users và có token trong bảng token và token hiện tại bằng token trong db
            switch ($permisionResults['admincp']) { // kiểm tra nếu user có quyền truy cập trang admin ko
                case 'yes': // nếu có
                    header("Location: $site_addr/admin", true, 303);
                    die('Already loggedin'); 
                    break;
                
                default: // mặc định
                    header("Location: $site_addr", true, 303);
                    die('Already loggedin'); 
                    break;
            }
        }
    }
?>