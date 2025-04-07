<?php
session_start();
include("connection.php");

$classID = $_SESSION['chosenClass'];
$students = [];

$stmt = $con->prepare("SELECT StudentID, ClassID, 'Status' FROM attendance WHERE ClassID = ?");
//$stmt = $con->prepare("SELECT Name FROM professors WHERE ProfessorEmail = ?");
$stmt->bind_param("s", $classID);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

$stmt->close();


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

        .title-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            text-align: center;
            justify-content: space-between;
            width: 80%;
            height:fit-content;
            margin-top: 5%;
            margin-bottom: 10px;
            margin-left: 10%;
            margin-right: 10%;
        }
        .title-container h1 {
            color: #0b2015;
        }
        .course-code h2{
            height: 40px;
            width: 150px;
            border-radius: 20px;
            border: 2px solid #ffe57b;
            background: #fff2bd;
            margin-right: 5%;
            color: #0b2015;
            font-size: small;
            padding-top: 5%;
        }
        .back-btn {
            height: 40px;
            width: fit-content;
            border-radius: 20px;
            border: 2px solid #adadad;
            background: #e8e8e8;
        }

        .separator {
            width: 80%;
            height: 2px;
            background-color: #ffcc00;
            margin: 0 auto 20px auto;
        }
        
        .container {
            width: 50%;
            margin: 0 auto;
        }
        .student-entry {
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .student-name {
            font-weight: bold;
            color: #333;
        }
        .status {
            padding: 6px 12px;
            border-radius: 12px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
            margin-left: 20px;
            cursor: pointer;
        }
        .present {
            background-color: #d4edda;
            color: #155724;
        }
        .tardy {
            background-color: #fff3cd;
            color: #856404;
        }
        .absent {
            background-color: #f8d7da;
            color: #721c24;
        }
        .inactive {
            background-color: #e2e3e5;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header-container"> 
        <img src="../Images/nsuLogoWhite.png" alt="NSU Logo">
        <form action="logout.php" method="POST">
            <button type="submit" class="signout-btn">Sign Out</button>
        </form>
    </div>

    <div class="title-container">
        <form action="coursePage.php" method="POST">
            <button type="submit" class="back-btn">Back to Courses</button>
        </form>
        <h1>Course Attendance</h1>
        <div class="course-code">
            <h2>Course ID: <?php echo htmlspecialchars(string: $classID);?></h2>
        </div>
    </div>


    <div class="separator"></div>

    <div class="container">
        <div id="studentList"></div>
    </div>

    <script>
        const students = [
          { id: 1, name: "Karlena Hardaway", status: "Present" },
          { id: 2, name: "Kaleb Jarmen", status: "Tardy" },
          { id: 3, name: "O'Neil Williams", status: "Absent" },
          { id: 4, name: "Victor Morgan", status: "Present" },
          { id: 5, name: "Jalaya Allen", status: "Tardy" }
        ];
    
        const studentList = document.getElementById("studentList");
    
        function renderList() {
          studentList.innerHTML = '';
          students.forEach((student, index) => {
            const entry = document.createElement("div");
            entry.className = "student-entry";
    
            const name = document.createElement("div");
            name.className = "student-name";
            name.textContent = student.name;
    
            const statusContainer = document.createElement("div");
    
            const statuses = ["Present", "Tardy", "Absent"];
    
            statuses.forEach(status => {
              const statusSpan = document.createElement("span");
              statusSpan.textContent = status;
              statusSpan.classList.add("status");
    
              if (student.status === status) {
                statusSpan.classList.add(status.toLowerCase());
              } else {
                statusSpan.classList.add("inactive");
              }
    
              statusSpan.addEventListener('click', () => {
                students[index].status = status;
                renderList();
              });
    
              statusContainer.appendChild(statusSpan);
            });
    
            entry.appendChild(name);
            entry.appendChild(statusContainer);
            studentList.appendChild(entry);
          });
        }
        renderList();
      </script>
</body>
</html>