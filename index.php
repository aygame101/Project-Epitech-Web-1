<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="css/style_index.css">
    <script src="js/script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
                    // Remplir le menu déroulant des titres de poste
                    $.get("http://localhost:8000/job_titles", function(data) {
                        var select = $("#job_title");
                        $.each(data.job_titles, function(index, value) {
                            select.append($('<option></option>').val(value).html(value));
                        });
                    });

                    // Remplir le menu déroulant des types de contrat
                    $.get("http://localhost:8000/contract_types", function(data) {
                        var select = $("#contract_type");
                        $.each(data.contract_types, function(index, value) {
                            select.append($('<option></option>').val(value).html(value));
                        });
                    });

                    // Remplir le menu déroulant des localisations
                    $.get("http://localhost:8000/locations", function(data) {
                        var select = $("#location");
                        $.each(data.locations, function(index, value) {
                            select.append($('<option></option>').val(value).html(value));
                        });
                    });

                    // Gérer la soumission du formulaire
                    $("form").submit(function(event) {
                        event.preventDefault();
                        var jobTitle = $("#job_title").val() === '*' ? '' : $("#job_title").val();
                        var contractType = $("#contract_type").val() === '*' ? '' : $("#contract_type").val();
                        var location = $("#location").val() === '*' ? '' : $("#location").val();

                        // Rediriger vers la page de résultats avec les paramètres de recherche
                        window.location.href = 'pages/offers.php?job_title=' + encodeURIComponent(jobTitle) +
                            '&contract_type=' + encodeURIComponent(contractType) +
                            '&location=' + encodeURIComponent(location);
                    });
                });
    </script>
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
            <select id="job_title" name="job_title">
                <option selected="true" disabled="true" value="">Job Title</option>
                <option id="all" value="*">*ALL*</option>
            </select>

            <select id="contract_type" name="contract_type">
                <option selected="true" disabled="true" value="">Contract Type</option>
                <option id="all" value="*">*ALL*</option>
            </select>

            <select id="location" name="location">
                <option selected="true" disabled="true" value="">Location</option>
                <option id="all" value="*">*ALL*</option>
            </select>

            <input type="submit" value="Search" />
        </form>

        <h3><a href="pages/form_company.html">Post job offer</a></h3>

    </div>
</body>

</html>


<!--
session_start();
$_SESSION['connected'] = true;
-->