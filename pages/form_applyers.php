<?php
session_start();

$name = $firstname = $phone = $mail = '';

// Vérif si un utilisateur est connecté
if (isset($_SESSION['connected']) && isset($_SESSION['candidate']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $ch = curl_init("http://localhost:8000/people/{$user_id}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $user_data = json_decode($response, true);
        $name = $user_data['name'] ?? '';
        $firstname = $user_data['firstname'] ?? '';
        $phone = $user_data['phone'] ?? '';
        $mail = $user_data['mail'] ?? '';
    }
}

$job_ad_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$job_ad_id) {
    die("Aucun ID d'annonce n'a été fourni.");
}

$ch = curl_init("http://localhost:8000/job_ads/{$job_ad_id}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$job_ad = json_decode($response, true);

if (!$job_ad) {
    die("Annonce non trouvée.");
}

$company_name = urlencode($job_ad['company_name']);
$ch = curl_init("http://localhost:8000/companies/search?name={$company_name}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$company = json_decode($response, true);

if (!isset($company['id'])) {
    die("Entreprise non trouvée.");
}

$company_id = $company['id'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="../css/no_uderline.css">
    <script src="js/script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J0B F1ND3R</title>
</head>

<body>
    <div class="div_titre_acc">
        <a href="../index.php">
            <h1 class="titre_acc">J0B F1ND3R</h1>
        </a>

        <?php
        if (!isset($_SESSION['connected'])) {
            echo '<a class="login" href="login.php">Login</a>';
        } else if (isset($_SESSION['connected'])) {
            if (isset($_SESSION['company'])) {
                echo '<a class="login" href="account_company.php">Account</a>';
            } else if (isset($_SESSION['candidate'])) {
                echo '<a class="login" href="account_applier.php">Account</a>';
            } else if (isset($_SESSION['admin'])) {
                echo '<a class="login" href="admin.php">Account</a>';
            }
        }
        ?>
    </div>

    <div class="div_form">
        <form action="../api/insert_postuler.php" method="post">
            <div>
                <label for="name">Your name : </label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>

            <div>
                <label for="firstname">Your first name : </label>
                <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($firstname); ?>" required>
            </div>

            <div>
                <label for="phone">Your phone number : </label>
                <input type="tel" maxlength="20" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
            </div>

            <div>
                <label for="mail">Your email : </label>
                <input type="email" id="mail" name="mail" value="<?php echo htmlspecialchars($mail); ?>" required>
            </div>

            <!-- Champs pour stocker les id -->
            <input type="hidden" name="job_ad_id" value="<?php echo htmlspecialchars($job_ad_id); ?>">
            <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($company_id); ?>">

            <input class="valider" type="submit" value="Valider" />
        </form>
    </div>
</body>

</html>