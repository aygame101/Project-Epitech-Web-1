<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../css/style_form_company.css">
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
        $conn = mysqli_connect("localhost", "root", "", "test");

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "SELECT name FROM companies ORDER BY name";
        $result = mysqli_query($conn, $sql);

        $companies = array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $companies[] = $row['name'];
            }
        }

        mysqli_close($conn);
        ?>
        <?php
        session_start();
        if (!isset($_SESSION['connected'])) {
            echo '<a class="login" href="login.php">Login</a>';
        } else if (isset($_SESSION['connected'])) {
            if (isset($_SESSION['company'])) {
                echo '<a class="login" href="account_company.php">Account</a>';
            } else if (isset($_SESSION['candidate'])) {
                echo '<a class="login" href="account_applier.php">Account</a>';
            } else if (isset($_SESSION['admin'])) {
                echo '<a class="login" href="admin.php">Account</a>';
            }
        }
        ?>

        <div class="div_form">
            <h2>Create your Job advertisement here :</h2>

            <form action="../api/post_job_ad.php" method="POST" id="jobAdForm">
                <select name="company_name" id="company_name" required onchange="checkNewCompany(this)">
                    <option value="" disabled selected>Select a company</option>
                    <option value="new_company">Add a new company</option>
                </select>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        fetch('http://localhost:8000/companies')
                            .then(response => response.json())
                            .then(data => {
                                const select = document.getElementById('company_name');
                                data.companies.forEach(company => {
                                    const option = document.createElement('option');
                                    option.value = company.name;
                                    option.textContent = company.name;
                                    select.insertBefore(option, select.lastElementChild);
                                });
                            })
                            .catch(error => console.error('Error:', error));
                    });

                    function checkNewCompany(select) {
                        if (select.value === 'new_company') {
                            window.location.href = 'register_comp.php';
                        }
                    }
                </script>

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

                <textarea type="text" name="description_job" id="description_job" placeholder="Job description " required></textarea>
                <!-- textarea pour garder les saut de ligne-->
                <input type="submit" value="Submit" class="submit">
            </form>
            <script>
                function checkNewCompany(select) {
                    if (select.value === 'new_company') {
                        window.location.href = 'register_comp.php';
                    }
                }
            </script>
        </div>

</body>





</html>