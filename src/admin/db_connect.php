<?php 
    if(!defined('isSet')){
        die('<h1>Direct access is not allowed!</h1>');
    }
    require('settings.php');
    class dataBase // tạo class
    {
        public function __construct($db_server, $db_name,$db_username, $db_password, $db_port = 3306) {
            // hàm khởi tạo ban đầu
            $this->db_server = $db_server;
            $this->db_name = $db_name;
            $this->db_username = $db_username;
            $this->db_password = $db_password;
            $this->db_port = $db_port;
            // Kết nối tới db
            $this->conn = @mysqli_connect($this->db_server, $this->db_username, $this->db_password, $this->db_name, $this->db_port);
        }

        public function checkDbConnection() // kiểm tra kết nối
        {
            if($this->conn) {return true;} else {return false;}
        }

        public function createTable($table) // tạo bảng
        {

            $createTable = @mysqli_query($this->conn, $table);
            if (!$createTable) {
                echo 'Error: '.$createTable.'<br>'.mysqli_error($this->conn);
            }
        }

        public function insertTable($tableName, $columns, ...$values)
        {
            for ($i=0; $i < count($values); $i++) { 
                if ($i == count($values)-1) {
                    switch (is_numeric($values[$i])) {
                        case true:
                            $valToIns .= $values[$i];
                            break;
                        
                        default:
                            $valToIns .= "'$values[$i]'";
                            break;
                    }
                } else {
                    switch (is_numeric($values[$i])) {
                        case true:
                            $valToIns .= $values[$i].', ';
                            break;
                        
                        default:
                            $valToIns .= "'$values[$i]', ";
                            break;
                    }
                }

            }
            $ins = @mysqli_query($this->conn, 
            "INSERT INTO $tableName ($columns)
            VALUES ($valToIns)");
            if (!$ins){
                echo 'Error: '.$ins.'<br>'.mysqli_error($this->conn);
            }
        }

        public function selectCol($table,...$columns) {
            for ($i=0; $i < count($columns); $i++) { 
                if ($i == count($columns)-1) {
                    $colToVal .= ' '.$columns[$i];
                }
                $colToVal .= ' '.$columns[$i].', ';
            }
            return mysqli_query($this->conn, "SELECT $colToVal FROM $table");
        }

        public function selectValue($table, $condition,...$columns) {
            for ($i=0; $i < count($columns); $i++) { 
                if ($i == count($columns)-1) {
                    $colToVal .= ' '.$columns[$i];
                } else {$colToVal .= ' '.$columns[$i].', ';}
            }
            return mysqli_query($this->conn, "SELECT $colToVal FROM $table WHERE $condition");
        }

        public function __destruct()
        {
            @mysqli_close($this->conn);
        }
    }
    $db = new dataBase($db_server, $db_username, $db_password, $db_port);
?>