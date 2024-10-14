<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="style_index.css">
    <script src="js/script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J0B F1ND3R</title>
</head>

<body>
    <div class="div_titre_acc">
        <h1 class="titre_acc">J0B F1ND3R</h1>

        <?php
        session_start();
        if (!isset($_SESSION['connected'])) {
            echo '<a class="login" href="login.php">Login</a>';
        } else if (isset($_SESSION['connected'])) {
            echo '<p class="login">Account</p>';
        }
        ?>
    </div>

    <div class="way">
        <h2>who are you ?</h2>

        <div class="button">
            <button>A company</button>
            <button>An applier</button>
        </div>
    </div>
</body>

</html>

<!--
session_start();
$_SESSION['connected'] = true;
-->