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

    if ($email == 'admin') {
        $endpoint = 'http://localhost:8000/admin/login';
        $data['username'] = $email;
    } elseif ($type == 'company') {
        $endpoint = 'http://localhost:8000/companies/login';
    } else {
        $endpoint = 'http://localhost:8000/people/login';
    }

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
        if ($email == "admin") {
            $_SESSION['admin'] = true;
            header("Location: admin.php");
        } else if ($type == "applier" && $email !== "admin") {
            $_SESSION['candidate'] = true;
            header("Location: ../index.php");
        } else if ($type == 'company' && $email !== "admin") {
            $_SESSION['company'] = true;
            header("Location: form_company.php");
        }
        exit();
    } else {
        $error = "Invalid email/username or password";
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
        <a href="../index.php">
            <h1 class="titre_acc">J0B F1ND3R</h1>
        </a>
    </div>

    <div class="div_form">

        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="error-message">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <select onclick="updateChamp()" id="type_person" name="type_person" required>
                <option value="" selected disabled>Are you a company or an applier</option>
                <option value="company">Company</option>
                <option value="applier">Applier</option>
            </select>

            <?php if ($error) {
                echo "<p style='color: black;'>$error</p>";
            } ?>

            <input type="text" id="username" name="username" placeholder="Username or Mail" required>

            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="checkbox" onclick="show_pass()">
            <p>Show Password</p>

            <input class="connexion" type="submit" value="Connexion" />

            <a class="register_button" href="register_comp.php">Register as a Company</a>
            <a class="register_button" href="register_applier.php">Register as an Applier</a>

        </form>
    </div>
</body>

</html>