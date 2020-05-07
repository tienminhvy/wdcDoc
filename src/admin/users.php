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
    $id = $_GET['id'];
    $typeRequest = $_GET['type'];
    $rdfrom = $_GET['rdfrom'];
    if ($rdfrom == 'add') {
        $success = 'Add new user successfully!';
    } elseif ($rdfrom == 'edit') {
        $success = 'Edit user successfully!';
    } elseif ($rdfrom == 'delete') {
        $success = 'User has been deleted successfully!';
    }
    if ($_GET['delete']==true) {
        $isDelete = $_GET['delete'];
    } else {
        $isDelete = false;
    }
    // Xử lí dữ liệu
    // lấy dữ liệu từ user
    $uUsername = $_POST['uUsername'];
    $uEmail = $_POST['uEmail'];
    $uPassword = $_POST['uPassword']; 
    $uConfirmPassword = $_POST['uConfirmPassword']; 
    $admincp = $_POST['admincp'];
    switch ($typeRequest) {
        case 'add':
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
                                break;
                            
                            default: // nếu ko
                                $user = new userRegister($uUsername, $uEmail, $uPassword, 'member', $db);
                                break;
                        }
                        if ($user->status){ // nếu tạo thành công
                            header("Location: $site_addr/admin/users.php?rdfrom=add", true, 303);
                            die('Create new user success!');
                        } else { // ngược lại
                            $error = 'Error when creating new user, please try again later.';
                        }
                    }
                }
            }
            break;
        
        case 'edit':
            if (isset($id)) {
                $checkIfFound = mysqli_fetch_assoc($db->selectValue('users', "id=$id", 'COUNT(id) as count'));
            }
            if ($checkIfFound['count']>0) {
                $getFromDb = mysqli_fetch_assoc($db->selectValue('users', "id=$id", 'username', 'email'));
                $getPermissionFromDb = mysqli_fetch_assoc($db->selectValue('users_permision', "username='".$getFromDb['username']."'", 'admincp'));
            };
            if ((isset($uEmail)||isset($uPassword))&&(!$isDelete)){ // nếu đã nhập 1 trong 3
                $userCheck = new userChecking($uUsername,$uEmail,$uPassword); // tạo obj kiểm tra
                if ($userCheck->checkUsername()&&$userCheck->checkEmail()) { // nếu 3 đk đều đúng
                    if ($uPassword!=''&&$uConfirmPassword=='') {
                        $errPassword = '<b>You must confirm the password!</b>';
                    } elseif ($uPassword!==$uConfirmPassword){
                        $errPassword = '<b>The confirm password do not match!</b>';
                    } elseif ($uPassword==''&&$uConfirmPassword!='') {
                        $errPassword = '<b>You must enter the password!</b>';
                    } else {
                        switch ($admincp) { 
                            case 'on': // nếu có quyền truy cập ad
                                $editUser = new editUser($uUsername, $uEmail, $uPassword, 'admin', $db);// chỉnh sửa user
                                if ($uPassword=='') {
                                    $editUser->editExistingUserWOPassword();
                                } else {
                                    $editUser->editExistingUserWPassword();
                                }
                                break;
                            
                            default: // nếu ko
                                $editUser = new editUser($uUsername, $uEmail, $uPassword, 'member', $db);// chỉnh sửa user
                                if ($uPassword=='') {
                                    $editUser->editExistingUserWOPassword();
                                } else {
                                    $editUser->editExistingUserWPassword();
                                }
                                break;
                        }
                        if ($editUser->status){ // nếu tạo thành công
                            header("Location: $site_addr/admin/users.php?rdfrom=edit", true, 303);
                            die('Create new user success!');
                        } else { // ngược lại
                            $error = 'Error when editing new user, please try again later.';
                        }
                    }
                }
            } elseif ($isDelete) {
                $resultFDb = mysqli_fetch_assoc($db->selectValue('users', "id=$id",'username'));
                $userToDel = $resultFDb['username'];
                $db->deleteFromTable('users', "username='$userToDel'");
                $db->deleteFromTable('users_permision', "username='$userToDel'");
                header('Location: users.php?rdfrom=delete', true, 303);
                die('Delete user successfully');
            }
            break;
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
    } else {
        pagination($pagination, $count, $total, $typeRequest);
        if ($pagination==$count) {
            $user=(($pagination-1)*10+1); // bài đầu tiên là (($pagination-1)*10+1). vd pagination = 2 thì bài đầu tiên là ((2-1)*10+1) == 11
            for ($i=(($pagination-1)*10); $i < $resultC['count']; $i++) {  // vòng lặp in bài
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
                        <td><span><a href='user.php?type=edit&id=$userId' class='btn btn-info'>Edit</a></span><span><button data-id='$userId' class='btn btn-danger delete'>Remove</button></span></td>
                    </tr>";
                $user++;
            }
        } else {
            $user=(($pagination-1)*10+1); // bài đầu tiên là (($pagination-1)*10+1). vd pagination = 2 thì bài đầu tiên là ((2-1)*10+1) == 11
            for ($i=(($pagination-1)*10); $i < ($pagination*10); $i++) {  // vòng lặp in bài
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
                        <td><span><a href='user.php?type=edit&id=$userId' class='btn btn-info'>Edit</a></span><span><button data-id='$userId' class='btn btn-danger delete'>Remove</button></span></td>
                    </tr>";
                $user++;
            }
        }
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
                ".errorTemplate($error)."
                <form method='POST'>
                    <div class='input-group mb-3'>
                        <div class='input-group-prepend'>
                            <label class='input-group-text' for='uUsername'>Username</label>
                        </div>
                        <input type='text' class='form-control' name='uUsername' id='uUsername' value='".$getFromDb['username']."' readonly>
                    </div>
                    $errUsername
                    <div class='input-group mb-3'>
                        <div class='input-group-prepend'>
                            <label class='input-group-text' for='uEmail'>Email</label>
                        </div>
                        <input type='text' class='form-control' name='uEmail' id='uEmail' value='".$getFromDb['email']."'>
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
                    <button class='btn btn-info btn-block'>Edit</button>
                </form>
                <button class='btn btn-danger btn-block delete'>Delete this user</button>
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
                ".successTemplate($success).errorTemplate($error)."
                <a href='users.php?type=add' class='btn btn-info'>Add new user</a>
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
            if (!isset($id)) {
                echo 
                "<main><div class='alert alert-danger' role='alert'>
                    You must declare the user id to continue!
                </div></main>";
            } elseif ($checkIfFound['count']==0) {
                echo 
                "<main><div class='alert alert-danger' role='alert'>
                    Wrong ID! Please try again!
                </div></main>";
            } else {
                $getFromDb['username'];
                if ($getPermissionFromDb['admincp']=='yes'){
                    $js1 = "<script>$('#admincp').attr('checked','checked');</script>";
                };
                $windowLocation = "window.location.assign(`users.php?type=edit&id=$id&delete=true`);";
                $js2 =
                "<script>$('.delete').on('click', function (){
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
                                $windowLocation
                            }
                    })
                });</script>";
                echo $htmlEditUser;
            }
            break;
        
        default:
            $windowLocation = 'window.location.assign(`users.php?type=edit&id=${id}&delete=true`);';
            $js2 =
            "<script>$('.delete').on('click', function (){
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
            });</script>";
            echo $htmlViewUser;
            break;
    }
    require_once('themes/default/footer.php');
    echo $css.$js.$js1.$js2;
?>