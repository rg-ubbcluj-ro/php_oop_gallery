<?php

class User {
    public $id;
    public $username;
    public $first_name;
    public $last_name;
    public $password;

    public static function instantiation($the_record){
        
        $the_object = new self;

        foreach ( $the_record as $the_attribute =>$value){
            if ($the_object->has_the_attribute($the_attribute)){
                
                $the_object->$the_attribute=$value;
            }
        }
    return $the_object;
    }


    private function has_the_attribute ($the_attribute){
        $object_properties=get_object_vars($this);

        return array_key_exists($the_attribute,$object_properties);
    }
    

    public static function find_all_users(){
        global $database;

        return self::find_this_query("SELECT * FROM users");

    }

    public static function find_user_by_id($id){
        global $database;
    
        $the_result_array= self::find_this_query("SELECT * FROM users WHERE id=$id LIMIT 1");
       //if the array is empty, return false
        return !empty($the_result_array) ? array_shift($the_result_array) : false;

        } 

    public static function find_this_query($sql){
        global $database;
        $result_set=$database->query($sql);
        $the_object_array = array();

        while($row = mysqli_fetch_array($result_set)){
            $the_object_array[] = self::instantiation($row);    
        }
        return $the_object_array;
    }


    public static function verify_user($username, $password){
        global $database;

        $username=$database->escape_string($username);
        $password=$database->escape_string($password);
   
        $sql="SELECT * from users WHERE ";
        $sql.="username = '{$username}' ";
        $sql.="AND password = '{$password}' ";
        $sql.="LIMIT 1";
        
        $the_result_array= self::find_this_query($sql);
        //if the array is empty, return false
         return !empty($the_result_array) ? array_shift($the_result_array) : false;
        
    }

    public static function verify_username($username){
        global $database;

        $username=$database->escape_string($username);
   
        $sql="SELECT * from users WHERE ";
        $sql.="username = '{$username}' ";
        $sql.="LIMIT 1";
        
        $the_result_array= self::find_this_query($sql);
        //if the array is empty, return false
         return !empty($the_result_array) ? array_shift($the_result_array) : false;
    }

public function create(){
    global $database;

    $sql="INSERT INTO users (username,password,first_name,last_name)";
    $sql.="VALUES('";
    $sql.=$database->escape_string($this->username) ."','";
    $sql.=$database->escape_string($this->password) ."','";
    $sql.=$database->escape_string($this->first_name) ."','";
    $sql.=$database->escape_string($this->last_name) ."')";

    
    if ($database->query($sql)){
        //getting the id of the last record and assigning it to the object;
        $this ->id=$database->the_insert_id();
        return true;

    }else {

        return false;

    }

}//create method

public function update(){
    global $database;

    $sql="UPDATE users SET ";
    $sql.="username= '".$database->escape_string($this->username) . "',";
    $sql.="password= '".$database->escape_string($this->password) . "',";
    $sql.="first_name= '".$database->escape_string($this->first_name) . "',";
    $sql.="last_name= '".$database->escape_string($this->last_name) . "' ";
    $sql.=" WHERE id=".$database->escape_string($this->id);

    $database->query($sql);

    return (mysqli_affected_rows($database->connection)==1)? true:false;
}

public function delete(){
    global $database;

    $sql="DELETE FROM users WHERE id=".$database->escape_string($this->id);
    $sql.=" LIMIT 1";

    $database->query($sql);

    return (mysqli_affected_rows($database->connection)==1)? true:false;

}














}//End of the user class