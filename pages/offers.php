<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../css/">
    <script src="../JS/script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J0B F1ND3R</title>
</head>

<body>
    <div>
        <div class="">
            <h1 class="">J0B F1ND3R</h1>
        </div>

        <div>
            <h1>Data Analyst</h1>
            <h2>Ubisoft</h2>
            <h3>Paris, 15eme</h3>
            <h4>CDI</h4>
            <h5>50 000/an</h5>
        </div>

        <div>
            <?php
                $string = "Description du poste : Nous recherchons un(e) Data Engineer talentueux(se) et passionne(e) pour rejoindre notre equipe Data & Analytics. Vous serez charge(e) de concevoir, construire et maintenir l'infrastructure de traitement des donnees necessaire pour soutenir nos initiatives data-driven.  
                En tant que Data Engineer, vous travaillerez en etroite collaboration avec les Data Scientists, 
                les equipes de developpement et les analystes pour garantir que les donnees sont accessibles, fiables et optimisees pour les analyses et prises de decisions.  
                Responsabilites principales :  
                * Concevoir, developper et maintenir des pipelines de donnees robustes et evolutifs.  
                * Integrer et transformer des donnees provenant de sources multiples (bases de donnees SQL/NoSQL, API, fichiers plats, etc.).  
                * Optimiser les performances des bases de donnees et des traitements de donnees.  
                * Implementer des solutions de stockage de donnees modernes (data lakes, data warehouses).  
                * Assurer la qualite des donnees (verification, nettoyage, validation).  
                * Travailler en collaboration avec les equipes produit et IT pour repondre aux besoins metiers.  
                * Mettre en place des solutions de monitoring et de securite pour garantir la disponibilite et l'integrite des donnees.  ";

                echo '<p id="text_full" style="display: none;">' . $string . '</p>';
                /* if(strlen($string) > 20) $string = substr($string, 0, 30).'...'; */
                $string = substr($string, 0, 30) . '...';
             echo '<p id="part_text">' . $string . '</p>';
            ?>
            <button onclick="hide_or_show()" id="toggle_see">See more</button>
        </div>

        <div>            
            <button>Apply</button>
        </div>
    </div>

    <div>
        <div>
            <h1>Consultant Cybersécurité</h1>
            <h2>Safran</h2>
            <h3>Villaroche</h3>
            <h4>CDI</h4>
            <h5>52 000/an</h5>
        </div>

        <div>
            <?php
                $string = "Description du poste :  
Nous sommes a la recherche d'un(e) Consultant(e) en Cybersecurite pour renforcer notre equipe et accompagner nos clients dans la protection de leurs systemes d'information. Vous jouerez un role cle dans l'evaluation, la mise en oeuvre et la gestion des strategies de securite des entreprises clientes afin de prevenir les cyberattaques et d'ameliorer leur resilience face aux menaces numeriques.  
Vous travaillerez en etroite collaboration avec les equipes techniques et manageriales pour offrir des solutions sur mesure, allant de l'audit a la conception de plans d'amelioration en passant par la reponse aux incidents.  

