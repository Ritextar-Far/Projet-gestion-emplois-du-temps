<?php

// Traitement : Ajouter un type d'intervention
if (isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    $nom   = trim($_POST['nouveau_nom']   ?? '');
    $color = trim($_POST['nouveau_color'] ?? '');
    $desc  = trim($_POST['nouveau_desc']  ?? '');

    if ($nom !== '' && $color !== '' && $desc !== '') {
        $stmt = $pdo->prepare("INSERT INTO intervention_type (name, description, color) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $desc, $color]);
        header('Location: type_intervention.php?success=1');
        exit;
    } else {
        $erreur_ajout = "Tous les champs sont obligatoires.";
    }
}

// Filtre
$filtre_nom = isset($_GET['nom']) ? trim($_GET['nom']) : '';

$page     = max(1, (int)($_GET['page'] ?? 1));
$par_page = 10;

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM intervention_type WHERE name LIKE ?");
$count_stmt->execute(['%' . $filtre_nom . '%']);
$nb_types    = (int)$count_stmt->fetchColumn();
$total_pages = max(1, (int)ceil($nb_types / $par_page));
$page        = min($page, $total_pages);
$offset      = ($page - 1) * $par_page;

$stmt = $pdo->prepare("SELECT * FROM intervention_type WHERE name LIKE ? ORDER BY name ASC LIMIT ? OFFSET ?");
$stmt->bindValue(1, '%' . $filtre_nom . '%');
$stmt->bindValue(2, $par_page, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);
