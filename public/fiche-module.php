<?php
$message = "";

$db = new PDO('mysql:host=localhost;dbname=gestion_licence;charset=utf8mb4', 'root', '');

// 1. Déterminer si on est en mode modification ou ajout
$id_module = isset($_GET['id']) ? intval($_GET['id']) : null;
$is_edit = ($id_module !== null);

$code_val = "";
$nom_val = "";
$heures_val = "14";
$parent_val = null;
$description_val = "Piloter un projet informatique";
$projet_fil_rouge_val = 1;

// 2. Si mode édition, récupérer les infos
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
        $is_edit = false;
        $id_module = null;
    }
}

// 3. Traitement de la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer_suppression']) && $is_edit) {
    try {
        $stmt = $db->prepare("DELETE FROM module WHERE id = :id");
        $stmt->execute(['id' => $id_module]);
        header("Location: modules.php");
        exit;
    } catch (PDOException $e) {
        $message = "Erreur : Impossible de supprimer ce module car des interventions y sont probablement liées.";
    }
}

// 4. Traitement de l'enregistrement (Ajout ou Modification)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enregistrer'])) {
    $code = $_POST['code'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $heures = $_POST['heures'] ?? 0;
    $parent = !empty($_POST['parent']) ? $_POST['parent'] : null; 
    $description = $_POST['description'] ?? '';
    $projet_fil_rouge = isset($_POST['projet_fil_rouge']) ? 1 : 0;

    try {
        if ($is_edit) {
            $sql = "UPDATE module SET code = :code, name = :nom, hours_count = :heures, parent_id = :parent, description = :description, capstone_project = :projet_fil_rouge WHERE id = :id";
            $query = $db->prepare($sql);
            $query->execute([
                'code' => $code, 'nom' => $nom, 'heures' => $heures, 'parent' => $parent, 
                'description' => $description, 'projet_fil_rouge' => $projet_fil_rouge, 'id' => $id_module
            ]);
            $message = "Module modifié avec succès !";
            $code_val = $code; $nom_val = $nom; $heures_val = $heures; 
            $parent_val = $parent; $description_val = $description; $projet_fil_rouge_val = $projet_fil_rouge;
        } else {
            $sql = "INSERT INTO module (code, name, hours_count, parent_id, description, capstone_project) VALUES (:code, :nom, :heures, :parent, :description, :projet_fil_rouge)";
            $query = $db->prepare($sql);
            $query->execute([
                'code' => $code, 'nom' => $nom, 'heures' => $heures, 'parent' => $parent, 
                'description' => $description, 'projet_fil_rouge' => $projet_fil_rouge
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
            <?php if ($is_edit): ?>
                <span>›</span>
                <a href="#"><?= htmlspecialchars($nom_val) ?></a>
            <?php endif; ?>
        </nav>

        <h1 class="page-title"><?= $is_edit ? htmlspecialchars($nom_val) : "Module" ?></h1>

        <?php if($message): ?>
            <p class='alert' style='padding: 10px; margin-bottom: 15px; font-weight: bold; color: <?= strpos($message, "Erreur") !== false ? "red" : "green" ?>;'>
                <?= $message ?>
            </p>
        <?php endif; ?>

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
                <div class="form-group">
                    <label>Parent</label>
                    <select name="parent" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-family: 'Poppins', sans-serif;">
                        <option value="">-- Aucun --</option>
                        <option value="1" <?= $parent_val == 1 ? 'selected' : '' ?>>Développement front</option>
                    </select>
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

            <div class="form-actions" style="display: flex; gap: 10px; align-items: center;">
                <a href="modules.php" class="btn btn-secondary">Retour à la liste</a>
                
                <?php if ($is_edit): ?>
                    <button type="button" class="btn-delete-trigger" onclick="document.getElementById('modalSuppression').style.display='flex'">Supprimer</button>
                <?php endif; ?>

                <button type="submit" name="enregistrer" class="btn btn-primary">Enregistrer les informations</button>
            </div>
        </form>
    </main>
    
    <?php if ($is_edit): ?>
    <div id="modalSuppression" class="modal-overlay">
        <div class="modal-content">
            <button type="button" class="modal-close" onclick="document.getElementById('modalSuppression').style.display='none'"><i class="fas fa-times"></i></button>
            
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-times"></i></div>
                <div class="modal-title">
                    <h2>Supprimer le module</h2>
                    <p>Confirmation de l'action</p>
                </div>
            </div>

            <div class="modal-body">
                Vous vous apprêtez à supprimer le module, cette action est irrévocable.<br>
                A noter qu'aucune intervention ne doit être liée à ce module pour pouvoir le supprimer.<br><br>
                <strong>Confirmez-vous l'action ?</strong>
            </div>

            <form method="POST" action="">
                <div class="modal-actions">
                    <button type="button" class="modal-btn btn-cancel" onclick="document.getElementById('modalSuppression').style.display='none'">Annuler</button>
                    <button type="submit" name="confirmer_suppression" class="modal-btn btn-confirm">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>