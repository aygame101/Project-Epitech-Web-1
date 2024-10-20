<?php
session_start();
if (!isset($_SESSION['connected']) || !isset($_SESSION['company']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$company_id = $_SESSION['user_id'];

// Recup le nom de l'entreprise
$ch = curl_init("http://localhost:8000/companies/{$company_id}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$company = json_decode($response, true);

if (!$company || !isset($company['name'])) {
    die("Erreur lors de la récupération des informations de l'entreprise.");
}

$company_name = $company['name'];

// Recup les annonces
$ch = curl_init("http://localhost:8000/job_ads");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$all_job_ads = json_decode($response, true);

// Filtrer les annonces pour cette entreprise
$job_ads = array_filter($all_job_ads['job_ads'], function ($ad) use ($company_name) {
    return $ad['company_name'] === $company_name;
});

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/no_uderline.css">
    <title>Company Account</title>
</head>

<body>
    <a href="../index.php">
        <h1>Company Dashboard</h1>
    </a>
    <a href="logout.php">Logout</a>

    <h2>Your Job Advertisements</h2>

    <?php if (empty($job_ads)): ?>
        <p>You haven't posted any job advertisements yet.</p>
    <?php else: ?>
        <?php foreach ($job_ads as $ad): ?>
            <div class="job-ad">
                <h3><?php echo htmlspecialchars($ad['job_title']); ?></h3>

                <p>Location: <?php echo htmlspecialchars($ad['city']); ?></p>

                <p>Contract Type: <?php echo htmlspecialchars($ad['contract_type']); ?></p>

                <p>Wage: <?php echo htmlspecialchars($ad['wage']); ?></p>

                <form action="../api/delete_job_ad.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this job ad?');">
                    <input type="hidden" name="ad_id" value="<?php echo $ad['id']; ?>">
                    <button type="submit">Delete</button>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="form_company.php">Post a New Job Advertisement</a>
</body>

</html>