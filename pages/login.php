<?php
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type_person'];
    
    $data = [
        'email' => $email,
        'password' => $password
    ];
    
    $endpoint = ($type == 'company') ? 'http://localhost:8000/companies/login' : 'http://localhost:8000/people/login';
    
    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        $result = json_decode($response, true);
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['user_type'] = $type;
        $_SESSION['connected'] = true;
        if ($type == 'company') {
            $_SESSION['company'] = true;
            header("Location: form_company.php");
        } else {
            $_SESSION['candidate'] = true;
            header("Location: ../index.php");
        }
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/st_connexion.html.css">
    <link rel="stylesheet" href="../css/no_uderline.css">
    <script src="../js/script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J0B F1ND3R - Login</title>
</head>
<body>
    <div class="div_titre_acc">
        <a href="../index.php"><h1 class="titre_acc">J0B F1ND3R</h1></a>
    </div>

    <div class="div_form">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <select onclick="updateChamp()" id="type_person" name="type_person" required>
            <option value="" selected disabled>Are you a company or an applier</option>
            <option value="company">Company</option>
            <option value="applier">Applier</option>
        </select>

        <?php if($error) { echo "<p style='color: black;'>$error</p>"; } ?>

        <input type="text" id="username" name="username" placeholder="Username or Mail" required>

        <input type="password" id="password" name="password" placeholder="Password" required>
        <input type="checkbox" onclick="show_pass()"><p>Show Password</p>

        <input class="connexion" type="submit" value="Connexion"/>

        <a class="register_button" href="register_comp.html">Register as a Company</a>
        <a class="register_button" href="register_applier.html">Register as an Applier</a>

    </form>
    </div>
</body>
</html>