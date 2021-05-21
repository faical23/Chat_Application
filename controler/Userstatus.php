<?php
session_start();
include '../model/db.php';

$status = $_GET["status"];
$UserId= $_GET["id"];


$execution = new CRUD('users');
if($status == 'available'){
    $execution->update(['status' => 1],$UserId);
    $_SESSION['available'] = 'available';
    unset($_SESSION['Not available']);

}
else if($status == 'Not available'){
    $execution->update(['status' => 0],$UserId);
    $_SESSION['Not available'] = 'Not available';
    unset($_SESSION['available']);
}
