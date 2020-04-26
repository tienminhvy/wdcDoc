<?php 
    if(!defined('isSet')){
        die('<h1>Direct access is not allowed!</h1>');
    }
    require('db_connect.php');
    require('settings.php');
?>
<?php 
    // User 
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
})
function redirect(){
    window.location.assign('.');
}
setTimeout(redirect,3000);
";
            // Thông báo khi admin đăng nhập thành công
            $this->notifyIfTrue_Admin = "Swal.fire({
    title: 'Success!',
    text: 'You have logged in as Administrator successfully',
    icon: 'success',
})
function redirect(){
    window.location.assign('admin');
}
setTimeout(redirect,3000);
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
        // Kiểm tra điều kiện đăng nhập
        public function checkLogin()
        {   
            if ($this->usernameCheck($this->username)&&$this->passwordCheck($this->username, $this->password)) { // Nếu hai hàm trên đúng
                $data = $this->db->selectValue('users', "username = '$this->username'",'userrole'); // lấy g.trị vai trò người dùng
                $result = mysqli_fetch_assoc($data);
                $this->isLogged = true; // trả về đăng nhập thành công
                if ($this->isLogged) {
                    if ($this->remember == 'on') { // Lấy giá trị ghi nhớ tôi, nếu là on
                        $_SESSION['logged'] = true; // set session và cookie
                        setcookie('logged', true, time()+(86400*30),"/");
                        setcookie('username', $this->username, time()+(86400*30),"/");
                    } else { 
                        $_SESSION['username'] = $this->username;
                        $_SESSION['logged'] = true;
                    }
                }
                if ($result['userrole'] == "Administrator") { // nếu vai trò là admin
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
    // tạo kết nối db mới
    $db = new dataBase($db_server,$db_name,$db_username,$db_password);
?>