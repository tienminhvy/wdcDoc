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
    $typeRequest = $_GET['type'];
    $rdfrom = $_GET['rdfrom'];
    if ($rdfrom == 'add') {
        $success = 'Add new user successfully!';
    }
    // Xử lí dữ liệu
    // lấy dữ liệu từ user
    $uUsername = $_POST['uUsername'];
    $uEmail = $_POST['uEmail'];
    $uPassword = $_POST['uPassword']; 
    $uConfirmPassword = $_POST['uConfirmPassword']; 
    $admincp = $_POST['admincp'];
    if (isset($uUsername)||isset($uEmail)||isset($uPassword)){ // nếu đã nhập 1 trong 3
        $userCheck = new userChecking($uUsername,$uEmail,$uPassword); // tạo obj kiểm tra
        if ($userCheck->checkUsername()&&$userCheck->checkEmail()&&$userCheck->checkPassword()) { // nếu 3 đk đều đúng
            if ($uConfirmPassword == '') { // nếu chưa xác nhận mk
                $errPassword = '<b>You must confirm the password.</b>';
            } elseif ($uPassword!=$uConfirmPassword) { // nếu mk xác nhận ko khớp
                $errPassword = '<b>The confirm password do not match!</b>';
            } else { // th còn lại
                switch ($admincp) { 
                    case 'on': // nếu có quyền truy cập ad
                        $user = new userRegister($uUsername, $uEmail, $uPassword, 'admin', $db); // tạo mới user
                        if ($user->status){ // nếu tạo thành công
                            header("Location: $site_addr/admin/users.php", true, 303);
                            die('Create new user success!');
                        } else { // ngược lại
                            $error = 'Error when creating new user, please try again later.';
                        }
                        break;
                    
                    default: // nếu ko
                        // tương tự như trên
                        $user = new userRegister($uUsername, $uEmail, $uPassword, 'member', $db);
                        if ($user->status){
                            header("Location: $site_addr/admin/users.php?rdfrom=add", true, 303);
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
    // code in phần view
    $getFDb = $db->selectCol('users', 'id', 'username', 'email'); // lấy id, title, tác giả từ db
    $getPermisionFDb = $db->selectCol('users_permision', 'admincp'); // lấy quyền của users
    $getCFDb = $db->selectCol('users', 'COUNT(id) AS count'); // đếm số lượng
    $result = mysqli_fetch_all($getFDb);
    $resultPermision = mysqli_fetch_all($getPermisionFDb);
    $resultC = mysqli_fetch_assoc($getCFDb);
    if ($resultC['count'] < 10) { // nếu số lượng bài dưới 10
        $user=1; // bài đầu tiên là 1
        for ($i=0; $i < $resultC['count']; $i++) {  // vòng lặp in bài
            $template = ''; // reset biến template
            for ($j=0; $j < count($result[$i]); $j++) {
                if ($j>0) {
                    $template .= 
                    "<td>".$result[$i][$j]."</td>";
                } elseif ($j == 0) {
                    $userId = $result[$i][$j]; // lấy id của user
                }
            }
            $template .= "<td>".$resultPermision[$i][0]."</td>";
            $print .= // lưu bài vào biến
            "<tr>
            <th scope='row'>$user</th>
            $template
            <td><span><a href='users.php?type=edit&id=$userId' class='btn btn-info'>Edit</a></span><span><button data-id='$userId' class='btn btn-danger delete'>Remove</button></span></td>
            </tr>";
            $user++;
        }
    } elseif ($pagination==1) {
        
    } else {
        
    }
    // end
    if ($typeRequest==''){
        $usersMethod = 'main';
    } else {
        $usersMethod = $typeRequest;
    }
    $css = 
"<style>
.users.$usersMethod > a {
    background: black;
    color: rgb(231, 231, 231);
}
#wdc_admin_users > a{
    background: black;
}
.wdc_submenu_01 {
    position: static !important;
    top: 0 !important;
    z-index: 1 !important;
    background: #363636 !important;
    width: 200px !important;
    left: 0 !important;
    opacity: 1 !important;
    visibility: visible !important;
    transition: unset !important;
}
.wdc_submenu_01_collapsed {
    left: 45px !important;
    opacity: 0;
}
</style>";
    $windowLocation = 'window.location.assign(`users.php?type=edit&id=${id}&delete=true`);';
    $js =
"<script>
let collapseCol = false;
    $('#wdc_admin_users > ul').addClass('wdc_submenu_01');
    $('#wdc_collapseActivate').on('click', function (){
        switch (collapseCol) {
            case false:
            $('#wdc_admin_users > ul').removeClass('wdc_submenu_01');
            $('#wdc_admin_users > ul').addClass('wdc_submenu_01_collapsed');
            collapseCol = true;
            break;

            default:
            $('#wdc_admin_users > ul').addClass('wdc_submenu_01');
            $('#wdc_admin_users > ul').removeClass('wdc_submenu_01_collapsed');
            collapseCol = false;
        break;}
    });
$('.delete').on('click', function (){
    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Deleted!',
                    'User has been deleted.',
                    'success'
                )
                id = $(this).attr('data-id');
                $windowLocation
            }
    })
});

</script>";
    // template thông báo
    function errorTemplate($error)
    {
        if (isset($error)) {
            return "<div class='alert alert-danger' role='alert'>$error</div>";
        }
        return;
    }
    function successTemplate($success)
    {
        if (isset($success)) {
            return "<div class='alert alert-success' role='alert'>$success</div>";
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
                ".successTemplate($success)."
                <table class='table'>
                    <thead>
                        <tr>
                            <th scope='col'>#</th>
                            <th scope='col'>Username</th>
                            <th scope='col'>Email</th>
                            <th scope='col'>Can access to administrator dashboard?</th>
                            <th scope='col'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        $print
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>";
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
    echo $css.$js;
?>