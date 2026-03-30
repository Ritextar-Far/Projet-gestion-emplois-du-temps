<?php
require_once '../fonctions/db.php';
require_once '../database/requetecorpsenseignant.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Corps enseignant</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/corps_enseignant.css">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Quicksand:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>

<body class="corps-enseignant">
<div class="page">
    <?php require_once '../inclus/header.php'; ?>
    <main class="contenu">
        <nav class="fil">
            <a href="index.php"><img src="assets/images/Home.svg"></a>
            <span>›</span>
            <a href="corps_enseignant.php">Corps enseignant</a>
        </nav>

        <div class="entete-page">
            <p class="titre-principal">Corps enseignant</p>
            <button class="bouton-ajouter" onclick="document.getElementById('modal-ajouter').showModal()">
                Ajouter un enseignant
            </button>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alerte-succes">L'enseignant a bien été ajouté.</div>
        <?php endif; ?>
        <?php if (isset($erreur_ajout)): ?>
            <div class="alerte-erreur">⚠️ <?= htmlspecialchars($erreur_ajout) ?></div>
        <?php endif; ?>

        <section class="bloc-filtre">
            <h3 class="titre-filtre">Filtres</h3>
            <form class="formulaire-filtre" method="POST" action="">
                <div class="champ">
                    <label>Nom de famille</label>
                    <input type="text" name="nom" placeholder="Saisissez le nom de famille"
                           value="<?= htmlspecialchars($filtre_nom) ?>">
                </div>
                <div class="champ">
                    <label>Prénom</label>
                    <input type="text" name="prenom" placeholder="Saisissez le prénom"
                           value="<?= htmlspecialchars($filtre_prenom) ?>">
                </div>
                <div class="champ">
                    <label>Email</label>
                    <input type="text" name="email" placeholder="Saisissez l'email"
                           value="<?= htmlspecialchars($filtre_email) ?>">
                </div>
                <button type="submit" class="bouton-filtre">Filtrer</button>
            </form>
        </section>

        <div class="hrcouleur"></div>

        <h3><?= $nb_enseignants ?> enseignant<?= $nb_enseignants > 1 ? 's' : '' ?> trouvé<?= $nb_enseignants > 1 ? 's' : '' ?></h3>

        <table class="table">
            <thead>
            <tr>
                <th>Nom de famille</th>
                <th>Prénom</th>
                <th>Modules enseignés</th>
                <th>Nombre d'heures</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if ($nb_enseignants === 0): ?>
                <tr>
                    <td colspan="5" class="aucun-resultat">Aucun enseignant trouvé.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($enseignants as $enseignant): ?>
                    <tr>
                        <td><?= htmlspecialchars($enseignant['last_name']) ?></td>
                        <td><?= htmlspecialchars($enseignant['first_name']) ?></td>
                        <td><?= htmlspecialchars($enseignant['modules'] ?? '—') ?></td>
                        <td><?= (int)$enseignant['total_heures'] ?>h</td>
                        <td>
                            <a href="fiche_enseignant.php?id=<?= (int)$enseignant['id'] ?>" class="lien-fiche">
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

<!-- Modale : Ajouter un enseignant -->
<dialog id="modal-ajouter">
    <button class="modal-fermer" type="button" onclick="document.getElementById('modal-ajouter').close()">×</button>

    <div class="modal-entete">
        <div class="modal-icone">+</div>
        <div>
            <h2 class="modal-titre">Ajouter un enseignant</h2>
            <p class="modal-sous-titre">Remplissez les informations ci-dessous.</p>
        </div>
    </div>

    <form method="POST" action="">
        <input type="hidden" name="action" value="ajouter">

        <div class="modal-grille">
            <div class="modal-champ">
                <label>Nom de famille - <span class="obligatoire">champ obligatoire</span></label>
                <input type="text" name="nouveau_nom" placeholder="Saisissez le nom de famille">
            </div>
            <div class="modal-champ">
                <label>Prénom - <span class="obligatoire">champ obligatoire</span></label>
                <input type="text" name="nouveau_prenom" placeholder="Saisissez le prénom">
            </div>
            <div class="modal-champ">
                <label>Email - <span class="obligatoire">champ obligatoire</span></label>
                <input type="text" name="nouveau_email" placeholder="Saisissez l'email">
            </div>
            <div class="modal-champ">
                <label>Nombre d'heures</label>
                <input type="text" value="0h" disabled>
            </div>
        </div>

        <div class="modal-champ">
            <label>Modules enseignés - <span class="obligatoire">champ obligatoire</span></label>
            <div class="modal-multiselect">
                <div class="modal-tags" id="modal-tags"></div>
                <select id="modal-select-modules" onchange="ajouterTagModal(this)">
                    <option value="">— Sélectionner un module —</option>
                    <?php foreach ($tous_modules as $m): ?>
                        <option value="<?= $m['id'] ?>" data-nom="<?= htmlspecialchars($m['name']) ?>">
                            <?= htmlspecialchars($m['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="modal-boutons">
            <button type="button" class="bouton-annuler" onclick="document.getElementById('modal-ajouter').close()">Annuler</button>
            <button type="submit" class="bouton-enregistrer-modal">Enregistrer les informations</button>
        </div>
    </form>
</dialog>

<script>
function ajouterTagModal(select) {
    const id  = select.value;
    const nom = select.options[select.selectedIndex].dataset.nom;
    if (!id) return;
    if (document.querySelector(`#modal-tags .modal-tag[data-id="${id}"]`)) {
        select.value = '';
        return;
    }
    const tag = document.createElement('span');
    tag.className = 'modal-tag';
    tag.dataset.id = id;
    tag.innerHTML = `${nom} <button type="button" onclick="this.closest('.modal-tag').remove()">×</button><input type="hidden" name="nouveaux_modules[]" value="${id}">`;
    document.getElementById('modal-tags').appendChild(tag);
    select.value = '';
}
</script>
</body>
</html>
