<?php
Session_start();
Session_destroy();

header('Location: ../vue/index.php');

?>