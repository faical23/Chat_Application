<?php
session_start();
include "../model/db.php";



if(isset($_POST["submit"])){

    $email = filter_var( $_POST["Email"], FILTER_SANITIZE_STRING);
    $password =filter_var(  $_POST["Password"] , FILTER_SANITIZE_STRING);
    $user_data=["email" => $email , "password" => $password ];
    echo $email;
    $status = 1;

    $execution = new CRUD("users");
    $execution->select("yes", $user_data);




    if($execution->select("yes", $user_data)){

        $result = $execution->select("", $user_data);
        foreach($result as $value){
           $id = $value["id"];
           $_SESSION['id'] = $id;
        }

        $executionLastMsg = new CRUD("chat");
        $LastMsg = $executionLastMsg->select_last_msg($id);

        foreach($LastMsg as $value){
            $connect = $value["id_message_comming"];
        }
    
        if($connect = ""){
            $execution->update(["status" => 1], $id);
            header('Location:../vue/Chat_Page.php?id='. $id . '&connect=' .  $connect);
        }
        else{
            header('Location:../vue/Chat_Page.php?id='. $id . '&connect=' .  $id);

        }

        $_SESSION["user_login"]= "user_login";
        
    }
    else{
        
         header('Location:../vue/index.php');
    }


}