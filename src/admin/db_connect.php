<?php 
    if(!defined('isSet')){
        die('<h1>Direct access is not allowed!</h1>');
    }
    require('settings.php');
    class dataBase 
    {
        public function __construct($db_server, $db_name,$db_username, $db_password, $db_port = 3306) {
            $this->db_server = $db_server;
            $this->db_name = $db_name;
            $this->db_username = $db_username;
            $this->db_password = $db_password;
            $this->db_port = $db_port;
            $this->conn = @mysqli_connect($this->db_server, $this->db_username, $this->db_password, $this->db_name, $this->db_port);
        }

        public function checkDbConnection()
        {
            if($this->conn) {return true;} else {return false;}
        }

        public function createTable($table)
        {

            $createTable = @mysqli_query($this->conn, 
            $table);
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

        public function __destruct()
        {
            @mysqli_close($this->conn);
        }
    }
    $db = new dataBase($db_server, $db_username, $db_password, $db_port);
?>