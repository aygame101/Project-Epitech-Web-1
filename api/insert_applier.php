<?php

$name = $_POST['name'];
$firstname = $_POST['firstname'];
$mail = $_POST['mail'];
$phone = $_POST['phone'];
$pwd = $_POST['password'];

//verif si il y a deja un user avec ce mail
$ch = curl_init("http://localhost:8000/people/search?email=" . urlencode($mail));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    $result = json_decode($response, true);
    //Le message d'erreur ne s'affiche pas
    if (!empty($result)) {
        $_SESSION['error'] = "An account with this email address already exists.";
        header('Location: ../pages/register_applier.php');
        exit();
    }
}

//si il n'y en a pas, en cree un
$data = array(
  'name' => $name,
  'firstname' => $firstname,
  'mail' => $mail,
  'phone' => $phone,
  'password' => $pwd,
  'is_applier' => 1
);

$json_data = json_encode($data);

$ch = curl_init('http://localhost:8000/people');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt(
  $ch,
  CURLOPT_HTTPHEADER,
  array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($json_data)
  )
);

$result = curl_exec($ch);

if (curl_errno($ch)) {
  $_SESSION['error'] = 'Erreur cURL : ' . curl_error($ch);
  header('Location: ../pages/register_applier.php');
} else {
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if ($http_code == 201) {
      $_SESSION['success'] = "Inscription réussie !";
      header('location: ../pages/login.php');
  } else {
      $_SESSION['error'] = "Erreur lors de l'inscription. Code HTTP : " . $http_code;
      header('Location: ../pages/register_applier.php');
  }
}

curl_close($ch);
exit();
