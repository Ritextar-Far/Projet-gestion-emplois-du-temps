<?php
require_once '../fonctions/db.php';

$erreur_ajout = '';
$success_ajout = false;

$date_debut_filtre = trim($_GET['date_debut'] ?? '');
$date_fin_filtre   = trim($_GET['date_fin'] ?? '');
$module_filtre     = (int)($_GET['module_id'] ?? 0);
$page              = max(1, (int)($_GET['page'] ?? 1));
$par_page          = 10;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'ajouter') {
    $titre                 = trim($_POST['nouveau_titre'] ?? '');
    $date_debut            = trim($_POST['date_debut'] ?? '');
    $date_fin              = trim($_POST['date_fin'] ?? '');
    $module_id             = (int)($_POST['module_id'] ?? 0);
    $intervention_type_id  = (int)($_POST['type_id'] ?? 0);
    $intervenants_selection = isset($_POST['intervenants']) ? array_map('intval', $_POST['intervenants']) : [];
    $visio                 = isset($_POST['visio']) ? 1 : 0;

    if ($date_debut === '' || $date_fin === '' || $module_id === 0 || $intervention_type_id === 0 || empty($intervenants_selection)) {
        $erreur_ajout = 'Tous les champs obligatoires doivent être remplis.';
    } elseif (strlen($titre) > 255) {
        $erreur_ajout = 'Le titre ne peut pas dépasser 255 caractères.';
    } else {
        $debut_ts = strtotime($date_debut);
        $fin_ts   = strtotime($date_fin);

        if ($debut_ts === false || $fin_ts === false) {
            $erreur_ajout = 'Les dates doivent être valides.';
        } elseif ($fin_ts <= $debut_ts) {
            $erreur_ajout = 'La date de fin doit être supérieure à la date de début.';
        } else {
            $duree_heures = ($fin_ts - $debut_ts) / 3600;
            if ($duree_heures > 4) {
                $erreur_ajout = 'L’intervention ne peut pas dépasser 4 heures.';
            } else {
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM instructor_module WHERE instructor_id = ? AND module_id = ?');
                foreach ($intervenants_selection as $instructor_id) {
                    if ($instructor_id <= 0) {
                        continue;
                    }
                    $stmt->execute([$instructor_id, $module_id]);
                    if ($stmt->fetchColumn() == 0) {
                        $erreur_ajout = 'Un intervenant sélectionné n’est pas rattaché au module choisi.';
                        break;
                    }
                }
            }
        }
    }

    if ($erreur_ajout === '') {
        $stmt = $pdo->prepare('INSERT INTO course (title, start_date, end_date, intervention_type_id, module_id, remotely) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$titre ?: null, $date_debut, $date_fin, $intervention_type_id, $module_id, $visio]);
        $course_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare('INSERT INTO course_instructor (course_id, instructor_id) VALUES (?, ?)');
        foreach ($intervenants_selection as $instructor_id) {
            if ($instructor_id > 0) {
                $stmt->execute([$course_id, $instructor_id]);
            }
        }

        header('Location: interventions.php?success=1');
        exit;
    }
}

$stmt = $pdo->query('SELECT id, name FROM module ORDER BY name ASC');
$tous_modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query('SELECT id, name FROM intervention_type ORDER BY name ASC');
$tous_types = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query(
    'SELECT i.id, CONCAT(u.first_name, " ", u.last_name) AS name, im.module_id
     FROM instructor i
     JOIN users u ON i.user_id = u.id
     JOIN instructor_module im ON i.id = im.instructor_id
     ORDER BY u.last_name, u.first_name'
);
$instructors_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$instructors = [];
foreach ($instructors_raw as $row) {
    $id = (int)$row['id'];
    if (!isset($instructors[$id])) {
        $instructors[$id] = [
            'id' => $id,
            'name' => $row['name'],
            'modules' => [],
        ];
    }
    if (!in_array((int)$row['module_id'], $instructors[$id]['modules'], true)) {
        $instructors[$id]['modules'][] = (int)$row['module_id'];
    }
}

$instructors_by_module = [];
foreach ($instructors as $instructor) {
    foreach ($instructor['modules'] as $module_id) {
        $instructors_by_module[$module_id][] = [
            'id' => $instructor['id'],
            'name' => $instructor['name'],
        ];
    }
}

$where = '1=1';
$params = [];
if ($module_filtre > 0) {
    $where .= ' AND c.module_id = ?';
    $params[] = $module_filtre;
}
if ($date_debut_filtre !== '') {
    $where .= ' AND c.start_date >= ?';
    $params[] = $date_debut_filtre . ' 00:00:00';
}
if ($date_fin_filtre !== '') {
    $where .= ' AND c.end_date <= ?';
    $params[] = $date_fin_filtre . ' 23:59:59';
}

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM course c WHERE $where");
$count_stmt->execute($params);
$total_interventions = (int)$count_stmt->fetchColumn();
$total_pages = max(1, (int)ceil($total_interventions / $par_page));
$page = min($page, $total_pages);
$offset = ($page - 1) * $par_page;

$query = 
    'SELECT c.id,
            c.title,
            c.start_date,
            c.end_date,
            m.name AS module_name,
            t.name AS type_name,
            c.remotely AS visio,
            COALESCE(GROUP_CONCAT(DISTINCT CONCAT(u.first_name, " ", u.last_name) ORDER BY u.last_name SEPARATOR ", "), "—") AS intervenants
     FROM course c
     JOIN module m ON c.module_id = m.id
     JOIN intervention_type t ON c.intervention_type_id = t.id
     LEFT JOIN course_instructor ci ON c.id = ci.course_id
     LEFT JOIN instructor i ON ci.instructor_id = i.id
     LEFT JOIN users u ON i.user_id = u.id
     WHERE ' . $where . '
     GROUP BY c.id
     ORDER BY c.start_date DESC
     LIMIT ? OFFSET ?';

$stmt = $pdo->prepare($query);
foreach ($params as $index => $value) {
    $stmt->bindValue($index + 1, $value);
}
$stmt->bindValue(count($params) + 1, $par_page, PDO::PARAM_INT);
$stmt->bindValue(count($params) + 2, $offset, PDO::PARAM_INT);
$stmt->execute();
$interventions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Interventions</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/intervenants.css">
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
            <a href="interventions.php">Interventions</a>
        </nav>

        <div class="entete-page">
            <p class="titre-principal">Interventions</p>
            <button class="bouton-ajouter" onclick="document.getElementById('modal-ajouter').showModal()">
                Ajouter une nouvelle intervention
            </button>
        </div>

        <section class="filtres">
            <form method="GET" class="filtres-formulaire">
                <div class="filtre-champ">
                    <label>Date de début</label>
                    <input type="date" name="date_debut" value="<?= htmlspecialchars($date_debut_filtre) ?>">
                </div>
                <div class="filtre-champ">
                    <label>Date de fin</label>
                    <input type="date" name="date_fin" value="<?= htmlspecialchars($date_fin_filtre) ?>">
                </div>
                <div class="filtre-champ">
                    <label>Module</label>
                    <select name="module_id">
                        <option value="0">Sélectionner le module</option>
                        <?php foreach ($tous_modules as $module): ?>
                            <option value="<?= $module['id'] ?>" <?= $module_filtre === (int)$module['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($module['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filtre-champ bouton-filtrer">
                    <button type="submit" class="bouton-filtrer">Filtrer</button>
                </div>
            </form>
        </section>

        <?php if (isset($_GET['success'])): ?>
            <div class="alerte-succes">L’intervention a bien été ajoutée.</div>
        <?php endif; ?>
        <?php if ($erreur_ajout !== ''): ?>
            <div class="alerte-erreur">⚠️ <?= htmlspecialchars($erreur_ajout) ?></div>
        <?php endif; ?>

        <div class="resultats">
            <p class="resultats-texte"><?= $total_interventions ?> intervention<?= $total_interventions > 1 ? 's' : '' ?> trouvée<?= $total_interventions > 1 ? 's' : '' ?></p>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>Date de l'intervention</th>
                <th>Titre</th>
                <th>Module</th>
                <th>Type</th>
                <th>Intervenants</th>
                <th>En visio</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($interventions)): ?>
                <tr>
                    <td colspan="7" class="aucun-resultat">Aucune intervention trouvée.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($interventions as $intervention): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars(date('d/m/Y H:i', strtotime($intervention['start_date']))) ?>
                        <br>—<br>
                        <?= htmlspecialchars(date('d/m/Y H:i', strtotime($intervention['end_date']))) ?>
                    </td>
                    <td><?= htmlspecialchars($intervention['title'] ?: '—') ?></td>
                    <td><?= htmlspecialchars($intervention['module_name']) ?></td>
                    <td><?= htmlspecialchars($intervention['type_name']) ?></td>
                    <td><?= htmlspecialchars($intervention['intervenants']) ?></td>
                    <td>
                        <?php if ($intervention['visio']): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17 10.5V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-3.5l4 4v-11l-4 4z"/></svg>
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="opacity:0.25"><path d="M21 6.5l-4 4V7a1 1 0 0 0-1-1H9.82L21 17.18V6.5zM3.27 2L2 3.27 4.73 6H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12c.28 0 .53-.11.71-.29L19.73 21 21 19.73 3.27 2z"/></svg>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="fiche_intervention.php?id=<?= (int)$intervention['id'] ?>" class="lien-fiche">
                            Accéder à la fiche
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>&date_debut=<?= urlencode($date_debut_filtre) ?>&date_fin=<?= urlencode($date_fin_filtre) ?>&module_id=<?= $module_filtre ?>" class="pagination-item <?= $i === $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
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
                <input type="text" name="nouveau_titre" maxlength="255" placeholder="Saisir un titre sur l'intervention">
            </div>
            <div class="modal-champ">
                <label>Date de début - <span class="obligatoire">champ obligatoire</span></label>
                <input type="datetime-local" name="date_debut" required>
            </div>
            <div class="modal-champ">
                <label>Date de fin - <span class="obligatoire">champ obligatoire</span></label>
                <input type="datetime-local" name="date_fin" required>
            </div>
            <div class="modal-champ">
                <label>Module - <span class="obligatoire">champ obligatoire</span></label>
                <select name="module_id" id="module-select" required onchange="filtrerIntervenants()">
                    <option value="">— Sélectionnez le module —</option>
                    <?php foreach ($tous_modules as $module): ?>
                        <option value="<?= $module['id'] ?>"><?= htmlspecialchars($module['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-champ">
                <label>Type d'intervention - <span class="obligatoire">champ obligatoire</span></label>
                <select name="type_id" required>
                    <option value="">— Sélectionnez le type —</option>
                    <?php foreach ($tous_types as $type): ?>
                        <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-champ">
                <label>Intervenants - <span class="obligatoire">champ obligatoire</span></label>
                <select name="intervenants[]" id="intervenant-select" multiple size="8" required>
                    <option value="" disabled>Choisissez un module d'abord</option>
                </select>
            </div>
            <div class="modal-champ modal-checkbox">
                <label>Intervention effectuée en visio</label>
                <label class="switch">
                    <input type="checkbox" name="visio" value="1">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
        <div class="modal-boutons">
            <button type="button" class="bouton-annuler" onclick="document.getElementById('modal-ajouter').close()">Annuler</button>
            <button type="submit" class="bouton-enregistrer-modal">Confirmer</button>
        </div>
    </form>
</dialog>

<script>
    const intervenantsParModule = <?= json_encode($instructors_by_module, JSON_UNESCAPED_UNICODE) ?>;

    function filtrerIntervenants() {
        const moduleId = document.getElementById('module-select').value;
        const intervenantSelect = document.getElementById('intervenant-select');
        intervenantSelect.innerHTML = '';

        if (!moduleId || !intervenantsParModule[moduleId] || intervenantsParModule[moduleId].length === 0) {
            const option = document.createElement('option');
            option.textContent = 'Aucun intervenant disponible pour ce module.';
            option.disabled = true;
            intervenantSelect.appendChild(option);
            return;
        }

        intervenantsParModule[moduleId].forEach(instructor => {
            const option = document.createElement('option');
            option.value = instructor.id;
            option.textContent = instructor.name;
            intervenantSelect.appendChild(option);
        });
    }
</script>
</body>
</html>
