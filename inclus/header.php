<?php
$page_actuelle = basename($_SERVER['PHP_SELF']);
?>

<aside class="barre-gauche">
    <div class="header-logo">
        <img src="assets/images/logo.png" alt="Logo établissement" class="logo">
        <div class="text-logo">
            <p class="gros-text">Lycée Saint-Vincent</p>
            <p class="sous-titre">Enseignement Supérieur</p>
        </div>
    </div>

    <nav class="menu-navigation">
        <p class="titre-section">MENU</p>
        <ul>
            <li class="<?= ($page_actuelle == 'calendrier.php') ? 'active' : '' ?>">
                <a href="calendrier.php"><img src="assets/images/calendrier.svg" class="icon-menu">Calendrier</a>
            </li>
            <li class="<?= ($page_actuelle == 'interventions.php') ? 'active' : '' ?>">
                <a href="interventions.php">
                    <img src="assets/images/interventions.svg" class="icon-menu">Interventions</a>
            </li>
            <li class="<?= ($page_actuelle == 'corps_enseignant.php') ? 'active' : '' ?>">
                <a href="corps_enseignant.php"><img src="assets/images/corps_enseignant.svg" class="icon-menu">Corps enseignant</a>
            </li>
        </ul>

        <p class="titre-section">PARAMÉTRAGE</p>
        <ul>
            <li class="<?= ($page_actuelle == 'modules.php') ? 'active' : '' ?>">
                <a href="modules.php">
                <img src="<?= ($page_actuelle == 'modules.php') ? 'assets/images/module-blanc.svg' : 'assets/images/module.svg' ?>" class="icon-menu">
                Modules
                </a>
            </li>
            <li class="<?= ($page_actuelle == 'type_intervention.php') ? 'active' : '' ?>">
                <a href="type_intervention.php"><img src="assets/images/typeintervention.svg" class="icon-menu">Types d'intervention</a>
            </li>
        </ul>
    </nav>

    <div class="profil-utilisateur">
        <img src="assets/images/Stella_Ribas-PDP.png" alt="Avatar" class="avatar">
        <div class="infos-utilisateur">
            <p class="nom">Stella Ribas</p>
            <p class="role">Administrateur</p>
        </div>
    </div>
</aside>
