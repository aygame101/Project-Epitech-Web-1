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
        <a href="../index.php"><h1 class="titre_acc">J0B F1ND3R</h1></a>

        <?php
        session_start();
        if (!isset($_SESSION['connected'])) {
            echo '<a class="login" href="pages/login.php">Login</a>';
        } else if (isset($_SESSION['connected'])) {
            if (isset($_SESSION['company'])){
                echo '<a class="login" href="account_company.php">Account</a>';
            }
            else if  (isset($_SESSION['candidate'])){
                echo '<a class="login" href="account_applier.php">Account</a>';
            }
        }
        ?>

    <div class="div_form">
        <form action="" method="get">
            <div>
                <label for="name">Your name : </label>
                <input type="text" id="name" name="name" required>
            </div>

            <div>
                <label for="firstname">Your first name : </label>
                <input type="text" id="fname" name="fname" required>
            </div>

            <div>
                <label for="phone">Your phone number : </label>
                <input  type="tel" maxlength="20" id="phone" name="phone" required>
            </div>

            <div>
                <label for="mail">Your  email : </label>
                <input  type="email" id="mail" name="mail" required>
            </div>

            <input class="valider" type="submit" value="Valider"/>
        </form>
    </body>
</html>
