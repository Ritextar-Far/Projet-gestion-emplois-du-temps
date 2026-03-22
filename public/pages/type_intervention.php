<?php
require_once '../../fonctions/db.php';
require_once '../../database/requetetypeintervention.php'

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Types d'intervention</title>
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/typeintervention.css">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Quicksand:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>

<body class="pageintervention">
<div class="page">
    <?php require_once '../../inclus/header.php'; ?>
    <main class="contenu">

        <div class="entete-page">
            <p class="titre-principal">Types d'intervention</p>
            <a href="ajouter_type.php" class="bouton-ajouter">Ajouter un type</a>
        </div>

        <section class="bloc-filtre">
            <h3 class="titre-filtre">Filtres</h3>
            <form class="formulaire-filtre" method="POST" action="">
                <div class="champ">
                    <label>Nom</label>
                    <input type="text" name="nom" placeholder="Saisissez le nom"
                           value="<?= htmlspecialchars($filtre_nom) ?>">
                </div>
                <button type="submit" class="bouton-filtre">Filtrer</button>
            </form>
        </section>

        <div class="hrcouleur"></div>

        <h3><?= $nb_types ?> type<?= $nb_types > 1 ? 's' : '' ?></h3>

        <table class="table">
            <thead>
            <tr>
                <th>Nom</th>
                <th>Descriptif</th>
                <th>Couleur</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if ($nb_types === 0): ?>
                <tr>
                    <td colspan="4" class="aucun-resultat">Aucun type trouvé.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($types as $type): ?>
                    <tr>
                        <td><?= htmlspecialchars($type['name']) ?></td>
                        <td><?= htmlspecialchars($type['description']) ?></td>
                        <td>
                                    <span class="pastille-couleur"
                                          style="background-color: <?= htmlspecialchars($type['color']) ?>"></span>
                            <span style="color: <?= htmlspecialchars($type['color']) ?>">
                                        <?= htmlspecialchars($type['color']) ?>
                                    </span>
                        </td>
                        <td>
                            <a href="fiche_type.php?id=<?= (int)$type['id'] ?>" class="lien-fiche">
                                👁 Accéder à la fiche
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

    </main>
</div>
</body>
</html>
