<?php

$conn = mysqli_connect("localhost", "root", "", "test");


if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}



$name = $_POST['name'];
$pwd= $_POST['password'];
$salt = '15'; 
$password = password_hash($pwd, PASSWORD_BCRYPT);

$sql = "INSERT INTO companies (name, password) VALUES ('$name', '$password')";



if (mysqli_query($conn, $sql)) {
  echo "Data inserted successfully!";
} else {
  echo "Error: " . mysqli_error($conn);
}


mysqli_close($conn);
header('location: ../pages/login.php');