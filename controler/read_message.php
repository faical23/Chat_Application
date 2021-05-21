<?php

include '../model/db.php';

$user = $_GET['user'];
$ConncetWith = $_GET['id'];

$execution = new Crud("chat");
$result = $execution->update_read_msg($ConncetWith , $user);

echo $ConncetWith;
