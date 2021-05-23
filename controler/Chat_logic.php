<?php

include "../model/db.php";


$UserSent = filter_var($_POST["User_sent"], FILTER_SANITIZE_STRING);
$User_recu = filter_var($_POST["User_recu"] , FILTER_SANITIZE_STRING);
$Msg = filter_var($_POST["message"] , FILTER_SANITIZE_STRING);

$date = date("h:i");


if(isset($_POST["submit"])){

    if(!empty($_POST["message"]))
    {

        $data =["id_message_comming" => $UserSent ,"id_message_going" =>  $User_recu ,"message" => $Msg , "read_msg" => 0 ,"date" => $date ];
        
        $execution = new Crud("chat");
        $execution->insert($data);
        echo $Msg;
        
    }
    else{
        echo "nothing";
    }

}
