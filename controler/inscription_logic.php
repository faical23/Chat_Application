<?php

include "../model/db.php";



if(isset($_POST["submit"])){

    $name = $_POST["Name"];
    $email = $_POST["Email"];
    $password = $_POST["Password"];
    $img = $_POST["img"];
    $status = 0;
    $userImg = "../assets/image/" . $img;

    $user_data=["name" => $name , "email" => $email , "password" => $password , "status" => $status , "user_pic" => $userImg];

    $execution = new CRUD("users");
    $execution->insert($user_data);

    header('Location:../vue/index.php');
}