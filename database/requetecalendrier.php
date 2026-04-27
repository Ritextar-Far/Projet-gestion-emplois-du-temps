<?php
require_once '../fonctions/db.php';

if (isset($_POST['action']) && $_POST['action'] === 'ajouter') {

    $titre      = trim($_POST['nouveau_titre'] ?? '');
    $date_debut = trim($_POST['date_debut']    ?? '');
    $date_fin   = trim($_POST['date_fin']      ?? '');
    $modules    = isset($_POST['nouveaux_modules'])      ? array_map('intval', $_POST['nouveaux_modules'])      : [];
    $types      = isset($_POST['nouveaux_types'])        ? array_map('intval', $_POST['nouveaux_types'])        : [];
    $interv     = isset($_POST['nouveaux_intervenants']) ? array_map('intval', $_POST['nouveaux_intervenants']) : [];
    $remotely   = isset($_POST['remotely']) ? 1 : 0;

    if (empty($date_debut) || empty($date_fin) || empty($modules) || empty($types)) {
        $erreur_ajout = "Veuillez remplir tous les champs obligatoires.";
    } elseif ($date_fin < $date_debut) {
        $erreur_ajout = "La date de fin ne peut pas être antérieure à la date de début.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO course (title, start_date, end_date, module_id, intervention_type_id, remotely)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        foreach ($modules as $module_id) {
            $type_id = $types[0] ?? null;
            $stmt->execute([$titre ?: null, $date_debut, $date_fin, $module_id, $type_id, $remotely]);
            $course_id = $pdo->lastInsertId();

            if (!empty($interv)) {
                $stmtCI = $pdo->prepare("
                    INSERT INTO course_instructor (course_id, instructor_id) VALUES (?, ?)
                ");
                foreach ($interv as $instructor_id) {
                    $stmtCI->execute([$course_id, $instructor_id]);
                }
            }
        }

        header("Location: calendrier.php?success=1");
        exit;
    }
}

$stmt = $pdo->query("
    SELECT
        c.id,
        CONCAT(
            DATE_FORMAT(c.start_date, '%d/%m/%Y %Hh%i'),
            ' à ',
            DATE_FORMAT(c.end_date, '%Hh%i')
        )                                                   AS date,
        CONCAT(
            m.name,
            IF(c.title IS NOT NULL AND c.title != '',
               CONCAT(' - ', c.title), '')
        )                                                   AS mod_titre,
        COALESCE(it.name, '—')                             AS type,
        GROUP_CONCAT(
            CONCAT(u.first_name, ' ', u.last_name)
            ORDER BY u.last_name
            SEPARATOR ', '
        )                                                   AS intervenants,
        c.remotely                                          AS visio
    FROM course c
    JOIN module m                   ON c.module_id            = m.id
    LEFT JOIN intervention_type it  ON c.intervention_type_id = it.id
    LEFT JOIN course_instructor ci  ON c.id                   = ci.course_id
    LEFT JOIN instructor ins        ON ci.instructor_id       = ins.id
    LEFT JOIN users u               ON ins.user_id            = u.id
    GROUP BY c.id, c.start_date, c.end_date, m.name, c.title, it.name, c.remotely
    ORDER BY c.start_date ASC
");
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT id, name FROM module ORDER BY name ASC");
$tous_modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT id, name FROM intervention_type ORDER BY name ASC");
$tous_types = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("
    SELECT i.id, CONCAT(u.first_name, ' ', u.last_name) AS name
    FROM instructor i
    JOIN users u ON i.user_id = u.id
    ORDER BY u.last_name ASC
");
$tous_intervenants = $stmt->fetchAll(PDO::FETCH_ASSOC);