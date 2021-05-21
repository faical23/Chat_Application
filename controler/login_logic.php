<?php
session_start();
include "../model/db.php";


if(isset($_POST["submit"])){

    $email = $_POST["Email"];
    $password = $_POST["Password"];
    $user_data=["email" => $email , "password" => $password ];
    echo $email;
    $status = 1;

    $execution = new CRUD("users");
    $execution->select("yes", $user_data);




    if($execution->select("yes", $user_data)){

        $result = $execution->select("", $user_data);
        foreach($result as $value){
           $id = $value["id"];
        }

        $executionLastMsg = new CRUD("chat");
        $LastMsg = $executionLastMsg->select_last_msg($id);

        foreach($LastMsg as $value){
            $connect = $value["id_message_comming"];
        }
    
        $execution->update(["status" => 1], $id);
        header('Location:../vue/Chat_Page.php?id='. $id . '&connect=' .  $connect);
        $_SESSION["user_login"]= "user_login";
        
    }
    else{
        
         header('Location:../vue/index.php');
    }


}