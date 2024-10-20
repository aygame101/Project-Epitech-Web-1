<?php

$name = $_POST['name'];
$pwd = $_POST['password'];

//verif si il y a deja un user avec ce mail
$ch = curl_init("http://localhost:8000/companies/search?name=" . urlencode($name));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    $result = json_decode($response, true);
    if (!empty($result)) {
        // Le message d'erreur ne s'affiche pas
        $_SESSION['error'] = "A company with this name already exists.";
        header('Location: ../pages/register_comp.php');
        exit();
    }
}

//si il n'y en a pas, la cree
$data = array(
  'name' => $name,
  'password' => $pwd
);

$json_data = json_encode($data);

$ch = curl_init('http://localhost:8000/companies');
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
  echo 'Erreur cURL : ' . curl_error($ch);
} else {
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if ($http_code == 201) {
    echo "Inscription de l'entreprise réussie !";
  } else {
    echo "Erreur lors de l'inscription de l'entreprise. Code HTTP : " . $http_code;
  }
}

curl_close($ch);

header('location: ../pages/login.php');
