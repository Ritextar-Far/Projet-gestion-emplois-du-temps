<?php
require_once '../fonctions/db.php';
require_once '../database/requetecalendrier.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendrier</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/calendrier.css">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Quicksand:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body class="pageintervention">
<div class="page">
    <?php require_once '../inclus/header.php'; ?>
    <main class="contenu">
        <nav class="fil">
            <a href="index.php"><img src="assets/images/Home.svg"></a>
            <span>›</span>
            <a href="calendrier.php">Calendrier</a>
        </nav>

        <div class="entete-page">
            <p class="titre-principal">Calendrier</p>
            <button class="bouton-ajouter" onclick="document.getElementById('modal-ajouter').showModal()">
                Ajouter une nouvelle intervention
            </button>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alerte-succes">La nouvelle intervention a bien été ajouté.</div>
        <?php endif; ?>
        <?php if (isset($erreur_ajout)): ?>
            <div class="alerte-erreur">⚠️ <?= htmlspecialchars($erreur_ajout) ?></div>
        <?php endif; ?>

        <table class="table">
            <thead>
            <tr>
                <th>Date de l'intervention</th>
                <th>Module &amp; titre</th>
                <th>Type</th>
                <th>Intervenants</th>
                <th>En visio</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($types as $type): ?>
                <tr>
                    <td><?= htmlspecialchars($type['date'] ?? '') ?></td>
                    <td><?= htmlspecialchars($type['mod_titre'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($type['type'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($type['intervenants'] ?? '—') ?></td>
                    <td>
                        <?php if ($type['visio']): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17 10.5V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-3.5l4 4v-11l-4 4z"/></svg>
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="opacity:0.25"><path d="M21 6.5l-4 4V7a1 1 0 0 0-1-1H9.82L21 17.18V6.5zM3.27 2L2 3.27 4.73 6H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12c.28 0 .53-.11.71-.29L19.73 21 21 19.73 3.27 2z"/></svg>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="fiche_type.php?id=<?= (int)$type['id'] ?>" class="lien-fiche">
                            Accéder à la fiche
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>

<dialog id="modal-ajouter">
    <button class="modal-fermer" type="button" onclick="document.getElementById('modal-ajouter').close()">×</button>

    <div class="modal-entete">
        <div class="modal-icone">+</div>
        <div>
            <h2 class="modal-titre">Ajouter une intervention</h2>
            <p class="modal-sous-titre">Remplissez les informations ci-dessous.</p>
        </div>
    </div>

    <form method="POST" action="">
        <input type="hidden" name="action" value="ajouter">

        <div class="modal-grille">
            <div class="modal-champ">
                <label>Titre</label>
                <input type="text" name="nouveau_titre" placeholder="Saisir un titre sur l'intervention">
            </div>
            <div class="modal-champ">
                <label>Date de début - <span class="obligatoire">champ obligatoire</span></label>
                <input type="date" name="date_debut">
            </div>
            <div class="modal-champ">
                <label>Date de fin - <span class="obligatoire">champ obligatoire</span></label>
                <input type="date" name="date_fin">
            </div>

            <div class="modal-champ">
                <label>Module - <span class="obligatoire">champ obligatoire</span></label>
                <div class="modal-multiselect">
                    <div class="modal-tags" id="tags-modules"></div>
                    <select id="select-modules" onchange="ajouterTag(this, 'tags-modules', 'nouveaux_modules[]')">
                    <option value="">— Sélectionnez le module —</option>
                        <?php foreach ($tous_modules as $m): ?>
                            <option value="<?= $m['id'] ?>" data-nom="<?= htmlspecialchars($m['name']) ?>">
                                <?= htmlspecialchars($m['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="modal-champ">
                <label>Type d'intervention - <span class="obligatoire">champ obligatoire</span></label>
                <div class="modal-multiselect">
                    <div class="modal-tags" id="tags-types"></div>
                    <select id="select-types" onchange="ajouterTag(this, 'tags-types', 'nouveaux_types[]')">
                        <option value="">— Sélectionnez le type —</option>
                        <?php foreach ($tous_types as $t): ?>
                            <option value="<?= $t['id'] ?>" data-nom="<?= htmlspecialchars($t['name']) ?>">
                                <?= htmlspecialchars($t['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-champ">
                <label>Intervenants - <span class="obligatoire">champ obligatoire</span></label>
                <div class="modal-multiselect">
                    <div class="modal-tags" id="tags-intervenants"></div>
                    <select id="select-intervenants" onchange="ajouterTag(this, 'tags-intervenants', 'nouveaux_intervenants[]')">
                        <option value="">— Sélectionnez un intervenant —</option>
                        <?php foreach ($tous_intervenants as $i): ?>
                            <option value="<?= $i['id'] ?>" data-nom="<?= htmlspecialchars($i['name']) ?>">
                                <?= htmlspecialchars($i['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="modal-boutons">
            <button type="button" class="bouton-annuler" onclick="document.getElementById('modal-ajouter').close()">Annuler</button>
            <button type="submit" class="bouton-enregistrer-modal">Confirmer</button>
        </div>
    </form>
</dialog>

<script>
    function ajouterTag(select, tagsId, inputName) {
        const id  = select.value;
        const nom = select.options[select.selectedIndex].dataset.nom;
        if (!id) return;
        const container = document.getElementById(tagsId);
        if (container.querySelector(`.modal-tag[data-id="${id}"]`)) {
            select.value = '';
            return;
        }
        const tag = document.createElement('span');
        tag.className = 'modal-tag';
        tag.dataset.id = id;
        tag.innerHTML = `${nom} <button type="button" onclick="this.closest('.modal-tag').remove()">×</button><input type="hidden" name="${inputName}" value="${id}">`;
        container.appendChild(tag);
        select.value = '';
    }
</script>
</body>
</html>