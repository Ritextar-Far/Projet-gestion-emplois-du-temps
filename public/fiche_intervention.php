<?php
require_once '../fonctions/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: interventions.php');
    exit;
}

$erreur = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'modifier') {
    $titre                 = trim($_POST['titre'] ?? '');
    $date_debut            = trim($_POST['date_debut'] ?? '');
    $date_fin              = trim($_POST['date_fin'] ?? '');
    $module_id             = (int)($_POST['module_id'] ?? 0);
    $intervention_type_id  = (int)($_POST['type_id'] ?? 0);
    $intervenants_selection = isset($_POST['intervenants']) ? array_map('intval', $_POST['intervenants']) : [];
    $visio                 = isset($_POST['visio']) ? 1 : 0;

    if ($date_debut === '' || $date_fin === '' || $module_id === 0 || $intervention_type_id === 0 || empty($intervenants_selection)) {
        $erreur = 'Tous les champs obligatoires doivent être remplis.';
    } elseif (strlen($titre) > 255) {
        $erreur = 'Le titre ne peut pas dépasser 255 caractères.';
    } else {
        $debut_ts = strtotime($date_debut);
        $fin_ts   = strtotime($date_fin);
        if ($debut_ts === false || $fin_ts === false) {
            $erreur = 'Les dates doivent être valides.';
        } elseif ($fin_ts <= $debut_ts) {
            $erreur = 'La date de fin doit être supérieure à la date de début.';
        } else {
            $duree_heures = ($fin_ts - $debut_ts) / 3600;
            if ($duree_heures > 4) {
                $erreur = 'L’intervention ne peut pas dépasser 4 heures.';
            } else {
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM instructor_module WHERE instructor_id = ? AND module_id = ?');
                foreach ($intervenants_selection as $instructor_id) {
                    if ($instructor_id <= 0) {
                        continue;
                    }
                    $stmt->execute([$instructor_id, $module_id]);
                    if ($stmt->fetchColumn() == 0) {
                        $erreur = 'Un intervenant sélectionné n’est pas rattaché au module choisi.';
                        break;
                    }
                }
            }
        }
    }

    if ($erreur === '') {
        $stmt = $pdo->prepare('UPDATE course SET title = ?, start_date = ?, end_date = ?, intervention_type_id = ?, module_id = ?, remotely = ? WHERE id = ?');
        $stmt->execute([$titre ?: null, $date_debut, $date_fin, $intervention_type_id, $module_id, $visio, $id]);

        $stmt = $pdo->prepare('DELETE FROM course_instructor WHERE course_id = ?');
        $stmt->execute([$id]);

        $stmt = $pdo->prepare('INSERT INTO course_instructor (course_id, instructor_id) VALUES (?, ?)');
        foreach ($intervenants_selection as $instructor_id) {
            if ($instructor_id > 0) {
                $stmt->execute([$id, $instructor_id]);
            }
        }

        header('Location: fiche_intervention.php?id=' . $id . '&success=1');
        exit;
    }
}

$stmt = $pdo->prepare(
    'SELECT c.*, m.name AS module_name, t.name AS type_name
     FROM course c
     JOIN module m ON c.module_id = m.id
     JOIN intervention_type t ON c.intervention_type_id = t.id
     WHERE c.id = ?'
);
$stmt->execute([$id]);
$intervention = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$intervention) {
    header('Location: interventions.php');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT i.id, CONCAT(u.first_name, " ", u.last_name) AS name
     FROM course_instructor ci
     JOIN instructor i ON ci.instructor_id = i.id
     JOIN users u ON i.user_id = u.id
     WHERE ci.course_id = ?
     ORDER BY u.last_name, u.first_name'
);
$stmt->execute([$id]);
$selected_intervenants = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    $idInstr = (int)$row['id'];
    if (!isset($instructors[$idInstr])) {
        $instructors[$idInstr] = [
            'id' => $idInstr,
            'name' => $row['name'],
            'modules' => [],
        ];
    }
    if (!in_array((int)$row['module_id'], $instructors[$idInstr]['modules'], true)) {
        $instructors[$idInstr]['modules'][] = (int)$row['module_id'];
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

$current_instructors = array_column($selected_intervenants, 'id');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche intervention</title>
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
            <span>›</span>
            <span>Fiche intervention</span>
        </nav>

        <div class="entete-page">
            <p class="titre-principal">Fiche intervention</p>
            <a href="interventions.php" class="bouton-ajouter">Retour</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alerte-succes">La fiche a bien été mise à jour.</div>
        <?php endif; ?>
        <?php if ($erreur !== ''): ?>
            <div class="alerte-erreur">⚠️ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" action="fiche_intervention.php?id=<?= (int)$_GET['id'] ?>">
            <input type="hidden" name="action" value="modifier">
            <div class="modal-grille">
                <div class="modal-champ">
                    <label>Titre</label>
                    <input type="text" name="titre" maxlength="255" value="<?= htmlspecialchars($intervention['title'] ?? '') ?>">
                </div>
                <div class="modal-champ">
                    <label>Date de début</label>
                    <input type="datetime-local" name="date_debut" required value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($intervention['start_date']))) ?>">
                </div>
                <div class="modal-champ">
                    <label>Date de fin</label>
                    <input type="datetime-local" name="date_fin" required value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($intervention['end_date']))) ?>">
                </div>
                <div class="modal-champ">
                    <label>Module</label>
                    <select name="module_id" id="module-select" required onchange="filtrerIntervenants()">
                        <option value="">— Sélectionnez le module —</option>
                        <?php foreach ($tous_modules as $module): ?>
                            <option value="<?= $module['id'] ?>" <?= (int)$intervention['module_id'] === (int)$module['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($module['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-champ">
                    <label>Type d'intervention</label>
                    <select name="type_id" required>
                        <option value="">— Sélectionnez le type —</option>
                        <?php foreach ($tous_types as $type): ?>
                            <option value="<?= $type['id'] ?>" <?= (int)$intervention['intervention_type_id'] === (int)$type['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-champ">
                    <label>Intervenants</label>
                    <select name="intervenants[]" id="intervenant-select" multiple size="8" required>
                    </select>
                </div>
                <div class="modal-champ modal-checkbox">
                    <label>Intervention effectuée en visio</label>
                    <label class="switch">
                        <input type="checkbox" name="visio" value="1" <?= $intervention['remotely'] ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            <div class="modal-boutons">
                <button type="submit" class="bouton-enregistrer-modal">Enregistrer</button>
            </div>
        </form>
    </main>
</div>

<script>
    const intervenantsParModule = <?= json_encode($instructors_by_module, JSON_UNESCAPED_UNICODE) ?>;
    const currentIntervenants = <?= json_encode($current_instructors, JSON_UNESCAPED_UNICODE) ?>;

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
            if (currentIntervenants.includes(instructor.id)) {
                option.selected = true;
            }
            intervenantSelect.appendChild(option);
        });
    }

    filtrerIntervenants();
</script>
</body>
</html>
