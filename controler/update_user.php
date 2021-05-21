<?php

include '../model/db.php';
$id = $_POST['id'];
$UserName = $_POST['UserName'];
$NewImg = $_POST['NewImg'];
$LastImg = $_POST['LastImg'];

$image = "";


if($NewImg == '')
{
    $image = $LastImg;
}
else{
    $image = $NewImg ;
}

$execution = new CRUD("users");
$execution->update(["name" => $UserName , "user_pic" => $image], $id);
echo $image;