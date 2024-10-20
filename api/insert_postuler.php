<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['connected']) || !isset($_SESSION['candidate'])) {
    $_SESSION['error_message'] = "You must be logged in to apply";
    header("Location: ../pages/login.php");
    exit();
} else {
    $applicant_id = $_SESSION['user_id'];
    $job_ad_id = $_POST['job_ad_id'] ?? null;
    $company_id = $_POST['company_id'] ?? null;
}


if (!$job_ad_id || !$company_id) {
    header("../index.php");
    echo '<script>alert("Données manquantes : job_ad_id ou company_id")</script>';
}

$data = array(
    'id_annonce_postule' => $job_ad_id,
    'applayer_info' => $applicant_id,
    'id_company' => $company_id
);

$json_data = json_encode($data);

// Envoi des données
$ch = curl_init('http://localhost:8000/apply');
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
    die('Erreur cURL : ' . curl_error($ch));
} else {
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code == 201) {
        echo "Candidature envoyée avec succès !";
        header("Location: ../index.php");
    } else {
        echo "Erreur lors de l'envoi de la candidature. Code HTTP : " . $http_code . "<br>";
        echo "Réponse de l'API : " . $result;
        exit();
    }
}
