<?php
session_start();

$name = $firstname = $phone = $mail = '';

// verif si un utilisateur est connecté
if (isset($_SESSION['connected']) && isset($_SESSION['candidate']) && isset($_SESSION['user_id'])) {
    $conn = mysqli_connect("localhost", "root", "", "test");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT name, firstname, phone, mail FROM people WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $name = $row['name'];
        $firstname = $row['firstname'];
        $phone = $row['phone'];
        $mail = $row['mail'];
    }

    mysqli_close($conn);
}
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
            echo '<a class="login" href="pages/login.php">Login</a>';
        } else if (isset($_SESSION['connected'])) {
            if (isset($_SESSION['company'])) {
                echo '<a class="login" href="pages/account_company.php">Account</a>';
            } else if (isset($_SESSION['candidate'])) {
                echo '<a class="login" href="pages/account_applier.php">Account</a>';
            } else if (isset($_SESSION['admin'])) {
                echo '<a class="login" href="pages/admin.php">Account</a>';
            }
        }
        ?>
    </div>

    <div class="div_form">
        <form action="" method="get">
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

            <input class="valider" type="submit" value="Valider" />
        </form>
    </div>
</body>

</html>