<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirmpassword'];
    $mobile   = $_POST['mobile'];
    $dob      = $_POST['dob'];
    $gender   = $_POST['gender'];

    if ($password !== $confirm) {
        die("Passwords do not match. <a href='Registration.html'>Go back</a>");
    }

    $sql = "INSERT INTO userlog(name, email, password, mobile, dob, gender) VALUES('$name','$email','$password','$mobile','$dob','$gender')";

    if (mysqli_query($conn, $sql)) {
        echo "Registration successful! <a href='login.html'>Login here</a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

} else {
    header("Location: Registration.html");
    exit;
}
?>
