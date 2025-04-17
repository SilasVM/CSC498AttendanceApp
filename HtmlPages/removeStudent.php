<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['studentId'];
    $classId = $_SESSION['chosenClass'];

    $stmt = $con->prepare("DELETE FROM enrollment WHERE StudentID = ? AND ClassID = ?");
    $stmt->bind_param("ss", $studentId, $classId);
    if (!$stmt->execute()) {
        echo "error";
        exit;
    }
    $stmt->close();

    $stmt = $con->prepare(query: "DELETE FROM attendance WHERE StudentID = ? AND ClassID = ?");
    $stmt->bind_param("ss", $studentId, $classId);
    $stmt->execute();
    $stmt->close();

    $con->close();
    echo "success";
    exit;
}
?>
