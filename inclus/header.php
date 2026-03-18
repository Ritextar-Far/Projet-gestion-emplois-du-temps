<?php
// On détecte le nom du fichier actuel pour mettre l'onglet en surbrillance
$page_actuelle = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Licence - Lycée Saint-Vincent</title>
    <link rel="stylesheet" href="header.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>

<div class="page">
    <aside class="barre-gauche">
        <div class="header-logo">
           <img src="Logo_StVincent.png" alt="Logo établissement" class="logo">
            <div class="text-logo">
                <p class="gros-text">Lycée Saint-Vincent</p>
                <p class="sous-titre">Enseignement Supérieur</p>
            </div>
        </div>

        <nav class="menu-navigation">
            <p class="titre-section">MENU</p>
            <ul>
                <li class="<?= ($page_actuelle == 'calendrier.php') ? 'active' : '' ?>">
                    <a href="calendrier.php"><img src="calendrier.png" class="icon-menu">Calendrier</a>
                </li>
                <li class="<?= ($page_actuelle == 'interventions.php') ? 'active' : '' ?>">
                    <a href="interventions.php">
                        <img src="Interventions.png" class="icon-menu">Interventions</a>
                </li>
                <li class="<?= ($page_actuelle == 'enseignants.php') ? 'active' : '' ?>">
                    <a href="enseignants.php"><img src="Corp_enseignant.png" class="icon-menu">Corps enseignant</a>
                </li>
            </ul>

            <p class="titre-section">PARAMÉTRAGE</p>
            <ul>
                <li class="<?= ($page_actuelle == 'modules.php') ? 'active' : '' ?>">
                    <a href="modules.php"><img src="Modules.png" class="icon-menu">Modules</a>
                </li>
                <li class="<?= ($page_actuelle == 'types.php') ? 'active' : '' ?>">
                    <a href="types.php"><img src="Interventions.png" class="icon-menu">Types d'intervention</a>
                </li>
            </ul>
        </nav>

        <div class="profil-utilisateur">
            <img src="Stella_Ribas-PDP.png" alt="Avatar" class="avatar">
            <div class="infos-utilisateur">
                <p class="nom">Stella Ribas</p>
                <p class="role">Administrateur</p>
            </div>
        </div>
    </aside>



    
<main class="contenu-principal">