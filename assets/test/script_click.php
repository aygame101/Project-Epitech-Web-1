<!DOCTYPE html>
<html lang="en">

<head>
    <script src="../../js/script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $string = "Dans le cadre d'une récente acquisition-fusion, nous avons été chargés de développer une application web pour gérer les données de nos employés
        et clients. Vous devrez donc être capable de travailler en équipe, de communiquer efficacement et de gérer votre
        temps de manière efficace.";
    
    echo '<p id="text_full" style="display: none;">'.$string.'</p>';
    /* if(strlen($string) > 20) $string = substr($string, 0, 30).'...'; */
    $string = substr($string, 0, 30).'...';
    echo '<p id="part_text">'.$string.'</p>';
    ?>
    
    <button onclick="hide_or_show_php()" id="toggle_see">See more</button>

</body>

</html>