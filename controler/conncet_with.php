<?php

include "../model/db.php";

$ConncetWith = $_GET['id'];
$ZoneConnectWith = "";

$user = new CRUD("users");

$result = $user->select("",["id" => $ConncetWith]);
foreach($result as $value){
    $user_Connncet_name = $value["name"];
    $user_Conncet_pic = $value["user_pic"];                    
}

$ZoneConnectWith = '
                            <img src="'.$user_Conncet_pic.'">
                            <h1>'.$user_Connncet_name.'</h1>
                    ';
echo $ZoneConnectWith ;

