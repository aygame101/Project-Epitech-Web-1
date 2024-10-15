<?php

$company_name = nl2br(htmlspecialchars($_POST['company_name']));
$city = nl2br(htmlspecialchars($_POST['city']));
$job_title = nl2br(htmlspecialchars($_POST['job']));
$contract_type = nl2br(htmlspecialchars($_POST['contract']));
$wage = nl2br(htmlspecialchars($_POST['wage']));
$mail_in_charge = nl2br(htmlspecialchars($_POST['in_charge']));
$description_job = nl2br(htmlspecialchars($_POST['description_job']));


require_once('../assets/hypnos.php');

try {
    
    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $req = $bdd->prepare('
        INSERT INTO job_ads(company_name, city, job_title, contract_type, wage, mail_in_charge, description_job) 
        VALUES(:company_name, :city, :job_title, :contract_type, :wage, :mail_in_charge, :description_job)
    ');

   
    $req->execute(array(
        'company_name' => $company_name,
        'city' => $city,
        'job_title' => $job_title,
        'contract_type' => $contract_type,
        'wage' => $wage,
        'mail_in_charge' => $mail_in_charge,
        'description_job' => $description_job
    ));

    
    header('Location: form_company.html');
    exit();

} catch (PDOException $e) {
    
    echo "Erreur : " . $e->getMessage();
    die;
}