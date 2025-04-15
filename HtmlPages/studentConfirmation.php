<?php
session_start();
include("connection.php");

$studentID = $_SESSION['user'];
$studentName = "student";
$classID = $_SESSION['classID'];
$checkInTime = $_SESSION['checkinTime'];
$checkinDate = $_SESSION['checkinDate'];

$stmt = $con->prepare("SELECT Name FROM students WHERE StudentID = ?");
//$stmt = $con->prepare("SELECT Name FROM professors WHERE ProfessorEmail = ?");
$stmt->bind_param("s", $studentID);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $studentName = $row['Name'];
}

$stmt->close();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <title>Landing Page 2</title>
    <style>
        body, html {
            height: 100%;
            font-family: 'Trebuchet MS', sans-serif;
            display: flex;
            background-color: #f7e6a2;
            align-items: center;
        }
        * {
            box-sizing: border-box;
        }

        .header-container {
            /* box format*/
            position: absolute;
            align-self: left;
            width: 100%;
            height: 100%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);

            /* content format*/
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .header-container img{
            width: 45%;
            height: 45%;
            object-fit: contain;
            border-radius: 10px;
            border-top-left-radius: 0px;
            border-bottom-left-radius: 0px;
            margin-top: 7%;
        }
        .header-container h2{
            font-size: 40px;
            font-weight: 200;
            margin-top: 0px;
            color: #0b2015;
        }
        .header-container p{
            font-size: 18px;
            color: #0b2015;
        }
    </style>
</head>
<body>
    <div class="header-container">       
        <img src="/Images/confirmationImage4.gif" alt="Confirmation GIF"> 
        <div class="login-container">
            <h2>You're all set, <?php echo $studentName ?>!</h2>
            <p>Your attendance has been recorded.</p>
            <p> You've signed in for <?php echo $classID?> at <?php echo $checkInTime?> on <?php echo $checkinDate?></p>
        </div>
    </div>
</body>
</html>


