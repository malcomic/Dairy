<?php
session_start();
session_destroy();
header('Location: /Dairy/views/users/login.php');
exit;
?>