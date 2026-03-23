<?php
require_once '../fonctions/db.php';
require_once '../database/requetefichetype.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($type['name']) ?> – Fiche type</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/fiche_type.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="page-fiche">
<div class="page">
    <?php require_once '../inclus/header.php'; ?>
    <main class="contenu">

        <nav class="fil">
            <a href="../index.php"><img src="assets/images/Home.svg"></a>
            <span>›</span>
            <a href="type_intervention.php">Types d'intervention</a>
            <span>›</span>
            <span><?= htmlspecialchars($type['name']) ?></span>
        </nav>

        <?php if (isset($_GET['success'])): ?>
            <div class="alerte-succes">Les informations ont bien été enregistrées.</div>
        <?php endif; ?>

        <?php if (isset($erreur)): ?>
            <div class="alerte-erreur">⚠️ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <h1 class="titre-fiche"><?= htmlspecialchars($type['name']) ?></h1>

        <form method="POST" action="" id="formulaire-principal">
            <div class="grille-champs">

                <div class="champ">
                    <label>Nom - <span class="obligatoire">champ obligatoire</span></label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($_GET['nom'] ?? $type['name']) ?>">
                </div>

                <div class="champ">
                    <label>Code couleur (hexadécimal) - <span class="obligatoire">champ obligatoire</span></label>
                    <div class="champ-couleur">
                        <input type="text" name="color" value="<?= htmlspecialchars($_GET['color'] ?? $type['color']) ?>">
                    </div>
                </div>

            </div>

            <div class="champ">
                <label>Description - <span class="obligatoire">champ obligatoire</span></label>
                <textarea name="description" rows="4"><?= htmlspecialchars($_GET['description'] ?? $type['description']) ?></textarea>
            </div>

            <div class="boutons">
                <a href="type_intervention.php" class="bouton-retour">Retour à la liste</a>
                <button type="button" class="bouton-supprimer" onclick="document.getElementById('modal-supprimer').showModal()">Supprimer</button>
                <button type="submit" name="enregistrer" class="bouton-enregistrer">Enregistrer les informations</button>
            </div>
        </form>
        <dialog id="modal-supprimer">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h1>Supprimer le type d’intervention</h1>
            <h2>Confirmation de l’action</h2>

            <p class="modal-avertissement">Vous vous apprêtez à supprimer le type d’intervention, <br>
                cette action est irrévoquable. <br>
                A noter qu’aucune intervention ne doit être liée à ce module pour pouvoir le <br>
                supprimer.</p>
            <p class="modal-avertissement-confirm">Confirmez-vous l’action ?</p>
            <div class="modal-boutons">
                <button type="button" class="bouton-supprimer-annuler" onclick="document.getElementById('modal-supprimer').close()">Annuler</button>
                <button type="submit" name="supprimer" form="formulaire-principal" class="bouton-supprimer-confirmer">Confirmer</button>
            </div>
        </dialog>

    </main>
</div>
</body>
</html>
