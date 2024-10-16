<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="css/style_index.css">
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
            echo '<a class="login" href="#">Login / Register</a>';
        } else if (isset($_SESSION['connected'])) {
            echo '<p class="login" href="google.Fr">Account</p>';
        }
        ?>
    </div>

    <div class="div_search">
        <h2>What are you looking for ?</h2>


    <form action="" method="get">

        <!-- <label for="job_title">Job Title :</label> -->
        <select id="job_title" name="job_title">
            <option selected="true" disabled="true" >Job Title</option>

            <!-- recup les données dans la BDD -->
        </select>

        <!-- <label for="contract_type">Contract Type :</label> -->
        <select id="contract_type" name="contract_type">
            <option selected="true" disabled="true" >Contract Type</option>

            <!-- recup les données dans la BDD -->
        </select>

        <!-- <label for="location">Location :</label> -->
        <select id="location" name="location">
            <option selected="true" disabled="true" >Location</option>

            <!-- recup les données dans la BDD -->
        </select>

        <input type="submit" value="Search"/>
    </form>

        <h3><a href="pages/form_company.html">Post job offer</a></h3>

    </div>
</body>
</html>

<!--
session_start();
$_SESSION['connected'] = true;
-->