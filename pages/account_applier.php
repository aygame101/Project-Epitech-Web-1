<?php
session_start();

// Vérif la connexion
if (!isset($_SESSION['connected']) || !isset($_SESSION['company']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// regarde a quoi l'user a apply
$ch = curl_init("http://localhost:8000/applied_jobs/{$user_id}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$applied_jobs = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/no_uderline.css">
    <title>Applicant Account</title>
</head>
<body>
    <a href="../index.php"><h1>Your Applicant Account</h1></a>
    <a href="logout.php">Logout</a>
    
    <h2>Jobs You've Applied For:</h2>
    
    <?php if (!empty($applied_jobs)): ?>
        <ul>
        <?php foreach ($applied_jobs as $job): ?>
            <li>
                <h3><?php echo htmlspecialchars($job['job_title']); ?></h3>
                <p>Company: <?php echo htmlspecialchars($job['company_name']); ?></p>
                <p>Location: <?php echo htmlspecialchars($job['city']); ?></p>
                <p>Contract Type: <?php echo htmlspecialchars($job['contract_type']); ?></p>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>You haven't applied to any jobs yet.</p>
    <?php endif; ?>
    
    
</body>
</html>