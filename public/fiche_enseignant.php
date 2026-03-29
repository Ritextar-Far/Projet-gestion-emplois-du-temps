<?php
require_once '../fonctions/db.php';
require_once '../database/requeteficheenseignant.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($enseignant['first_name'] . ' ' . $enseignant['last_name']) ?> – Fiche enseignant</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/fiche_enseignant.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="page-fiche">
<div class="page">
    <?php require_once '../inclus/header.php'; ?>
    <main class="contenu">

        <nav class="fil">
            <a href="index.php"><img src="assets/images/Home.svg"></a>
            <span>›</span>
            <a href="corps_enseignant.php">Corps enseignant</a>
            <span>›</span>
            <span><?= htmlspecialchars($enseignant['first_name'] . ' ' . $enseignant['last_name']) ?></span>
            <span>›</span>
            <span>Informations générales</span>
        </nav>

        <?php if (isset($_GET['success'])): ?>
            <div class="alerte-succes">Les informations ont bien été enregistrées.</div>
        <?php endif; ?>
        <?php if (isset($erreur)): ?>
            <div class="alerte-erreur">⚠️ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <h1 class="titre-fiche"><?= htmlspecialchars($enseignant['first_name'] . ' ' . $enseignant['last_name']) ?></h1>

        <?php if (!empty($heures_par_module)): ?>
            <p class="sous-titre-section">Modules enseignés</p>
            <ul class="liste-modules-heures">
                <?php foreach ($heures_par_module as $ligne): ?>
                    <li>
                        <span class="nom-module"><?= htmlspecialchars($ligne['name']) ?></span>
                        <span class="separateur"> : </span>
                        <span class="nb-heures"><?= (int)$ligne['heures'] ?>h00</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="hrcouleur"></div>

        <div class="onglets">
            <button class="onglet actif" onclick="afficherOnglet('infos', this)">Informations générales</button>
            <button class="onglet" onclick="afficherOnglet('interventions', this)">Interventions</button>
        </div>

        <!-- Onglet Informations générales -->
        <div id="onglet-infos" class="contenu-onglet">
            <p class="sous-titre-section">Informations générales</p>

            <form method="POST" action="" id="formulaire-principal">
                <div class="grille-champs">
                    <div class="champ">
                        <label>Nom de famille - <span class="obligatoire">champ obligatoire</span></label>
                        <input type="text" name="nom" value="<?= htmlspecialchars($enseignant['last_name']) ?>">
                    </div>
                    <div class="champ">
                        <label>Prénom - <span class="obligatoire">champ obligatoire</span></label>
                        <input type="text" name="prenom" value="<?= htmlspecialchars($enseignant['first_name']) ?>">
                    </div>
                    <div class="champ">
                        <label>Email - <span class="obligatoire">champ obligatoire</span></label>
                        <input type="text" name="email" value="<?= htmlspecialchars($enseignant['email']) ?>">
                    </div>
                </div>

                <div class="champ">
                    <label>Modules enseignés - <span class="obligatoire">champ obligatoire</span></label>
                    <div class="multiselect" id="multiselect">
                        <div class="tags" id="tags">
                            <?php foreach ($modules_enseignant as $m): ?>
                                <span class="tag" data-id="<?= $m['id'] ?>">
                                    <?= htmlspecialchars($m['name']) ?>
                                    <button type="button" onclick="retirerTag(this)">×</button>
                                    <input type="hidden" name="modules[]" value="<?= $m['id'] ?>">
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <select id="select-modules" onchange="ajouterTag(this)">
                            <option value="">— Ajouter un module —</option>
                            <?php foreach ($tous_modules as $m): ?>
                                <option value="<?= $m['id'] ?>"
                                    data-nom="<?= htmlspecialchars($m['name']) ?>"
                                    <?= in_array($m['id'], $ids_modules_enseignant) ? 'data-selected="1"' : '' ?>>
                                    <?= htmlspecialchars($m['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="boutons">
                    <button type="submit" name="enregistrer" class="bouton-enregistrer">Enregistrer les informations</button>
                </div>
            </form>
        </div>

        <!-- Onglet Interventions -->
        <div id="onglet-interventions" class="contenu-onglet" style="display:none;">
            <p class="sous-titre-section">Interventions</p>
            <p style="color:#888; font-style:italic; margin-top:16px;">Aucune intervention enregistrée.</p>
        </div>

    </main>
</div>

<script>
function afficherOnglet(nom, btn) {
    document.querySelectorAll('.contenu-onglet').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.onglet').forEach(el => el.classList.remove('actif'));
    document.getElementById('onglet-' + nom).style.display = 'block';
    btn.classList.add('actif');
}

function ajouterTag(select) {
    const id  = select.value;
    const nom = select.options[select.selectedIndex].dataset.nom;
    if (!id) return;

    if (document.querySelector(`#tags .tag[data-id="${id}"]`)) {
        select.value = '';
        return;
    }

    const tag = document.createElement('span');
    tag.className = 'tag';
    tag.dataset.id = id;
    tag.innerHTML = `${nom} <button type="button" onclick="retirerTag(this)">×</button><input type="hidden" name="modules[]" value="${id}">`;
    document.getElementById('tags').appendChild(tag);
    select.value = '';
}

function retirerTag(btn) {
    btn.closest('.tag').remove();
}
</script>
</body>
</html>
