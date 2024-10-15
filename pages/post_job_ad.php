<?php

$company_name = nl2br(htmlspecialchars($_POST['company_name']));
$city = nl2br(htmlspecialchars($_POST['city']));
$job = nl2br(htmlspecialchars($_POST['job']));
$contract = nl2br(htmlspecialchars($_POST['contract']));
$wage = nl2br(htmlspecialchars($_POST['wage']));
$mail_in_charge = nl2br(htmlspecialchars($_POST['in_charge']));
$job_description = nl2br(htmlspecialchars($_POST['job_description']));

require_once('../assets/hypnos.php');

try
{
    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e)
{
    print "Erreur :" . $e->getMessage() . "<br/>";
    die;
}

$req = $bdd->prepare('INSERT INTO job_ads(company_name, city, job_title, contract_type, wage, mail_in_charge, description) VALUES(:company_name, :city, :job_title, :contract_type, :wage, :mail_in_charge, :description)');
$req->execute(array(
    'company_name' => $company_name,
    'city' => $city,
    'job_title' => $job,
    'contract_type' => $contract,
    'wage' => $wage,
    'mail_in_charge' => $mail_in_charge,
    'description' => $job_description
));

header('Location: form_company.html');