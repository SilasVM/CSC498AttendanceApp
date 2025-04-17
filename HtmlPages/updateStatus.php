<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['studentId'];
    $classId = $_SESSION['chosenClass'];
    $status = $_POST['status'];
    $date = $_POST['date'];

    if ($studentId && $status && $date && $classId) {
        error_log("Received status: " . $status);
        $stmt = $con->prepare("UPDATE attendance SET Status = ? WHERE StudentID = ? AND AttendanceDate = ? AND ClassID = ?");
        $stmt->bind_param("ssss", $status, $studentId, $date, $classId);
        if(!$stmt->execute()){
            echo "error";
            exit;
        }else{
            echo "success";
        }
        $stmt->close();
    }else{
        echo "missing_fields";
    }
    $con->close();
    }
?>
