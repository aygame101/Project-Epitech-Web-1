<?php
session_start();


$conn = new mysqli("localhost", "root", "", "test");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type_person'];
    
    if ($type == 'company') {
        $sql = "SELECT * FROM companies WHERE name = ?";
    } else {
        $sql = "SELECT * FROM people WHERE mail = ?";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_type'] = $type;
            $conn->close();
            $_SESSION['connected'] = true;
            if ($type == 'company') {
                header("Location: form_company.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/st_connexion.html.css">
    <script src="../js/script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J0B F1ND3R - Login</title>
</head>
<body>
    <div class="div_titre_acc">
        <h1 class="titre_acc">J0B F1ND3R</h1>
    </div>

    <div class="div_form">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <select id="type_person" name="type_person" required>
            <option value="" selected disabled>Are you a company or an applier</option>
            <option value="company">Company</option>
            <option value="applier">Applier</option>
        </select>

        <?php if($error) { echo "<p style='color: red;'>$error</p>"; } ?>

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