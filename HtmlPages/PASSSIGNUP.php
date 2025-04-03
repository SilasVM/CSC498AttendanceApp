<?php
    session_start();
    include("connection.php");

    if($_SERVER['REQUEST_METHOD'] == "POST"){
      
        $username = $_POST['username'];
        $status = $_POST['status'];
        $dorm = $_POST['dorm'];
        $handicap = @$_POST['handicap'];
        $reserved = @$_POST['reserved'];
        $nsu_id = $_POST['nsu_id'];
        $decal_id = $_POST['decal_id'];
        $password = $_POST['password'];

        $username = stripcslashes($username);
        $status = stripcslashes($status);
        $dorm = stripcslashes($dorm);
        $handicap = stripcslashes($handicap);
        $reserved = stripcslashes($reserved);
        $nsu_id = stripcslashes($nsu_id);
        $decal_id = stripcslashes($decal_id);
        $password = stripcslashes($password);


    
    // using sql to create a data entry query'
    $userC = "SELECT * FROM passinfo WHERE username='$username'";
              $result = mysqli_query($con, $userC);
  
              if((mysqli_num_rows($result))>0){
                echo 'Username Already In Use. Please Try Another Username Or Login';
              }else{
                $sql = "INSERT INTO passInfo (username, status, dorm, handicap, reserved, nsu_id, decal_id, password) VALUES ('$username', '$status', '$dorm', '$handicap', '$reserved', '$nsu_id', '$decal_id', '$password')";
                header("Location: Frontend");
                mysqli_query($con, $sql);

              }
          

   // mysqli_query($con, $sql);

    // send query to the database to add values and confirm if successful
    //$rs =
    

    header("Frontend");
   /* if($rs)
    {
        echo "Entries added!";
    }  */
  
    // close connection
    mysqli_close($con);

}
?>
<!DOCTYPE html>
<html>
<style>
body {font-family: "Times New Roman", Times, serif;}
* {box-sizing: border-box}

.dropdown-tabs{
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

.checkbox-input{
  max-width: 30%;
  padding: 10px;
  margin: 0px;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

.checkbox-input-label{
  max-width: 70%;
  padding: 10px;
  margin: 0px;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

.check-box-space{
  padding: 5px;
}
/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}
input[type=int], input[type=int] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=int]:focus, input[type=int]:focus {
  background-color: #ddd;
  outline: none;
}

hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for all buttons */
button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

button:hover {
  opacity:1;
}

/* Extra styles for the cancel button */
.cancelbtn {
  padding: 8px 13px;
  background-color: #f44336;
  width: 15%;

}

/* Float cancel and signup buttons and add an equal width */
.signupbtn {
  float: left;
  width: 50%;
}


/* Add padding to container elements */
.container {
  padding: 16px;
}

/* Clear floats */
.clearfix::after {
  content: "";
  clear: both;
  display: table;
}

/* Change styles for cancel button and signup button on extra small screens */
@media screen and (max-width: 300px) {
  .cancelbtn, .signupbtn {
     width: 100%;
  }
}

</style>
<body>
<script>
var x = document.getElementById("demo");

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.watchPosition(showPosition);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}
    
function showPosition(position) {
    x.innerHTML="Latitude: " + position.coords.latitude + 
    "<br>Longitude: " + position.coords.longitude;
}
</script>
<form action="" method="POST" style="border:1px solid #ccc">
  <div class="container">
    <h1>Account Creation</h1>
    <p>Please fill all applicable fields to create an account.</p>
    <hr>

    <p> 
    <label for="username"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" id="username" required>

    <label for="status"><b>Status</b></label>
        <form>
            <select name="status" class="dropdown-tabs">
                <option value="On Campus Student"> On Campus Student</option>
                <option value="Commuter Student"> Commuter Student</option>
                <option value="Staff"> Staff</option>
                <option value="Faculty"> Faculty</option>
                <option value="Visitor"> Visitor</option>
            </select>

            <div></div>
            
        <label for="dorm"><b>Dorm</b></label>
        <select name="dorm" class="dropdown-tabs">
                <option value="N_A"></option>
                <option value="Charles_Hall"> Charles Hall</option>
                <option value="Lee_Hall"> Lee Hall</option>
                <option value="Midrise_Hall"> Midrise Hall</option>
                <option value="Rosa Hall"> Rosa Hall</option>
                <option value="New Residential South"> New Residential South</option>
                <option value="New Residential North"> New Residential North</option>
                <option value="Babbette Smith South"> Babbette Smith South</option>
                <option value="Babbette Smith North"> Babbette Smith North</option>
                <option value="Samuel Scott Hall">Samuel Scott Hall </option>
                <option value="Spartan Suites">Spartan Suites </option>
            </select>
        </form>

    <label class="checkbox-input-label" for="handicap"><b>Do you have handicap parking? Check box for yes.</b></label>
    <input class="checkbox-input" name="handicap" id="handicap" defauleValue="0" value="1" type="checkbox"  >

    <label class="checkbox-input-label" for="reserved"><b>Do you have reserved parking? Check box for yes.</b></label>
    <input class="checkbox-input" name="reserved" id="reserved" value="1" type="checkbox"  >


    <div></div>
    <label for="nsu_id"><b>Norfolk State ID</b></label>
    <input type="int" placeholder="Enter NSU ID" name="nsu_id" id="nsu_id" minlength="6" maxlength ="6">

    <label for="decal_id"><b>Parking Decal ID</b></label>
    <input type="int" placeholder="Enter Decal ID" name="decal_id" id="decal_id" minlength = "7" maxlength = "7">
    <label for="password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required minlength="5" maxlength="15" pattern="(?=.*\W+)(?=.*[a-z])(?=.*[A-Z]).{5,}" title="Must contain at least one special character, one uppercase letter, one lowercase letter, and between 5-15 characters">
    

    <div class="clearfix">
    <button type="submit" class="signupbtn">Sign Up</button>
</div>
    <div>
    <a href='PASSHOME.html'class="cancelbtn">Cancel</a>
    </div>
  </div>
</form>

</body>
</html>
