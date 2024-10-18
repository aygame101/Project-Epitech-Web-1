<?php

$company_name = nl2br(htmlspecialchars($_POST['company_name']));
$city = nl2br(htmlspecialchars($_POST['city']));
$job_title = nl2br(htmlspecialchars($_POST['job']));
$contract_type = nl2br(htmlspecialchars($_POST['contract']));
$wage = nl2br(htmlspecialchars($_POST['wage']));
$mail_in_charge = nl2br(htmlspecialchars($_POST['in_charge']));
$description_job = nl2br(htmlspecialchars($_POST['description_job']));

$data = [
    'company_name' => $company_name,
    'city' => $city,
    'job_title' => $job_title,
    'contract_type' => $contract_type,
    'wage' => $wage,
    'mail_in_charge' => $mail_in_charge,
    'description_job' => $description_job
];

$url = 'http://localhost:8000/job_ads';

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data)
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    $error = error_get_last();
    echo "An error occurred while sending data to the API: " . $error['message'];
} else {
    $response = json_decode($result, true);
    if (isset($response['message'])) {
        echo $response['message'];
    } else {
        echo "Data sent successfully to the API.";
    }
}

$response = file_get_contents('http://127.0.0.1:8000/job_ads');
if ($response === FALSE) {
    die('Error fetching data from API');
}
$data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Error decoding JSON data');
}

header('location: ../index.php');