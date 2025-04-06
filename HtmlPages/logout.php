<?php 
session_start();
session_destroy();
header("Location: professorLogin.php");
exit();
?>