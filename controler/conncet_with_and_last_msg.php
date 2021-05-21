<?php

$UserId = $_GET['user'];
$arr =[];
$all_message = "";
$user_enline = new CRUD("chat");
$result = $user_enline->select_msg_u_send();
foreach($result as $value){
    $was_connect_with = $value["id_message_going"];
    if(!($was_connect_with == $UserId)){
        array_push($arr,$was_connect_with);
    }
}
$result = $user_enline->select_msg_u_recu();
foreach($result as $value){
    $was_connect_with = $value["id_message_comming"];
    if(!(in_array($was_connect_with,$arr))){
        array_push($arr,$was_connect_with);
    }

}
for($i = 0 ; $i <count($arr) ; $i++){
    //// select name and pic
     $user_enline = new CRUD("users");
    $result = $user_enline->select("", ["id" => $arr[$i]]);
    foreach($result as $value){
        $pic_connect_with = $value["user_pic"];
        $name_connect_with = $value["name"];
    }
    /////////// select last msg
     $result = $user_enline->select_last_Discussions($UserId ,$arr[$i]);
    foreach($result as $value){
        $last_message = $value["message"];
        if($arr[$i]== $value["id_message_comming"]){
            $all_message .= '
                            <div class="user_message">
                                    <div class="img_user">
                                        <img src="'. $pic_connect_with.'">
                                    </div>
                                    <div class="name_user">
                                        <h5>'. $name_connect_with.'</h5>
                                        <p> You :'.$last_message.'</p>
                                    </div>
                                    <div class="status_users">
                                        <i class="fa fa-circle"></i>
                                    </div>
                            </div>';
        }
        else{
            $all_message .= '
                            <div class="user_message">
                                    <div class="img_user">
                                        <img src="'.$pic_connect_with.'">
                                    </div>
                                    <div class="name_user">
                                        <h5>'. $name_connect_with.'</h5>
                                        <p> He :'.$last_message.'</p>
                                    </div>
                                    <div class="status_users">
                                        <i class="fa fa-circle"></i>
                                    </div>
                            </div>';

        }
    }
}

echo $all_message;