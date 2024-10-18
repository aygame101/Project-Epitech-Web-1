<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/style_form_company.css">
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
            echo '<a class="login" href="#">Login</a>';
        } else if (isset($_SESSION['connected'])) {
            echo '<p class="login" href="google.Fr">Account</p>';
        }
        ?>
    <div class="div_form">
        <h2>Create your Job advertisement here :</h2>

        <form action="../api/post_job_ad.php" method="POST">
                <input type="text" name="company_name" id="company_name" placeholder="Name of the Company" required>

                <input type="text" name="city" id="city" placeholder="City" required>

                <input type="text" name="job" id="job" placeholder="Job title" required>

                <select type="text" name="contract" id="contract" placeholder="Contract Type" required>
                    <option id="cdi" value="CDI">CDI</option>
                    <option id="cdd" value="CDD">CDD</option>
                    <option id="half-time" value="Half-time">Half-time</option>
                    <option id="internship" value="Internship">Internship</option>
                    <option id="apprenticeship" value="Apprenticeship">Apprenticeship</option>
                    <option id="freelance" value="Freelance">Freelance</option>
                </select>

                <input type="text" name="wage" id="wage" placeholder="Wage" maxlength="20" required>

                <input type="text" name="in_charge" id="in_charge" placeholder="Mail person in charge" required>

                <input type="text" name="description_job" id="description_job" placeholder="Job description "required>

                <input type="submit" value="Submit" class="submit">
        </form>
    </div>

    </body>





</html>