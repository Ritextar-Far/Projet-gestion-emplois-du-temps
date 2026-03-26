<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/modules.css">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Quicksand:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body class="corps-enseignant">
    <?php require_once '../inclus/header.php'; ?>
    <main class="contenu">
        <nav class="fil">
            <a href="index.php"><img src="assets/images/home.svg"></a>
            <span>›</span>
            <a href="type_intervention.php">Modules</a>
        </nav>
        <p class="titre">Modules</p>
        <div class="liste-module">
            <li>Gestion de projet - Méthode Agile (63h)</li>
            <li>Cadre légal - Droit numérique (21h)</li>
                <ul>RGPD</ul>
                <ul>Propriété intellectuelle</ul>
                <ul>RSE</ul>
                <ul>Accessibilité</ul>
        </div>
        <button class="bouton-ajouter">Ajouter un module</button>
    </main>
        
</body>
</html>