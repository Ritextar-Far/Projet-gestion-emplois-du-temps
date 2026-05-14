<?php
$message = "";

$db = new PDO('mysql:host=localhost;dbname=gestion_licence;charset=utf8mb4', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enregistrer'])) {
    $code = $_POST['code'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $heures = $_POST['heures'] ?? 0;
    $parent = !empty($_POST['parent']) ? $_POST['parent'] : null; 
    $description = $_POST['description'] ?? '';
    $projet_fil_rouge = isset($_POST['projet_fil_rouge']) ? 1 : 0;

    try {
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
    } catch (Exception $e) {
        $message = "Erreur lors de l'ajout : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module </title>
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
            <a href="type_intervention.php">Modules</a>
        </nav>

        <h1 class="page-title">Module</h1>

        <?php if($message) echo "<p class='alert'>$message</p>"; ?>

        <form action="" method="POST" class="module-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Code - champ obligatoire</label>
                    <input type="text" name="code" placeholder="TAILWIND_CSS" required>
                </div>
                <div class="form-group">
                    <label>Nom - champ obligatoire</label>
                    <input type="text" name="nom" placeholder="Tailwind CSS" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nombre d'heures</label>
                    <input type="number" name="heures" value="14">
                </div>
                <div class="form-group">
                    <label>Parent</label>
                    <div class="select-wrapper">
                        <select name="parent">
                            <option value="Développement front">Développement front</option>
                            <option value="Développement back">Développement back</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group full-width">
                <label>Description - champ obligatoire</label>
                <textarea name="description" rows="4" required>Piloter un projet informatique</textarea>
            </div>

            <div class="toggle-container">
                <label class="switch">
                    <input type="checkbox" name="projet_fil_rouge" checked>
                    <span class="slider round"></span>
                </label>
                <span class="toggle-text">Module effectué sur le projet fil rouge</span>
            </div>

            <div class="form-actions">
                <a href="modules.php" class="btn btn-secondary">Retour à la liste</a>
                <button type="button" class="btn btn-danger">Supprimer</button>
                <button type="submit" name="enregistrer" class="btn btn-primary">Enregistrer les informations</button>
            </div>
        </form>
    </main>
    
</body>
</html>
