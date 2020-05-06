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
    require_once('../validate.php');
    require_once('functions.php');
?>

<?php 
    // Xử lí dữ liệu
    $uUsername = $_POST['uUsername'];
    $uEmail = $_POST['uEmail'];
    $uPassword = $_POST['uPassword']; 
    $uConfirmPassword = $_POST['uConfirmPassword']; 
    $admincp = $_POST['admincp'];
    if (isset($uUsername)||isset($uEmail)||isset($uPassword)){
        $userCheck = new userChecking($uUsername,$uEmail,$uPassword);
        if ($userCheck->checkUsername()&&$userCheck->checkEmail()&&$userCheck->checkPassword()) {
            if ($uConfirmPassword == '') {
                $errPassword = '<b>You must confirm the password.</b>';
            } elseif ($uPassword!=$uConfirmPassword) {
                $errPassword = '<b>The confirm password do not match!</b>';
            } else {
                switch ($admincp) {
                    case 'on':
                        $user = new userRegister($uUsername, $uEmail, $uPassword, 'admin', $db);
                        if ($user->status){
                            header("Location: $site_addr/admin/users.php", true, 303);
                            die('Create new user success!');
                        } else {
                            $error = 'Error when creating new user, please try again later.';
                        }
                        break;
                    
                    default:
                        $user = new userRegister($uUsername, $uEmail, $uPassword, 'member', $db);
                        if ($user->status){
                            header("Location: $site_addr/admin/users.php", true, 303);
                            die('Create new user success!');
                        } else {
                            $error = 'Error when creating new user, please try again later.';
                        }
                        break;
                }
            }
        }
    }
?>

<?php 
    function errorTemplate($error)
    {
        if (isset($error)) {
            return "<div class='alert alert-danger' role='alert'>$error</div>";
        }
        return;
    }
    require_once('themes/default/header.php');
    require_once('themes/default/modules/mainMenus.php');
    $htmlAddUser =
"<main>
    <div class='container'>
        <div class='row'>
            <div class='col'>
                <h2 class='text-center'>Add new user</h2>
                ".errorTemplate($error)."
                <form method='POST'>
                    <div class='input-group mb-3'>
                        <div class='input-group-prepend'>
                            <label class='input-group-text' for='uUsername'>Username</label>
                        </div>
                        <input type='text' class='form-control' name='uUsername' id='uUsername' value='$uUsername'>
                    </div>
                    $errUsername
                    <div class='input-group mb-3'>
                        <div class='input-group-prepend'>
                            <label class='input-group-text' for='uEmail'>Email</label>
                        </div>
                        <input type='text' class='form-control' name='uEmail' id='uEmail' value='$uEmail'>
                    </div>
                    $errEmail
                    <div class='input-group mb-3'>
                        <div class='input-group-prepend'>
                            <label class='input-group-text' for='uPassword'>Password</label>
                        </div>
                        <input type='password' class='form-control' name='uPassword' id='uPassword'>
                    </div>
                    <div class='input-group mb-3'>
                        <div class='input-group-prepend'>
                            <label class='input-group-text' for='uConfirmPassword'>Confirm password</label>
                        </div>
                        <input type='password' class='form-control' name='uConfirmPassword' id='uConfirmPassword'>
                    </div>
                    $errPassword
                    <div class='custom-control custom-checkbox'>
                        <input type='checkbox' class='custom-control-input' name='admincp' id='admincp' name='admincp'>
                        <label class='custom-control-label' for='admincp'>Can access to Administrator Dashboard</label>
                    </div>
                    <button class='btn btn-info btn-block'>Add</button>
                </form>
            </div>
        </div>
    </div>
</main>";
    $htmlEditUser =
"<main>
    <div class='container'>
        <div class='row'>
            <div class='col'>
                <h2 class='text-center'>Edit user</h2>
            </div>
        </div>
    </div>
</main>";
    $htmlViewUser =
"<main>
    <div class='container'>
        <div class='row'>
            <div class='col'>
                <h2 class='text-center'>View all users</h2>
            </div>
        </div>
    </div>
</main>";
    $typeRequest = $_GET['type'];
    switch ($typeRequest) {
        case 'add':
            echo $htmlAddUser;
            break;
        case 'edit':
            echo $htmlEditUser;
            break;
        
        default:
            echo $htmlViewUser;
            break;
    }
    require_once('themes/default/footer.php');
?>