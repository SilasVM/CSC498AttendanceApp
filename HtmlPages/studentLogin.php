<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['studentID']) && isset($_POST['classID'])) {

    function validate($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $attendanceDate = date('Y-m-d');
    $checkInTime = date("h:i:a");
    $studentID = validate($_POST['studentID']);
    $classID = validate($_POST['classID']);

    if (!empty($studentID) && !empty($classID)) {
        // ✅ Step 1: Check if student exists
        $stmt = $con->prepare("SELECT * FROM students WHERE StudentID = ?");
        $stmt->bind_param("s", $studentID);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result->fetch_assoc()) {
            echo "<script>alert('Error: Student is not registered in the system.'); window.location.href = window.location.href;</script>";
            exit();
        }
        $stmt->close();

        // ✅ Step 2: Check if class exists
        $stmt = $con->prepare("SELECT * FROM classes WHERE ClassID = ?");
        $stmt->bind_param("s", $classID);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result->fetch_assoc()) {
            echo "<script>alert('Error: The specified class does not exist.'); window.location.href = window.location.href;</script>";
            exit();
        }
        $stmt->close();

        // ✅ Step 3: Check if student is registered in the class
        $stmt = $con->prepare("SELECT * FROM enrollment WHERE StudentID = ? AND ClassID = ?");
        $stmt->bind_param("ss", $studentID, $classID);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result->fetch_assoc()) {
            echo "<script>alert('Error: Student is not registered in this class. Attendance not recorded.'); window.location.href = window.location.href;</script>";
            exit();
        }
        $stmt->close();

        // ✅ Step 4: Insert into `attendance`
        $stmt = $con->prepare("INSERT INTO attendance (studentID, classID, attendanceDate, checkInTime) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $studentID, $classID, $attendanceDate, $checkInTime);

        if ($stmt->execute()) {
            echo "<script>console.log('Attendance recorded successfully.');</script>";
            $_SESSION['user'] = $studentID;
            header("Location: studentConfirmation.html");
            exit();
        } else {
            echo "<script>alert('Error: Attendance recording failed.'); window.location.href = window.location.href;</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please fill in all fields.'); window.location.href = window.location.href;</script>";
    }

    mysqli_close($con);
}
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
            width: 50%;
            height: 60%;
            border: 5px solid #b9d399;
            border-radius: 15px;
            background: #fffae6;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0px 0px 10px #4e4d4d;

            /* content format*/
            display: flex;
            flex-direction: row;
            align-items: center;
        }
        .header-container img{
            width: 50%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
            border-top-left-radius: 0px;
            border-bottom-left-radius: 0px;
            margin-bottom: 0;
            border-left: 5px dotted #b9d399
        }

        .login-container {
            /* box format*/
            width: 50%;
            height: 100%;
            border-radius: 10px;
            border-top-right-radius: 0px;
            border-bottom-right-radius: 0px;
            background: white;
            padding: 20px;

            /* content format*/
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .login-container img {
            height:57px;
            width: 142px;
            margin-bottom: 10px;
            margin-top: 18px;
            border: none;
        }

        .login-container p{
            font-size: 15px;
        }
        .login-container input {
            width: 85%;
            padding: 10px;
            margin: 5px;
            margin-top: 15px;
            background: white;
            border: 1px solid #32560c;
            border-radius: 5px;
        }
        .login-btn {
            font-size: 15px;
            width: 40%;
            padding: 10px;
            background: #339966;
            color: white;
            border: 2px solid #297b52;
            border-radius: 5px;
            margin-top: 15px;
            cursor: pointer;
        }
        .login-btn:hover {
            background: #297b52;
        }
        
    </style>
</head>
<body>
    <div class="header-container">        
        <div class="login-container">
            <img src="/Images/nsuLogo4.png" alt="NSU Logo">
            <h2>Attendance Sign In</h2>
            <p>Enter your login information below</p>
            <form action="studentLogin.php" method="POST">
                <input type="text" name ="studentID" placeholder="Student ID" required>
                <input type="text" name ="classID" placeholder="Class Code" required>
                <button type="submit" class="login-btn">Sign In</button>
            </form>
        </div>
        <img src="/Images/studentImage.jpg" alt="Student Image">  
    </div>
</body>
</html>
