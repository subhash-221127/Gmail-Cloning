<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email    = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        die("Please fill in all fields. <a href='login.html'>Go back</a>");
    }

    $sql    = "SELECT * FROM userlog WHERE email='$email' AND password='$password' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name']  = $user['name'];
        header("Location: index.html");
        exit;
    } else {
        echo "Invalid email or password. <a href='login.html'>Try again</a>";
        exit;
    }

} else {
    header("Location: login.html");
    exit;
}
?>
