<?php

$conn = mysqli_connect("localhost", "root", "", "test");


if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


$name = $_POST['name'];
$firstname = $_POST['firstname'];
$mail = $_POST['mail'];
$phone = $_POST['phone'];
$pwd= $_POST['password'];
$salt = '15'; 
$password = password_hash($pwd, PASSWORD_BCRYPT);



$sql = "INSERT INTO people (name, firstname, mail, phone, password) VALUES ('$name',  '$firstname', '$mail', '$phone', '$password')";




if (mysqli_query($conn, $sql)) {
  echo "Data inserted successfully!";
} else {
  echo "Error: " . mysqli_error($conn);
}


mysqli_close($conn);
