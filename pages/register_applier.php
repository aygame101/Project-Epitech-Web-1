<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="../css/no_uderline.css">
    <script src="js/script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J0B F1ND3R</title>
</head>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/">
    <script src="js/script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J0B F1ND3R</title>
</head>
<body>
    <div class="div_titre_acc">
        <a href="../index.php"><h1 class="titre_acc">J0B F1ND3R</h1></a>

        <?php
        session_start();
        if (!isset($_SESSION['connected'])) {
            echo '<a class="login" href="pages/login.php">Login</a>';
        } else if (isset($_SESSION['connected'])) {
            if (isset($_SESSION['company'])){
                echo '<a class="login" href="pages/account_company.php">Account</a>';
            }
            else if  (isset($_SESSION['candidate'])){
                echo '<a class="login" href="pages/account_applier.php">Account</a>';
            }
            else if (isset($_SESSION['admin'])){
                echo '<a class="login" href="pages/admin.php">Account</a>';
        }}
        ?>
        
    <div class="div_form">
        <h2>Please register here</h2>

        <form action="../api/insert_applier.php" method="post">
                <input type="text" name="name" id="name" placeholder="Your name" required>

                <input type="text" name="firstname" id="firstname" placeholder="Your firstname" required>

                <input type="text" name="mail" id="mail" placeholder="Your mail" required>

                <input type="text" name="phone" id="phone" placeholder="Your phone number" required>

                <input type="text" name="password" id="password" placeholder="password" required>

                <button type="register" value="register" class="register">register</button>
        </form>
    </div>

    </body>

</html>