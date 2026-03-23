<?php
session_start();
$error = '';
require_once '../fonctions/db.php';
require_once '../database/requeteconnexion.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion au portail</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Quicksand:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>
<body class="pageconnexion">
<div class="page">
    <aside class="barre-gauche">
        <img src="assets/images/logo.png" alt="Logo établissement" class="logo">
        <div class="text-logo">
            <p class="gros-text">Lycée Saint-vincent</p>
            <p class="sous-titre">Enseignement supérieur</p>
        </div>
    </aside>
    <main class="contenu">
        <h1 class="titre-principal">Gestion du supérieur</h1>
        <section class="bloc-connexion">
            <h2 class="titre-connexion">Connexion au portail</h2>

            <?php if ($error): ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form class="formulaire-connexion" method="POST" action="">
                <div class="champ">
                    <label>Email - champ obligatoire</label>
                    <input type="email" name="email" placeholder="ribas@gmail.com">
                </div>
                <div class="champ">
                    <label>Mot de passe - champ obligatoire</label>
                    <input type="password" name="password" placeholder="**********">
                </div>
                <button type="submit" class="bouton-connexion">
                    Accéder au portail
                </button>
            </form>
        </section>
    </main>
</div>
</body>
</html>
