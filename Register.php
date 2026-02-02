<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name=$_POST['name'];
$email=$_POST['email'];
$password=$_POST['password'];
$confirm=$_POST['confirmpassword'];
$mobile=$_POST['mobile'];
$dob=$_POST['dob'];
$gender=$_POST['gender'];

if($password !== $confirm){
    die("Password do not match");
}

}



$sql="insert into userlog(name,email,password,mobile,dob,gender) values('$name','$email','$password','$mobile','$dob','$gender')";


if(mysqli_query($conn,$sql)){
    echo "Registration successfully";
}

?>