<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/">
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
                echo '<a class="login" href="pages/account_company.php">Account</a>';
            }
            else if  (isset($_SESSION['candidate'])){
                echo '<a class="login" href="pages/account_applier.php">Account</a>';
            }
        }
        ?>

    <div class="div_form">
        <h2>Please register here</h2>

        <form action="../api/insert_comp.php" method="post">
                <input type="text" name="name" id="name" placeholder="Name of the Company" required>

                <input type="text" name="password" id="password" placeholder="password" required>

                <button type="register" value="register" class="register">register</button>
        </form>
    </div>

    </body>





</html>