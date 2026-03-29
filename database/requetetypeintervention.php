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
$filtre_nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';

if ($filtre_nom !== '') {
    $stmt = $pdo->prepare("SELECT * FROM intervention_type WHERE name LIKE ? ORDER BY name ASC");
    $stmt->execute(['%' . $filtre_nom . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM intervention_type ORDER BY name ASC");
}

$types    = $stmt->fetchAll(PDO::FETCH_ASSOC);
$nb_types = count($types);
