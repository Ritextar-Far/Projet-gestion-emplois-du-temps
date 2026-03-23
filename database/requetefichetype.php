<?php

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header('Location: type_intervention.php');
    exit;
}

// Récupération du type
$stmt = $pdo->prepare("SELECT * FROM intervention_type WHERE id = ?");
$stmt->execute([$id]);
$type = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$type) {
    header('Location: type_intervention.php');
    exit;
}

// le Traitement Enregistrer
if (isset($_POST['enregistrer'])) {
    $nom   = trim($_POST['nom']);
    $desc  = trim($_POST['description']);
    $color = trim($_POST['color']);

    if ($nom !== '' && $desc !== '' && $color !== '') {
        $stmt = $pdo->prepare("UPDATE intervention_type SET name = ?, description = ?, color = ? WHERE id = ?");
        $stmt->execute([$nom, $desc, $color, $id]);
        header('Location: fiche_type.php?id=' . $id . '&success=1');
        exit;
    } else {
        $erreur = "Tous les champs sont obligatoires.";
    }
}

// la Traitement Supprimer
if (isset($_POST['supprimer'])) {
    $stmt = $pdo->prepare("DELETE FROM intervention_type WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: type_intervention.php');
    exit;
}

$page_actuelle = basename($_SERVER['PHP_SELF']);
