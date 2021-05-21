<?php

include "../model/db.php";

$User = $_GET['user'];
$ConncetWith = $_GET['id'];
$ZoneMessage = "";




$execution = new CRUD("chat");
$result = $execution->unique_requet($User ,$ConncetWith);
$x = 0;

foreach($result as $value){

    if($value["id_message_going"] == $User )
    {
        $ZoneMessage .='  
                    <div class="message_recu">
                        <p>'. $value["message"].'</p>
                        <h6>'. $value["date"].'</h6>
                    </div>  
                ';
                $x++;
    }
    else if ($value["id_message_comming"] == $User ){
        $ZoneMessage .= '
                    <div class="message_sent">
                        <div class="message_sent_centent">
                                <p>'. $value["message"].'</p>
                            </div>
                            <div class="message_sent_time">
                                <img src="check.png">
                                <h6>'. $value["date"].'</h6>
                        </div>

                    </div>
        ';
        $x++;
    }
}
if($x == 0){
    $ZoneMessage = '<p class="not_message_her"> No message her</p>';
}
echo $ZoneMessage;