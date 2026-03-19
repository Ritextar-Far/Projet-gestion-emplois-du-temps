<?php
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion au portail</title>
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/typeintervention.css">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Quicksand:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>
<body class="pageintervention">
    <div class="page">
        <?php require_once '../../inclus/header.php'; ?>
        <main class="contenu">
            <p class="titre-principal">Types d'intervention</p>
            <section class="bloc-filtre">
                <h3 class="titre-filtre">Filtres</h3>
                <form class="formulaire-filtre" method="POST" action="">
                    <div class="champ">
                        <label>Nom</label>
                        <input type="text" name="nom" placeholder="Saisissez le nom">
                    </div>
                    <button type="submit" class="bouton-filtre">Filtrer</button>
                    <br>
                </form>
            </section>
            <div class="hrcouleur"></div>
            <h3>5 types</h3>
            <table class="table">
                <tr>
                    <th>Nom</th>
                    <th>Descriptif</th>
                    <th>Couleur</th>
                    <th>Fiche</th>
                </tr>
            </table>
        </main>
    </div>
</body>
</html>
