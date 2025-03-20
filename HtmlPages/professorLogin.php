<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Get data from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize the input data
    $email = stripcslashes($email);
    $password = stripcslashes($password);

    // Validate data
    function validate($data) {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $email = validate($email);
    $password = validate($password);

    if (!empty($email) && !empty($password)) {
        // SQL query to check if the email exists
        $sql = "SELECT * FROM passinfo WHERE email='$email' LIMIT 1";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            // User exists, now check the name field (password)
            $row = mysqli_fetch_assoc($result);
            if ($password === $row['name']) { // Compare form password with 'name' in the database
                // Set session variables for the logged-in user
                $_SESSION['email'] = $row['email'];
                $_SESSION['name'] = $row['name'];  // Assuming 'name' is a column in your table

                // Redirect to the frontend/dashboard page after successful login
                header("Location: Frontend.php"); // Adjust to your desired page
                exit();
            } else {
                echo 'Invalid password. Please try again.';
            }
        } else {
            echo 'Email not found. Please check your email or register.';
        }
    } else {
        echo 'Please fill in all fields.';
    }

    // Close the database connection
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
            color: #0b2015;
            display: flex;
            background-color: #cbe0b1;
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
            border: 5px solid #f7e6a2;
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
            border-top-right-radius: 0px;
            border-bottom-right-radius: 0px;
            border-right: 5px dotted #f7e6a2;
        }

        .login-container {
            /* box format*/
            width: 50%;
            height: 100%;
            border-radius: 10px;
            border-top-left-radius: 0px;
            border-bottom-left-radius: 0px;
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
            background: #ffe373;
            color: black;
            border: 2px solid #ffcc00;
            border-radius: 5px;
            margin-top: 15px;
            cursor: pointer;
        }
        .login-btn:hover {
            background: #ffcc00;
        }
        
    </style>
</head>
<body>
    <div class="header-container">
        <img src="/Images/professorImage.jpg" alt="Professor Image">  
                  
        <div class="login-container">
            <img src="/Images/nsuLogo4.png" alt="NSU Logo">
            <h2>Sign In</h2>
            <p>Enter your login information below</p>
            <form action="/HtmlPages/studentLogin.html" method="POST">
                <input type="text" placeholder="email" required>
                <input type="password" placeholder="password" required>
                <button type="submit" class="login-btn">Sign In</button>
            </form>
        </div>
    </div>
   
</body>
</html>