Responsabilites principales :  
* Realiser des audits de securite informatique (tests d'intrusion, analyses de vulnerabilites, etc.).  
* Developper et mettre en oeuvre des strategies de cybersecurite adaptees aux besoins des clients.  
* Proposer des solutions pour securiser les systemes, reseaux et applications.  
* Assurer la conformite des systemes d'information avec les normes et reglementations en vigueur (ISO 27001, RGPD, etc.).  
* Participer a la gestion des incidents de securite et a l'investigation des attaques.  
* Conseiller les clients sur les bonnes pratiques et les outils de securite (SIEM, pare-feu, IDS/IPS, etc.).  
* Assurer une veille technologique sur les nouvelles menaces et solutions en cybersecurite.  
* Former et sensibiliser les equipes des clients aux enjeux de la cybersecurite.";

                echo '<p id="text_full" style="display: none;">' . $string . '</p>';
                /* if(strlen($string) > 20) $string = substr($string, 0, 30).'...'; */
             $string = substr($string, 0, 30) . '...';
             echo '<p id="part_text">' . $string . '</p>';
            ?>
            <button onclick="hide_or_show()" id="toggle_see">See more</button>
        </div>

        <div>          
            <button>Apply</button>
        </div>
    </div>

    <div>
        <div>
            <h1>Architecte Réseaux</h1>
            <h2>Firefly Inc</h2>
            <h3>La Défense</h3>
            <h4>CDD de 18 mois</h4>
            <h5>2651/ mois</h5>
        </div>

        <div>
            <?php
                $string = "Description du poste :  
Nous recherchons un(e) Architecte des Systemes d'Information experimente(e) pour rejoindre notre equipe et concevoir des solutions technologiques innovantes adaptees a nos besoins metiers. Vous serez responsable de la structuration et de l'optimisation de notre architecture informatique, en assurant sa coherence avec la strategie de l'entreprise.  
En tant qu'Architecte SI, vous jouerez un role cle dans la transformation numerique, en garantissant l'integration des nouvelles technologies, la securite, la performance et l'evolutivite des systemes.  

Responsabilites principales :  
* Concevoir et formaliser l'architecture globale du systeme d'information en accord avec les objectifs strategiques de l'entreprise.  
* Assurer la coherence entre les differentes briques technologiques (applications, reseaux, donnees, etc.) et leur interopérabilité.  
* Elaborer des solutions innovantes pour repondre aux besoins metiers tout en garantissant la robustesse, la scalabilite et la securite des systemes.  
* Collaborer etroitement avec les equipes de developpement, les chefs de projet et les experts metier pour piloter l'implementation de nouvelles solutions.  
* Veiller a l'alignement des architectures avec les meilleures pratiques (TOGAF, ITIL, etc.) et les normes de securite.  
* Participer a la definition des politiques d'urbanisation du SI et a la gestion du cycle de vie des applications.  
* Realiser des etudes de faisabilite technique et evaluer les impacts des projets sur l'architecture existante.  
* Assurer une veille technologique continue pour anticiper les evolutions du marche et des besoins.";

                echo '<p id="text_full" style="display: none;">' . $string . '</p>';
                /* if(strlen($string) > 20) $string = substr($string, 0, 30).'...'; */
             $string = substr($string, 0, 30) . '...';
             echo '<p id="part_text">' . $string . '</p>';
            ?>
            <button onclick="hide_or_show()" id="toggle_see">See more</button>
        </div>
        

        <div>
           <button>Apply</button>
        </div>
    </div>

    <div>
        <div>
            <h1>Analyste SOC</h1>
            <h2>AMD</h2>
            <h3>Levalois-Perret</h3>
            <h4>Alternance</h4>
            <h5>à définir selon l'age du candidat</h5>
        </div>

       
        <div>
            <?php
                $string = "Description du poste :  
Nous recherchons un(e) Analyste SOC (Security Operations Center) pour integrer notre equipe de securite informatique. En tant qu'Analyste SOC, vous serez responsable de la detection, de l'analyse et de la reponse aux incidents de securite en temps reel. Vous jouerez un role essentiel dans la protection des systemes d'information et des donnees de nos clients contre les menaces cybernetiques.  
Vous travaillerez dans un environnement dynamique et serez expose(e) a une grande variete de menaces, ce qui vous permettra de developper vos competences en matiere de cybersecurite.  

Responsabilites principales :  
* Surveiller les systemes de securite (SIEM, IDS/IPS) pour detecter les menaces et les incidents en temps reel.  
* Analyser les alertes de securite et evaluer leur impact sur les systemes.  
* Reagir rapidement aux incidents de securite en suivant les procedures definies (containment, eradication, recovery).  
* Effectuer des enquetes approfondies sur les incidents de securite, y compris l'analyse des logs et la detection des comportements anormaux.  
* Mettre a jour et maintenir les indicateurs de compromission (IoC) et les regles de detection.  
* Assurer une gestion efficace des incidents de securite, y compris la documentation, l'escalade et la communication avec les parties prenantes.  
* Collaborer avec les equipes de securite, infrastructure et developpement pour renforcer la posture de securite.  
* Participer a des exercices de simulation d'incidents (cyber drills) et aux plans d'amelioration continue. ";

                echo '<p id="text_full" style="display: none;">' . $string . '</p>';
                /* if(strlen($string) > 20) $string = substr($string, 0, 30).'...'; */
             $string = substr($string, 0, 30) . '...';
             echo '<p id="part_text">' . $string . '</p>';
            ?>
            <button onclick="hide_or_show()" id="toggle_see">See more</button>
        </div>
       

        <div>         
            <button>Apply</button>
        </div>
    </div>

    <div>
        <h1>Chef de projet Web</h1>
        <h2>Magic Castle</h2>
        <h3>Versailles</h3>
        <h4>CDI</h4>
        <h5>25 000/an</h5>
    </div>

    <div>
            <?php
                $string = "Description du poste :  
Nous recherchons un(e) Chef de Projet Web dynamique et motive(e) pour rejoindre notre equipe digitale. Vous serez responsable de la gestion et du suivi des projets web, depuis la phase de conception jusqu'a la mise en ligne. En tant que Chef de Projet Web, vous coordonnerez les equipes techniques, creatives et marketing afin de garantir la livraison de projets de qualite respectant les delais et les budgets.  
Votre objectif sera d'assurer la reussite des projets web en pilotant les differentes etapes de realisation (conception, developpement, tests, deploiement) et en etant l'interlocuteur privilegie des parties prenantes.  

Responsabilites principales :  
* Gerer l'ensemble du cycle de vie des projets web (sites web, applications, plateformes e-commerce).  
* Coordonner les equipes internes (developpeurs, designers, UX/UI, marketing) et externes (prestataires, clients).  
* Recueillir les besoins des clients et definir les objectifs, les livrables et les echeances.  
* Rediger des cahiers des charges fonctionnels et techniques en lien avec les equipes concernees.  
* Assurer le respect des budgets et des delais tout en garantissant la qualite des livrables.  
* Superviser le developpement et l'integration des solutions techniques (CMS, frameworks, API).  
* Effectuer des tests fonctionnels et s'assurer du bon deroulement des phases de validation et de mise en ligne.  
* Veiller a l'optimisation continue des projets web apres leur lancement (maintenance, SEO, analyse de performance).  
* Assurer une veille technologique pour proposer des solutions innovantes et adaptees aux besoins metiers.";

                echo '<p id="text_full" style="display: none;">' . $string . '</p>';
                /* if(strlen($string) > 20) $string = substr($string, 0, 30).'...'; */
             $string = substr($string, 0, 30) . '...';
             echo '<p id="part_text">' . $string . '</p>';
            ?>
            <button onclick="hide_or_show()" id="toggle_see">See more</button>
        </div>

        <div>     
            <button>Apply</button>
        </div>
    </div>
</body>

</html>