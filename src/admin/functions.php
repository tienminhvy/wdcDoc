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
            $this->__createNewUser();
        }

        private function __createCustomID()
        {
            return uniqid("wdcUser_", true);
        }

        private function __createNewHashPW()
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
            if($this->db->insertTable('users', 'wdc_id, username, email, hash_password', $this->userID, $this->username, $this->email, $this->__createNewHashPW())) {
                $this->status = true;
            } else {
                $this->status = false;
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
?>