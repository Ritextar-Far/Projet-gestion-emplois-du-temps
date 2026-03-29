<?php

// Traitement : Ajouter un enseignant
if (isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    $nom    = trim($_POST['nouveau_nom']    ?? '');
    $prenom = trim($_POST['nouveau_prenom'] ?? '');
    $email  = trim($_POST['nouveau_email']  ?? '');
    $mods   = isset($_POST['nouveaux_modules']) ? array_map('intval', $_POST['nouveaux_modules']) : [];

    if ($nom !== '' && $prenom !== '' && $email !== '') {
        $stmt = $pdo->prepare("INSERT INTO users (role, email, last_name, first_name, password) VALUES ('instructor', ?, ?, ?, '')");
        $stmt->execute([$email, $nom, $prenom]);
        $user_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO instructor (user_id) VALUES (?)");
        $stmt->execute([$user_id]);
        $instructor_id = $pdo->lastInsertId();

        if (!empty($mods)) {
            $stmt = $pdo->prepare("INSERT INTO instructor_module (instructor_id, module_id) VALUES (?, ?)");
            foreach ($mods as $mid) {
                $stmt->execute([$instructor_id, $mid]);
            }
        }

        header('Location: corps_enseignant.php?success=1');
        exit;
    } else {
        $erreur_ajout = "Tous les champs sont obligatoires.";
    }
}

// Filtres
$filtre_nom    = isset($_POST['nom'])    ? trim($_POST['nom'])    : '';
$filtre_prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
$filtre_email  = isset($_POST['email'])  ? trim($_POST['email'])  : '';

$stmt = $pdo->prepare("
    SELECT i.id, u.last_name, u.first_name, u.email,
           GROUP_CONCAT(DISTINCT m.name ORDER BY m.name SEPARATOR ', ') AS modules,
           COALESCE(SUM(TIMESTAMPDIFF(HOUR, c.start_date, c.end_date)), 0) AS total_heures
    FROM instructor i
    JOIN users u ON i.user_id = u.id
    LEFT JOIN instructor_module im ON i.id = im.instructor_id
    LEFT JOIN module m ON im.module_id = m.id
    LEFT JOIN course_instructor ci ON i.id = ci.instructor_id
    LEFT JOIN course c ON ci.course_id = c.id
    WHERE u.last_name LIKE ? AND u.first_name LIKE ? AND u.email LIKE ?
    GROUP BY i.id, u.last_name, u.first_name, u.email
    ORDER BY u.last_name ASC
");
$stmt->execute(["%$filtre_nom%", "%$filtre_prenom%", "%$filtre_email%"]);

$enseignants    = $stmt->fetchAll(PDO::FETCH_ASSOC);
$nb_enseignants = count($enseignants);

// Modules disponibles pour la modale
$stmt        = $pdo->query("SELECT id, name FROM module ORDER BY name ASC");
$tous_modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
