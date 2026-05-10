<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/modules.css">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Quicksand:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body class="corps-enseignant">
    <?php require_once '../inclus/header.php'; ?>
    <main class="contenu">
        <nav class="fil">
            <a href="index.php"><img src="assets/images/home.svg"></a>
            <span>›</span>
            <a href="type_intervention.php">Modules</a>
        </nav>
        <p class="titre">Modules</p>
    
        
        
        <?php $pdo = new PDO('mysql:host=localhost;dbname=gestion_licence;charset=utf8mb4', 'root', '');

        $stmt = $pdo->query("SELECT * FROM module WHERE parent_id IS NULL ORDER BY name");
        $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);?>

        <div class="liste-module">
            <?php foreach ($modules as $module): ?>
            <li>
                <?= htmlspecialchars($module['name']) ?>
                <?php if ($module['hours_count']): ?>
                    (<?= $module['hours_count'] ?>h)
                <?php endif; ?>
            </li>

            <?php $stmt2 = $pdo->prepare("SELECT * FROM module WHERE parent_id = 'Devops et Cybersecurité' ORDER BY name");
            $sousModules = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            foreach ($sousModules as $sub): ?>
                <ul>
                    <li><?= htmlspecialchars($sub['name']) ?></li>
                </ul>
            <?php endforeach; ?>

            <?php endforeach; ?>
        </div>




        <button onclick="window.location.href='fiche-module.php'" class="bouton-ajouter">Ajouter un module</button>
    </main>
</body>
</html>
