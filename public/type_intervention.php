<?php
require_once '../fonctions/db.php';
require_once '../database/requetetypeintervention.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Types d'intervention</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/typeintervention.css">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Quicksand:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>

<body class="pageintervention">
<div class="page">
    <?php require_once '../inclus/header.php'; ?>
    <main class="contenu">
        <nav class="fil">
            <a href="index.php"><img src="assets/images/Home.svg"></a>
            <span>›</span>
            <a href="type_intervention.php">Types d'intervention</a>
        </nav>

        <div class="entete-page">
            <p class="titre-principal">Types d'intervention</p>
            <button class="bouton-ajouter" onclick="document.getElementById('modal-ajouter').showModal()">
                Ajouter un type
            </button>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alerte-succes">Le type d'intervention a bien été ajouté.</div>
        <?php endif; ?>
        <?php if (isset($erreur_ajout)): ?>
            <div class="alerte-erreur">⚠️ <?= htmlspecialchars($erreur_ajout) ?></div>
        <?php endif; ?>

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
                        <td><span style="color: <?= htmlspecialchars($type['color']) ?>"><?= htmlspecialchars($type['color']) ?></span></td>
                        <td>
                            <a href="fiche_type.php?id=<?= (int)$type['id'] ?>" class="lien-fiche">
                                Accéder à la fiche
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>

<!-- Modale : Ajouter un type d'intervention -->
<dialog id="modal-ajouter">
    <button class="modal-fermer" type="button" onclick="document.getElementById('modal-ajouter').close()">×</button>

    <div class="modal-entete">
        <div class="modal-icone">+</div>
        <div>
            <h2 class="modal-titre">Ajouter un type d'intervention</h2>
            <p class="modal-sous-titre">Remplissez les informations ci-dessous.</p>
        </div>
    </div>

    <form method="POST" action="">
        <input type="hidden" name="action" value="ajouter">

        <div class="modal-grille">
            <div class="modal-champ">
                <label>Nom - <span class="obligatoire">champ obligatoire</span></label>
                <input type="text" name="nouveau_nom" placeholder="Saisissez le nom">
            </div>
            <div class="modal-champ">
                <label>Code couleur (hex) - <span class="obligatoire">champ obligatoire</span></label>
                <input type="text" name="nouveau_color" placeholder="#2c416e">
            </div>
        </div>

        <div class="modal-champ">
            <label>Description - <span class="obligatoire">champ obligatoire</span></label>
            <textarea name="nouveau_desc" rows="3" placeholder="Saisissez une description"></textarea>
        </div>

        <div class="modal-boutons">
            <button type="button" class="bouton-annuler" onclick="document.getElementById('modal-ajouter').close()">Annuler</button>
            <button type="submit" class="bouton-enregistrer-modal">Enregistrer les informations</button>
        </div>
    </form>
</dialog>
</body>
</html>
