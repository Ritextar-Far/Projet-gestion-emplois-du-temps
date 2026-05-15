<?php
$message = "";

$db = new PDO('mysql:host=localhost;dbname=gestion_licence;charset=utf8mb4', 'root', '');

// 1. Vérifier si on est en mode modification (présence d'un ID dans l'URL)
$id_module = isset($_GET['id']) ? intval($_GET['id']) : null;
$is_edit = ($id_module !== null);

// Initialisation des variables pour les champs du formulaire (valeurs par défaut pour le mode ajout)
$code_val = "";
$nom_val = "";
$heures_val = "14";
$parent_val = null;
$description_val = "Piloter un projet informatique";
$projet_fil_rouge_val = 1; // Coché par défaut

// 2. Si on est en mode modification, récupérer les infos actuelles du module
if ($is_edit) {
    $stmt = $db->prepare("SELECT * FROM module WHERE id = :id");
    $stmt->execute(['id' => $id_module]);
    $module = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($module) {
        $code_val = $module['code'];
        $nom_val = $module['name'];
        $heures_val = $module['hours_count'];
        $parent_val = $module['parent_id'];
        $description_val = $module['description'];
        $projet_fil_rouge_val = $module['capstone_project'];
    } else {
        // Si l'ID n'existe pas en BDD, on bascule en mode ajout
        $is_edit = false;
        $id_module = null;
    }
}

// 3. Gestion de la soumission du formulaire (Enregistrement ou Modification)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enregistrer'])) {
    $code = $_POST['code'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $heures = $_POST['heures'] ?? 0;
    $parent = !empty($_POST['parent']) ? $_POST['parent'] : null; 
    $description = $_POST['description'] ?? '';
    $projet_fil_rouge = isset($_POST['projet_fil_rouge']) ? 1 : 0;

    try {
        if ($is_edit) {
            // Mode modification : UPDATE
            $sql = "UPDATE module 
                    SET code = :code, name = :nom, hours_count = :heures, parent_id = :parent, description = :description, capstone_project = :projet_fil_rouge 
                    WHERE id = :id";
            
            $query = $db->prepare($sql);
            $query->execute([
                'code' => $code,
                'nom' => $nom,
                'heures' => $heures,
                'parent' => $parent, 
                'description' => $description,
                'projet_fil_rouge' => $projet_fil_rouge,
                'id' => $id_module
            ]);

            $message = "Module modifié avec succès !";
            
            // Mettre à jour les variables pour réafficher les nouvelles valeurs saisies
            $code_val = $code;
            $nom_val = $nom;
            $heures_val = $heures;
            $parent_val = $parent;
            $description_val = $description;
            $projet_fil_rouge_val = $projet_fil_rouge;

        } else {
            // Mode ajout : INSERT
            $sql = "INSERT INTO module (code, name, hours_count, parent_id, description, capstone_project) 
                    VALUES (:code, :nom, :heures, :parent, :description, :projet_fil_rouge)";
            
            $query = $db->prepare($sql);
            $query->execute([
                'code' => $code,
                'nom' => $nom,
                'heures' => $heures,
                'parent' => $parent, 
                'description' => $description,
                'projet_fil_rouge' => $projet_fil_rouge
            ]);

            $message = "Module ajouté avec succès !";
        }
    } catch (Exception $e) {
        $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_edit ? "Modifier le Module" : "Ajouter un Module" ?></title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/fiche-momule.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="corps-enseignant">
    <?php require_once '../inclus/header.php'; ?>

    <main class="page-container">
        
        <nav class="fil">
            <a href="index.php"><img src="assets/images/home.svg"></a>
            <span>›</span>
            <a href="modules.php">Modules</a>
        </nav>

        <h1 class="page-title"><?= $is_edit ? "Modifier le Module : " . htmlspecialchars($nom_val) : "Module" ?></h1>

        <?php if($message) echo "<p class='alert' style='padding: 10px; margin-bottom: 15px; color: green; font-weight: bold;'>$message</p>"; ?>

        <form action="" method="POST" class="module-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Code - champ obligatoire</label>
                    <input type="text" name="code" placeholder="TAILWIND_CSS" value="<?= htmlspecialchars($code_val) ?>" required>
                </div>
                <div class="form-group">
                    <label>Nom - champ obligatoire</label>
                    <input type="text" name="nom" placeholder="Tailwind CSS" value="<?= htmlspecialchars($nom_val) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nombre d'heures</label>
                    <input type="number" name="heures" value="<?= htmlspecialchars($heures_val) ?>">
                </div>
            </div>

            <div class="form-group full-width">
                <label>Description - champ obligatoire</label>
                <textarea name="description" rows="4" required><?= htmlspecialchars($description_val) ?></textarea>
            </div>

            <div class="toggle-container">
                <label class="switch">
                    <input type="checkbox" name="projet_fil_rouge" <?= $projet_fil_rouge_val ? 'checked' : '' ?>>
                    <span class="slider round"></span>
                </label>
                <span class="toggle-text">Module effectué sur le projet fil rouge</span>
            </div>

            <div class="form-actions">
                <a href="modules.php" class="btn btn-secondary">Retour à la liste</a>
                <button type="submit" name="enregistrer" class="btn btn-primary">Enregistrer les informations</button>
            </div>
        </form>
    </main>
    
</body>
</html>