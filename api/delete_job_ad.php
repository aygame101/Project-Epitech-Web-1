<?php
session_start();

if (!isset($_SESSION['connected']) || !isset($_SESSION['company'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_POST['ad_id'])) {
    header('Location: ../pages/account_company.php');
    exit();
}

$ad_id = $_POST['ad_id'];

$ch = curl_init("http://localhost:8000/job_ads/{$ad_id}");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    $_SESSION['message'] = "Job ad successfully deleted.";
} else {
    $_SESSION['error'] = "Failed to delete job ad. Please try again.";
}

header('Location: ../pages/account_company.php');
exit();