<?php
session_start();

include '../model/db.php';

if(isset($_GET["name"])){

    $output = "";
    $user_search = $_GET["name"];
    $id = $_GET["id"];
    $user_enline = new CRUD("users");
    $result = $user_enline->select("",["name" => $user_search]);

    if($user_search != ""){
        if(count($result)> 0){
            foreach($result as $value){
                $output .=
            '<div class="user_message"  onclick="select('.$value["id"] .');  select_message('.$id.','.$value["id"] .')">
                <img src="'.$value["user_pic"] .'">
                <h5>'.$value["name"] .'</h5>
                <i class="fa fa-circle" style="color:green"></i>

            </div>';

            }
        }
        else{
            $output = '<p class="name_not_found">Name not found</p>';
        }
    
    }
    else{
        $result = $user_enline->select();
        foreach($result as $value){
            $output .=
            '<div class="user_message"  onclick="select('.$value["id"] .'); select_message('.$id.' ,'.$value["id"] .')"">

                <img src="'.$value["user_pic"] .'">
                <h5>'.$value["name"] .'</h5>
                <i class="fa fa-circle" style="color:green"></i>
            </div>';
        }
    }
    echo $output;
}