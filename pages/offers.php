<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - J0B F1ND3R</title>
    <link rel="stylesheet" href="">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../JS/script.js"></script>
</head>

<body>
    <div class="div_titre_acc">
        <h1 class="titre_acc">J0B F1ND3R - Search Results</h1>
        <a href="../index.php" class="back-link">Back to Search</a>
    </div>

    <div id="job-container">
        <!-- Les annonces seront affichées ici -->
    </div>

    <div id="navigation">
        <button id="prev">Previous</button>
        <button id="next">Next</button>
    </div>

    <script>
        $(document).ready(function() {
            var jobAds = [];
            var currentIndex = 0;

            // Fonction pour afficher une annonce
            function displayJob(job) {
                var jobHtml = '<h1>' + job.job_title + ' at ' + job.company_name + '</h1>' +
                    '<h2>' + job.city + '</h2>' +
                    '<h3>' + job.contract_type + '</h3>' +
                    '<h4>' + job.wage + '</h4>' +
                    '<p id="part_text">' + job.description.substr(0,200) + '...' + '</p>' +
                    '<p id="text_full" style="display: none;">' + job.description + '</p>' +
                    '<button onclick="hide_or_show()" id="toggle_see">See more</button>' +
                    '<a href="form_applyers.html"><button>apply</button></a>';
                $('#job-container').html(jobHtml);
            }

            // Fonction pour mettre à jour les boutons de navigation
            function updateNavigation() {
                $('#prev').prop('disabled', currentIndex === 0);
                $('#next').prop('disabled', currentIndex === jobAds.length - 1);
            }

            // Charger les résultats de recherche
            $.get("http://localhost:8000/search", {
                job_title: "<?php echo $_GET['job_title'] ?? ''; ?>",
                contract_type: "<?php echo $_GET['contract_type'] ?? ''; ?>",
                location: "<?php echo $_GET['location'] ?? ''; ?>"
            }, function(data) {
                jobAds = data.job_ads;
                if (jobAds.length > 0) {
                    displayJob(jobAds[0]);
                    updateNavigation();
                } else {
                    $('#job-container').html('<p>No results found.</p>');
                    $('#navigation').hide();
                }
            });

            // Gérer la navigation
            $('#prev').click(function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    displayJob(jobAds[currentIndex]);
                    updateNavigation();
                }
            });

            $('#next').click(function() {
                if (currentIndex < jobAds.length - 1) {
                    currentIndex++;
                    displayJob(jobAds[currentIndex]);
                    updateNavigation();
                }
            });
        });
    </script>
</body>

</html>