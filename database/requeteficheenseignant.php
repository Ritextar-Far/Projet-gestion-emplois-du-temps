<?php

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header('Location: corps_enseignant.php');
    exit;
}

// Récupération de l'enseignant
$stmt = $pdo->prepare("
    SELECT i.id, u.last_name, u.first_name, u.email
    FROM instructor i
    JOIN users u ON i.user_id = u.id
    WHERE i.id = ?
");
$stmt->execute([$id]);
$enseignant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$enseignant) {
    header('Location: corps_enseignant.php');
    exit;
}

// Heures par module pour cet enseignant
$stmt = $pdo->prepare("
    SELECT m.name, SUM(TIMESTAMPDIFF(HOUR, c.start_date, c.end_date)) AS heures
    FROM course c
    JOIN course_instructor ci ON c.id = ci.course_id
    JOIN module m ON c.module_id = m.id
    WHERE ci.instructor_id = ?
    GROUP BY m.id, m.name
    ORDER BY m.name ASC
");
$stmt->execute([$id]);
$heures_par_module = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Modules enseignés par cet enseignant
$stmt = $pdo->prepare("
    SELECT m.id, m.name
    FROM instructor_module im
    JOIN module m ON im.module_id = m.id
    WHERE im.instructor_id = ?
    ORDER BY m.name ASC
");
$stmt->execute([$id]);
$modules_enseignant = $stmt->fetchAll(PDO::FETCH_ASSOC);
$ids_modules_enseignant = array_column($modules_enseignant, 'id');

// Tous les modules disponibles (pour le sélecteur)
$stmt = $pdo->query("SELECT id, name FROM module ORDER BY name ASC");
$tous_modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement : Enregistrer
if (isset($_POST['enregistrer'])) {
    $nom    = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $mods   = isset($_POST['modules']) ? array_map('intval', $_POST['modules']) : [];

    if ($nom !== '' && $prenom !== '' && $email !== '') {
        $stmt = $pdo->prepare("UPDATE users SET last_name = ?, first_name = ?, email = ? WHERE id = (SELECT user_id FROM instructor WHERE id = ?)");
        $stmt->execute([$nom, $prenom, $email, $id]);

        $stmt = $pdo->prepare("DELETE FROM instructor_module WHERE instructor_id = ?");
        $stmt->execute([$id]);

        if (!empty($mods)) {
            $stmt = $pdo->prepare("INSERT INTO instructor_module (instructor_id, module_id) VALUES (?, ?)");
            foreach ($mods as $mid) {
                $stmt->execute([$id, $mid]);
            }
        }

        header("Location: fiche_enseignant.php?id=$id&success=1");
        exit;
    } else {
        $erreur = "Tous les champs sont obligatoires.";
    }
}
