<?php

include "../model/db.php";



if(isset($_POST["submit"])){

    $name =  filter_var(  $_POST["Name"] , FILTER_SANITIZE_STRING);
    $email = filter_var(  $_POST["Email"] , FILTER_SANITIZE_STRING);
    $password = filter_var( $_POST["Password"] , FILTER_SANITIZE_STRING);
    $img = $_POST["img"];
    $status = 0;
    $userImg = $img;

    $user_data=["name" => $name , "email" => $email , "password" => $password , "status" => $status , "user_pic" => $userImg];

    $execution = new CRUD("users");
    $execution->insert($user_data);

    header('Location:../vue/index.php');
}