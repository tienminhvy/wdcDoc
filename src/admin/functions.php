<?php 
    if(!defined('isSet')){
        die('<h1>Direct access is not allowed!</h1>');
    }
?>
<?php 
    // User 
    class userRegister
    {
        public function __construct($username, $email, $password, $role, $db) {
            $this->username = $username;
            $this->email = $email;
            $this->password = htmlspecialchars($password);
            $this->db = $db;
            $this->role = $role;
            $this->status = false;
            $this->__createNewUser();
        }

        private function __createCustomID()
        {
            return uniqid("wdcUser_", true);
        }

        protected function _createNewHashPW()
        {
            return password_hash($this->password, PASSWORD_BCRYPT);
        }

        private function __createNewUser(){
            $this->userID = $this->__createCustomID();
            switch ($this->role) {
                case 'admin':
                    $this->db->insertTable('users_permision', 'wdc_id, username, admincp', $this->userID, $this->username, 'yes');
                    break;
                
                default:
                    $this->db->insertTable('users_permision', 'wdc_id, username, admincp', $this->userID, $this->username, 'no');
                    break;
            }
            if($this->db->insertTable('users', 'wdc_id, username, email, hash_password', $this->userID, $this->username, $this->email, $this->_createNewHashPW())) {
                $this->status = true;
            } else {
                $this->status = false;
            }
        }
    }

    class editUser
    {
        
        public function __construct($username, $email, $password, $role, $db)
        {
            $this->username = $username;
            $this->email = $email;
            $this->password = htmlspecialchars($password);
            $this->db = $db;
            $this->role = $role;
        }

        protected function _createNewHashPW()
        {
            return password_hash($this->password, PASSWORD_BCRYPT);
        }

        public function editExistingUserWOPassword()
        {
            $this->_createNewHashPW();
            switch ($this->role) {
                case 'admin':
                    $this->db->editValue('users_permision', "username='".$this->username."'", 'admincp', "'yes'");
                    break;
                
                default:
                    $this->db->editValue('users_permision', "username='".$this->username."'", 'admincp', "'no'");
                    break;
            }
            if ($this->db->editValue('users', "username='".$this->username."'", 'email', "'".$this->email."'")==true) {
                $this->status = true;
            } else {
                $this->status = false;
            }
        }

        public function editExistingUserWPassword()
        {
            if ($this->password!='') {
                $this->_createNewHashPW();
                switch ($this->role) {
                    case 'admin':
                        $this->db->editValue('users_permision', "username='$this->username'", 'admincp', "'yes'");
                        break;
                    
                    default:
                        $this->db->editValue('users_permision', "username='$this->username'", 'admincp', "'no'");
                        break;
                }
                $this->db->editValue('users', "username='".$this->username."'", 'email', $this->email);
                if ($this->db->editValue('users', "username='".$this->username."'", 'hash_password', "'".$this->_createNewHashPW()."'")) {
                    $this->status = true;
                } else {
                    $this->status = false;
                }
            }
        }
        
        
    }

    class loginCheck
    { 

        public function __construct($username, $password, $remember, $db) // Khởi tạo
        { // Lưu trữ giá trị mặc định
            $this->isLogged = false;
            $this->remember = $remember;
            $this->username = $username;
            $this->password = $password;
            $this->db = $db;
            // Thông báo khi đăng nhập thất bại
            $this->notifyIfFalse = "Swal.fire({
    title: 'Error!',
    text: 'Incorrect email or password!',
    icon: 'error',
})";
            // Thông báo khi đăng nhập thành công
            $this->notifyIfTrue = "Swal.fire({
    title: 'Success!',
    text: 'You have logged in successfully',
    icon: 'success',
    showCloseButton: false,
})
function redirect(){
    window.location.assign('.');
}
Swal.disableButtons();
setTimeout(redirect,2000);
";
            // Thông báo khi admin đăng nhập thành công
            $this->notifyIfTrue_Admin = "Swal.fire({
    title: 'Success!',
    text: 'You have logged in as Administrator successfully',
    icon: 'success',
    showCloseButton: false,
})
function redirect(){
    window.location.assign('admin');
}
Swal.disableButtons();
setTimeout(redirect,2000);
";
        }
        // Hàm kiểm tra tên đăng nhập
        private function usernameCheck($username)
        {
            $data = $this->db->selectValue('users', "username = '$username'",'username'); // Lấy dữ liệu từ  database
            $result = mysqli_fetch_assoc($data); // Chuyển kq lấy từ db sang arr
            if ($result['username'] == '') { // Nếu arr result có index username == '' thì trả về false
                return false;
            } else { // ngược lại thì true
                return true; 
            }
        }
        // Hàm kiểm tra mật khẩu
        private function passwordCheck($username, $password)
        { // tương tự như trên
            $data = $this->db->selectValue('users', "username = '$username'",'hash_password');
            $result = mysqli_fetch_assoc($data);
            if ($result['hash_password']=='') {
                return false;
            } elseif (!password_verify($password,$result['hash_password'])) { // hàm kiểm tra hash
                return false;
            } else {
                return true;
            }
        }

        private function __createToken()
        {
            return uniqid('wdcToken_', true);
        }

        public function getToken()
        {
            return $this->__createToken();
        }

        // Kiểm tra điều kiện đăng nhập
        public function checkLogin()
        {   
            if ($this->usernameCheck($this->username)&&$this->passwordCheck($this->username, $this->password)) { // Nếu hai hàm trên đúng
                $dataFromDb = $this->db->selectValue('users_permision', "username = '$this->username'",'admincp'); // lấy g.trị vai trò người dùng
                $permisionResult = mysqli_fetch_assoc($dataFromDb);
                $token = $this->__createToken();
                $dataFUDb = $this->db->selectValue('users', "username = '$this->username'", 'wdc_id');
                $dataFTDb = $this->db->selectValue('users_token', "username = '$this->username'", 'COUNT(id)');
                $valueFUDb = mysqli_fetch_assoc($dataFUDb);
                $valueFTDb = mysqli_fetch_assoc($dataFTDb);
                $wdc_id = $valueFUDb['wdc_id'];
                if ($valueFTDb['COUNT(id)']>0) {
                    $this->db->editValue('users_token', "username = '$this->username'", 'token', "'$token'");
                } else {
                    $this->db->insertTable('users_token', 'wdc_id, username, token', $wdc_id, $this->username, $token);
                }
                
                if ($this->remember == 'on') { // Lấy giá trị ghi nhớ tôi, nếu là on
                    // set cookie
                    setcookie('wdcToken', $token, time()+(86400*30),"/");
                    setcookie('wdc_id', $wdc_id, time()+(86400*30), '/');
                } else { // set session
                    $_SESSION['wdc_id'] = $wdc_id;
                    $_SESSION['wdcToken'] = $token;
                }
                if ($permisionResult['admincp'] == 'yes') { // nếu vai trò là admin
                    return $this->notifyIfTrue_Admin;
                } else {
                    return $this->notifyIfTrue;
                }
            } else { // Nếu không
                $this->isLogged = false;
                return $this->notifyIfFalse;
            }
        }
    }
    function pagination($pagination, $count, $total, $typeRequest) {
        if ($pagination>$count) { // nếu pagination > count
            $GLOBALS['errorCheck'] = true;
            $GLOBALS['error'] = "Exceeded maximum count for $typeRequest, please try again!";
        }
        if ($total < 10) { // nếu số lượng bài dưới 10
            if ($pagination>1) { // nếu ko set pagination trên url
                $pagination = 1; // mặc định là 1
            }
        } elseif ($pagination==1 || $pagination=='') { // nếu đang ở trang view
            if ($count<4) { // kiểm tra nếu count < 4
                for ($i=1; $i <= $count; $i++) { 
                    $item .=
                    "<li class='page-item' id='paginate-$i'><a class='page-link' href='view.php?type=$typeRequest&pagination=$i'>$i</a></li>";
                }
            } else {
                for ($i=1; $i < 4; $i++) { 
                    $item .=
                    "<li class='page-item' id='paginate-$i'><a class='page-link' href='view.php?type=$typeRequest&pagination=$i'>$i</a></li>";
                }
                // trang cuối cùng
                $item .= 
                "<li class='page-item'>
                <a class='page-link' href='view.php?type=$typeRequest&pagination=$count' aria-label='Next'>
                <span aria-hidden='true'>&raquo;</span>
                <span class='sr-only'>Next</span>
                </a>
                </li>";
            }
            // lưu
            $GLOBALS['htmlPagination'] = 
            "<nav aria-label='Page navigation'>
            <ul class='pagination'>
            $item
            </ul>
            </nav>";
        } elseif ($pagination>1&&$pagination<4) { // nếu đang ở pagination là 2 và 3
            if ($pagination==$count) { // nếu số pagination trên url == count
                for ($i=1; $i <= $pagination; $i++) { // in từ 1 -> pagination
                    $item .=
                    "<li class='page-item' id='paginate-$i'><a class='page-link' href='view.php?type=$typeRequest&pagination=$i'>$i</a></li>";
                }
            } elseif (($pagination+1)==$count||($pagination+2)==$count) {
                for ($i=1; $i <= $count; $i++) {  // in từ 1 đến count
                    $item .=
                    "<li class='page-item' id='paginate-$i'><a class='page-link' href='view.php?type=$typeRequest&pagination=$i'>$i</a></li>";
                }
            } else {
                for ($i=1; $i <= $pagination; $i++) {  // in từ 1 đến pagination
                    $item .=
                    "<li class='page-item' id='paginate-$i'><a class='page-link' href='view.php?type=$typeRequest&pagination=$i'>$i</a></li>";
                }
                // thêm vào 2 trang kế
                $item .=
                "<li class='page-item' id='paginate-".($pagination+1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+1)."'>".($pagination+1)."</a></li>
                <li class='page-item' id='paginate-".($pagination+2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+2)."'>".($pagination+2)."</a></li>";
                // thêm vào trang cuối cùng
                $item .= 
                "<li class='page-item'>
                <a class='page-link' href='view.php?type=$typeRequest&pagination=$count' aria-label='Next'>
                <span aria-hidden='true'>&raquo;</span>
                <span class='sr-only'>Next</span>
                </a>
                </li>";
            }
            // html template
            $GLOBALS['htmlPagination'] = 
            "<nav aria-label='Page navigation'>
            <ul class='pagination'>
            $item
            </ul>
            </nav>";
        } else { // còn lại
            // trang đầu tiên
            $item = 
            "<li class='page-item'>
            <a class='page-link' href='view.php?type=$typeRequest&pagination=1' aria-label='Previous'>
            <span aria-hidden='true'>&laquo;</span>
            <span class='sr-only'>Previous</span>
            </a>
            </li>";
            if ($pagination==$count) { // nếu pagination == count hay đgl ở trang cuối cùng
                $item .=
                "<li class='page-item' id='paginate-".($pagination-4)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-4)."'>".($pagination-4)."</a></li>
                <li class='page-item' id='paginate-".($pagination-3)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-3)."'>".($pagination-3)."</a></li>
                <li class='page-item' id='paginate-".($pagination-2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-2)."'>".($pagination-2)."</a></li>
                <li class='page-item' id='paginate-".($pagination-1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-1)."'>".($pagination-1)."</a></li>
                <li class='page-item' id='paginate-".($pagination)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination)."'>".($pagination)."</a></li>
                ";
            } elseif (($pagination+1)==$count) { // nếu ở trang kế cuối
                $item .= 
                "<li class='page-item' id='paginate-".($pagination-3)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-3)."'>".($pagination-3)."</a></li>
                <li class='page-item' id='paginate-".($pagination-2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-2)."'>".($pagination-2)."</a></li>
                <li class='page-item' id='paginate-".($pagination-1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-1)."'>".($pagination-1)."</a></li>
                <li class='page-item' id='paginate-$pagination'><a class='page-link' href='view.php?type=$typeRequest&pagination=$pagination'>$pagination</a></li>
                <li class='page-item' id='paginate-".($pagination+1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+1)."'>".($pagination+1)."</a></li>";
            } elseif (($pagination+2)==$count) { // nếu ở gần trang kế cuối
                $item .= 
                "<li class='page-item' id='paginate-".($pagination-2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-2)."'>".($pagination-2)."</a></li>
                <li class='page-item' id='paginate-".($pagination-1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-1)."'>".($pagination-1)."</a></li>
                <li class='page-item' id='paginate-$pagination'><a class='page-link' href='view.php?type=$typeRequest&pagination=$pagination'>$pagination</a></li>
                <li class='page-item' id='paginate-".($pagination+1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+1)."'>".($pagination+1)."</a></li>
                <li class='page-item' id='paginate-".($pagination+2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+2)."'>".($pagination+2)."</a></li>";
            } else { // còn lại
                $item .= 
                "<li class='page-item' id='paginate-".($pagination-2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-2)."'>".($pagination-2)."</a></li>
                <li class='page-item' id='paginate-".($pagination-1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-1)."'>".($pagination-1)."</a></li>
                <li class='page-item' id='paginate-$pagination'><a class='page-link' href='view.php?type=$typeRequest&pagination=$pagination'>$pagination</a></li>
                <li class='page-item' id='paginate-".($pagination+1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+1)."'>".($pagination+1)."</a></li>
                <li class='page-item' id='paginate-".($pagination+2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+2)."'>".($pagination+2)."</a></li>";
                $item .=
                "<li class='page-item'>
                <a class='page-link' href='view.php?$total&pagination=$count' aria-label='Next'>
                <span aria-hidden='true'>&raquo;</span>
                <span class='sr-only'>Next</span>
                </a>
                </li>";
            }
            $GLOBALS['htmlPagination'] = 
            "<nav aria-label='Page navigation'>
            <ul class='pagination'>
            $item
            </ul>
            </nav>";
        }
    }
?>