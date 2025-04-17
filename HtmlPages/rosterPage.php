<?php
    session_start();
    include("connection.php");

    $classID = $_SESSION['chosenClass'];
    $students = [];
    $dates = [];

    $stmt = $con->prepare("SELECT StudentID, StudentName, Status, AttendanceDate  FROM attendance WHERE ClassID = ?");
    //$stmt = $con->prepare("SELECT Name FROM professors WHERE ProfessorEmail = ?");
    $stmt->bind_param("s", $classID);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        $students[] = $row;
        if(!in_array($row['AttendanceDate'], $dates)){
            $dates[] = $row['AttendanceDate'];
        }
    }
    $stmt->close();

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
            padding: 2%;

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
            margin-top: 4%;
            margin-bottom: 5px;
            margin-left: 10%;
            margin-right: 10%;
        }
        .title-container h1 {
            color: #0b2015;
        }
        .course-code h2{
            height: 50px;
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
            width: 120px;
            border-radius: 20px;
            border: 2px solid #ffe57b;
            background: #fffae6;
            margin-right: 5%;
        }
        .back-btn:hover {
            background: #ffe57b;
        }

        .separator {
            width: 80%;
            height: 2px;
            background-color: #ffcc00;
            margin: 0 auto 20px auto;
        }
        
        .options-container {
            width: 80%;
            justify-content: space-between;
            display: flex;
            flex-direction: row;
            align-self: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .dropdown-container {
            align-self: center;
        }
        select {
            padding: 10px;
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
        .edit-options {
            display: flex;
            flex-direction: row;
            align-items: center;
        }
        .edit-button {
            width: 150px;
            height: 40px;
            cursor: pointer;
            border-radius: 20px;
            background-color: #bfd3a6;
            color: black;
            border: none;
            margin-left: 15px;
            font-weight: bold;
        }
        .edit-button:hover {
            background-color: #97ac7d;
        }

        .student-container {
            width: 80%;
            margin: 0 auto;
        }
        .student-entry {
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-left: 20px;
            padding-right: 20px;
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
            width: 90px; 
            text-align: center;
        }
        .present {
            background-color: #d4edda;
            color: #155724;
        }
        .late {
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
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 30%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            border: 5px solid #b9d399;
        }
        .modal-content h2 {
            color: #0b2015
        }
        .modal-content h3{
            font-size: medium;
            font-weight: 300;
        }

        .modal-content input {
            width: 85%;
            padding: 10px;
            margin: 5px;
            margin-top: 15px;
            background: white;
            border: 1px solid #32560c;
            border-radius: 5px;
        }

        .modal-content button {
            font-size: 15px;
            width: 40%;
            padding: 10px;
            background: #ffe373;
            color: black;
            border: 2px solid #ffcc00;
            border-radius: 5px;
            margin-top: 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header-container"> 
        <img src="../Images/nsuLogoWhite.png" alt="NSU Logo">
        <form action="professorLogin.html" method="POST">
            <button type="submit" class="signout-btn">Sign Out</button>
        </form>
    </div>

    <div class="title-container">
        <form action="coursePage.php" method="POST">
            <button type="submit" class="back-btn">Back to Courses</button>
        </form>
        <h1>Course Attendance</h1>
        <div class="course-code">
            <h2>Course Code:</h2>
        </div>
    </div>
    
    <div class="separator"></div>

    <div class="options-container">
        <div class="dropdown-container">
            <select id="dateSelect">
                <option value = "" disabled selected>Select Date</option>
            </select>

        </div>
        <div class="edit-options">
           <!-- <button class="edit-button" onclick="addStudentPopup()" >Add Student</button> -->
            <button class="edit-button" onclick="removeStudentPopup()">Remove Student</button>
        </div>
    </div>

    <div class="student-container">
        <div id="studentList"></div>
    </div>

<!--   <div id="addModal" class="modal">
        <div class="modal-content">
            <h2>Add Student</h2>
            <h3>Please enter the students information:</h3>
            <input type="text" id="addFirstName" placeholder="First Name">
            <input type="text" id="addLastName" placeholder="Last Name">
            <input type="text" id="addStudentId" placeholder="Student ID">
            <button onclick="submitAddStudent()">Add Student</button>
        </div>
    </div> -->
    <div id="removeModal" class="modal">
        <div class="modal-content">
            <h2>Remove Student</h2>
            <h3>Please enter the students information:</h3>
            <input type="text" id="removeStudentId" placeholder="Student ID">
            <button onclick="submitRemoveStudent()">Remove Student</button>
        </div>
    </div>
    <script>
        // Populating valid dates:
        const validDates = <?php echo json_encode($dates); ?>;
        const dateSelect = document.getElementById("dateSelect");
        validDates.forEach(date => {
            const option = document.createElement("option");
            option.value = date;
            option.textContent = date;
            dateSelect.appendChild(option);
        });

        dateSelect.addEventListener("change", () => {
        const selectedDate = dateSelect.value;
        const filtered = students.filter(s => s.AttendanceDate === selectedDate);
        renderList(filtered);
        });

        //Populating students and icons
        const students = <?php echo json_encode($students); ?>;
        const icons = {
            "Present": "../Images/presentIcon.png",
            "Late": "../Images/tardyIcon.png",
            "Absent": "../Images/absentIcon.png",
        };

        const studentList = document.getElementById("studentList");

        function renderList(filteredStudents) {
            studentList.innerHTML = '';
            filteredStudents.forEach((student, index) => {
            const entry = document.createElement("div");
            entry.className = "student-entry";

            const name = document.createElement("div");
            name.className = "student-name";
            name.textContent = student.StudentName;

            const id = document.createElement("div");
            id.className = "student-id";
            id.textContent = student.StudentID;

            const statusContainer = document.createElement("div");

            const statuses = ["Present", "Late", "Absent"];

            statuses.forEach(status => {
                const statusSpan = document.createElement("span");
                statusSpan.classList.add("status");

                const iconDiv = document.createElement("div");
                iconDiv.className = "status-icon";
                const img = document.createElement("img");
                img.src = icons[status];
                img.alt = status;
                img.style.width = "24px";
                img.style.height = "24px";
                iconDiv.appendChild(img);

                const labelDiv = document.createElement("div");
                labelDiv.textContent = status;

                statusSpan.appendChild(iconDiv);
                statusSpan.appendChild(labelDiv);

                if (student.Status === status){
                statusSpan.classList.add(status.toLowerCase());
                }else{
                statusSpan.classList.add("inactive");
                }
    
              statusSpan.addEventListener('click', () => {
                const studentId = student.StudentID;
                const date = student.AttendanceDate;

                fetch("updateStatus.php",{
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `studentId=${encodeURIComponent(studentId)}&status=${encodeURIComponent(status)}&date=${encodeURIComponent(date)}`
                })
                .then(response => response.text())
                .then(result => {
                    console.log("Result is:", result.trim());
                    if(result.trim() === "success"){
                        students[index].status = status;
                        renderList(filteredStudents); // Re-render with current filtered list
                    }else{
                        console.log("Sending status:", status);
                        alert("Failed to update attendance.");
                    }
                })
                .catch(error =>{
                    console.error("Error updating status:", error);
                });
            });

                statusContainer.appendChild(statusSpan);
            });

            entry.appendChild(name);
            entry.appendChild(id);
            entry.appendChild(statusContainer);
            studentList.appendChild(entry);
        });
    }
/* 
    function addStudentPopup() {

    }
        document.getElementById("addModal").style.display = "flex";
        function submitAddStudent() {
        const firstName = document.getElementById("addFirstName").value.trim();
        const lastName = document.getElementById("addLastName").value.trim();
        const id = document.getElementById("addStudentId").value.trim();
        if (firstName && lastName && id) {
            students.push({ id, name: `${firstName} ${lastName}`, status: null });
            renderList();
            document.getElementById("addModal").style.display = "none";
        }
    }
*/
    function removeStudentPopup() {
        document.getElementById("removeModal").style.display = "flex";
    }

    function submitRemoveStudent() {
        const id = document.getElementById("removeStudentId").value.trim();

        if(!id){
            alert("Please fill all fields");
            return;
        }

        fetch("removeStudent.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `studentId=${encodeURIComponent(id)}`
        })
        .then(response => response.text())
        .then(result => {
            if (result === "success"){
                // Remove from JS array and re-render
                const index = students.findIndex(s => s.StudentID === id);
                if (index !== -1) {
                    students.splice(index, 1);
                    const selectedDate = dateSelect.value;
                    const filtered = students.filter(s => s.AttendanceDate === selectedDate);
                    renderList(filtered);
                }
                alert("Student removed successfully.");
                document.getElementById("removeModal").style.display = "none";
            } else if(result==="error"){
                alert("Failed to remove student.");
            }
        })
    }

    window.onclick = function(event) {
        if (event.target.classList.contains("modal")) {
            event.target.style.display = "none";
        }
    }
    renderList(students);
  </script>
</body>
</html>