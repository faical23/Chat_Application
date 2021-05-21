<?php

include "../model/db.php";


$UserSent = $_POST["User_sent"];
$User_recu = $_POST["User_recu"];
$Msg = $_POST["message"];

$date = date("h:i");


if(isset($_POST["submit"])){

    if(!empty($_POST["message"]))
    {

        $data =["id_message_comming" => $UserSent ,"id_message_going" =>  $User_recu ,"message" => $Msg , "read_msg" => 0 ,"date" => $date ];
        
        $execution = new Crud("chat");
        $execution->insert($data);
        echo $Msg;
        
        // $data =["id_message_comming" => $UserSent ,"id_message_going" =>  $User_recu ,"message" => $Msg , "read_msg" => 0 ,"date_msg" => $date];
        
        // $execution = new Crud("chats");
        // $execution->insert($data);
    }
    else{
        echo "nothing";
    }

}
