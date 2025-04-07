<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user'])) {
    header("Location: professorLogin.php");
    exit();
}

$professorEmail = $_SESSION['user'];
$classes = [];
$professorName = "Professor";

$stmt = $con->prepare("SELECT ClassName, ClassID, Semester FROM classes WHERE ProfessorEmail = ?");
//$stmt = $con->prepare("SELECT Name FROM professors WHERE ProfessorEmail = ?");
$stmt->bind_param("s", $professorEmail);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $classes[] = $row;
}

$stmt->close();

$stmt2 = $con->prepare("SELECT Name FROM professors WHERE Email = ?");
$stmt2->bind_param("s", $professorEmail);
$stmt2->execute();
$result = $stmt2->get_result();

while ($row = $result->fetch_assoc()) {
    $professorName = $row['Name'];
}

$stmt2->close();

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Courses</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            text-align: center;
            display: flex;
            flex-direction: column;
        }
        * {
            box-sizing: border-box;
        }
        .header-container {
            /* box format*/
            position: absolute;
            align-self: left;
            width: 100%;
            height: 10%;
            background: #0f5c29;
            top: 0;
            left: 0;
            right: 0;
            padding: 3%;

            /* content format*/
            display: flex;
            flex-direction: row;
            align-items: center;
            text-align: center;
            justify-content: space-between;
        }
        .header-container img {
            height: 40px;
            width: 116px; 
        }
        .header-container h2{
            color: white;
        }
        .signout-btn {
            height: 40px;
            width: 100px;
            border-radius: 20px;
            border: 2px solid #ffe57b;
            background: #fffae6;
            margin-right: 5%;
        }
        .signout-btn:hover { 
            background-color: #fff2bd;
        }

        
        h1 {
            margin-top: 6%;
            text-align: center;
            margin-bottom: 10px;
            color: #0b2015;
        }
        .separator {
            width: 80%;
            height: 2px;
            background-color: #ffcc00;
            margin: 0 auto 20px auto;
        }
        .dropdown-container {
            margin-bottom: 20px;
        }
        select {
            padding: 10px;
            font-size: 16px;
            border-radius: 20px;
            border: 1px solid #ccc;
            background-color: white;
            cursor: pointer;
            transition: 0.3s;
        }
        select:hover {
            border-color: #888;
        }
        select:focus {
            outline: none;
            border-color: #555;
        }
        .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }       
        .course-card {
            background: white;
            border: 1px solid #ecebe7;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px #0000001a;
            cursor: pointer;
            transition: transform 0.2s;
            text-align: center;
            max-width: 400px;
        }
        .course-card:hover {
            transform: scale(1.05);
        }
        .course-image {
            width: 100%;
            height: 150px;
            border-radius: 10px;
            margin-bottom: 10px;
            background-image: url("../Images/courseImage.jpg");
            background-position: center;
            background-size: contain;
        }
    </style>
</head>
<body>
    <div class="header-container"> 
        <img src= "../Images/nsuLogoWhite.png" alt="NSU Logo">
        <h2>Welcome, <?php echo htmlspecialchars(string: $professorName);?>!</h2> 
        <form action="logout.php" method="POST">
            <button type="submit" class="signout-btn">Sign Out</button>
        </form>
    </div>
    <h1>Courses</h1>
    <div class="separator"></div>
    <div class="dropdown-container">
        <select id="semester">
            <option value="" disabled selected>Select Semester</option>
            <option value="Spring 2025">Spring 2025</option>
            <option value="Fall 2024">Fall 2024</option>
        </select>
    </div>
    <div class="container" id="courses-container"></div>
    <script>
        const classes = <?php echo json_encode($classes); ?>;
        const container = document.getElementById("courses-container");
        const semesterSelect = document.getElementById("semester");

        semesterSelect.addEventListener("change", () => {
            const selectedSemester = semesterSelect.value;
            container.innerHTML = "";

            const filtered = classes.filter(c => c.Semester.toLowerCase() === selectedSemester.toLowerCase());

            filtered.forEach(c => {
                const card = document.createElement("div");
                card.classList.add("course-card");
                card.innerHTML = `
                    <form method="POST" action="setClass.php" class="class-form">
                        <input type="hidden" name="chosenClass" value="${c.ClassID}">
                        <div class="course-image"></div>
                        <h3>${c.ClassName}</h3>
                        <p>Class ID: ${c.ClassID}</p>
                    </form>
                `;

                card.addEventListener("click", () => {
                    card.querySelector("form").submit();
                });
                container.appendChild(card);
            });
        });
    </script>
</body>
</html>
