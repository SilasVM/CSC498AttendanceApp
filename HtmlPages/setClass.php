<?php
session_start();

if(isset($_POST['chosenClass'])){
    $_SESSION['chosenClass'] = $_POST['chosenClass'];
    header("Location: rosterPage.php");
    exit();
}else{
    // Handle missing data
    echo "No class was selected.";
}
?>
