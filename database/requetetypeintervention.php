<?php

$filtre_nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';

if ($filtre_nom !== '') {
    $stmt = $pdo->prepare("SELECT * FROM intervention_type WHERE name LIKE ? ORDER BY name ASC");
    $stmt->execute(['%' . $filtre_nom . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM intervention_type ORDER BY name ASC");
}

$types = $stmt->fetchAll(PDO::FETCH_ASSOC);
$nb_types = count($types);
