<?php
session_start();


$conn = new mysqli("localhost", "root", "", "test");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
   
    $sql_applier = "SELECT * FROM people WHERE mail = ?";
    $sql_company = "SELECT * FROM companies WHERE name = ?";
    
    $stmt_applier = $conn->prepare($sql_applier);
    $stmt_applier->bind_param("s", $email);
    $stmt_applier->execute();
    $result_applier = $stmt_applier->get_result();
    
    $stmt_company = $conn->prepare($sql_company);
    $stmt_company->bind_param("s", $email);
    $stmt_company->execute();
    $result_company = $stmt_company->get_result();
    
    if ($result_applier->num_rows == 1) {
        $row = $result_applier->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_type'] = 'applier';
            header("Location: form_applyers.html");
        } else {
            $error = "Invalid email or password";
        }
    } elseif ($result_company->num_rows == 1) {
        $row = $result_company->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_type'] = 'company';
            header("Location: form_company.html");
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
    <link rel="stylesheet" href="../css/">
    <script src="js/script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J0B F1ND3R - Login</title>
</head>
<body>
    <div class="div_titre_acc">
        <h1 class="titre_acc">J0B F1ND3R</h1>
    </div>
    <div class="div_form">
        <h2>Please login here</h2>

        <?php if(isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="email" id="email" placeholder="Your email or company name" required>

            <input type="password" name="password" id="password" placeholder="Password" required>

            <button type="submit" value="login" class="login">Login</button>
        </form>
    </div>
</body>
</html>