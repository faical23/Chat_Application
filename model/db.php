<?php
include "../model/connect.php";

    class CRUD extends DB{

    private $table;

    public function __construct($table){
        parent::__construct("systemechat");
        $this->table = $table;
    }


      public function insert($element = []){
        $columns = "";
        $values_column = "";


        $elment_length =  count($element) -1 ;
        $i=0;
        foreach ($element as $key => $value) {
                if($i < $elment_length)
                {
                    $columns .="$key,";
                    $values_column .= "'$value',";
                }
                else{
                    $columns .="$key";
                    $values_column .= "'$value'";
                }
                $i++;
        }
        $sql = "INSERT INTO $this->table ($columns) VALUES ($values_column)"; 
        $stmt = $this->dbh->prepare($sql);
        $stmt = $this->dbh->exec($sql); ;
        return $stmt;
    }

    public function select($check = "",$conditions = []){
        $i = 0;
        $sql = "SELECT * FROM $this->table ";
        foreach ($conditions as $key => $value) {
            if($i == 0)
            {
                $sql .= "WHERE $key = '$value'";
            }
            else{
                $sql .= " AND $key = '$value'";
            }
            $i++;

        }
        $stmt = $this->dbh->prepare($sql);

        try{
            if($check == "yes")
            {
                $stmt = $this->dbh->query($sql); 
                $count = $stmt->rowCount();
                return $count;
            }
            else{
                $stmt->execute(); 
                $result = $stmt->fetchAll();
                return $result;
            }
        }
        catch(Exception $e) {
            return $e->getMessage();
        }


    }

    public function delete($condition){

        $sql = "DELETE from $this->table WHERE id = $condition ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function update($element = [],$condition){
            $i = 0;
            $sql = "UPDATE $this->table SET ";
            $elment_length =  count($element) -1 ;
            foreach ($element as $key => $value) {
                if($i == 0)
                {
                    $sql .= "$key = '$value'";
                }
                else if($i > 0){
                    $sql .= " , $key = '$value'";
                }
                $i++;
            }
            
            $sql.= " WHERE id = $condition";
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            return $stmt;
    } 

    public function unique_requet($User_sent_message,$User_recu_message){
        $sql="SELECT * FROM `chat` WHERE id_message_comming = $User_recu_message AND id_message_going = $User_sent_message 
        OR id_message_going  = $User_recu_message AND  id_message_comming = $User_sent_message ";
        try{

                $stmt = $this->dbh->prepare($sql);
                $stmt->execute(); 
                $result = $stmt->fetchAll();
                return $result;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }

    }
    public function select_last_msg($id){
        $sql="SELECT * FROM `chat` WHERE id_message_going = $id or id_message_comming = $id ORDER by id DESC limit 1";
        $stmt = $this->dbh->prepare($sql);
        try{
                $stmt->execute(); 
                $result = $stmt->fetchAll();
                return $result;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }


    public function select_msg_u_send(){
        $sql= "SELECT DISTINCT id_message_going  FROM $this->table";
        $stmt = $this->dbh->prepare($sql);
        try{
                $stmt->execute(); 
                $result = $stmt->fetchAll();
                return $result;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
    public function select_msg_u_recu(){
        $sql= "SELECT DISTINCT id_message_comming  FROM $this->table ";
            $stmt = $this->dbh->prepare($sql);
        try{
                $stmt->execute(); 
                $result = $stmt->fetchAll();
                return $result;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
    public function select_last_Discussions($id ,$was_connect_with){
        $sql="SELECT * FROM `chat` WHERE id_message_going = $id AND id_message_comming = $was_connect_with OR 
        id_message_comming = $id and id_message_going =$was_connect_with ORDER by id DESC limit 1";
        $stmt = $this->dbh->prepare($sql);
        try{
                $stmt->execute(); 
                $result = $stmt->fetchAll();
                return $result;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
    public function update_read_msg($was_connect_with,$user){
        $sql="UPDATE $this->table SET read_msg = 1 WHERE id_message_comming = $was_connect_with AND id_message_going = $user";
        try{
                 $stmt = $this->dbh->prepare($sql);
                $stmt->execute(); 
                return $stmt;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
    



}


