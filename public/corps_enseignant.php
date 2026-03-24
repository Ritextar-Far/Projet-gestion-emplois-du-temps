<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corps Enseignant</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/corpsenseignant.css">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Quicksand:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body class="corps-enseignant">
    <?php require_once '../inclus/header.php'; ?>
    <main class="contenu">

        <!-- Fil d'Ariane -->
        <nav class="fil">
            <a href="index.php">
                <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7A1 1 0 002.707 10H4v7a1 1 0 001 1h4v-5h2v5h4a1 1 0 001-1v-7h1.293a1 1 0 00.707-1.707l-7-7z"/>
                </svg>
            </a>
            <span>›</span>
            <a href="corps_enseignant.php">Corps Enseignant</a>
        </nav>

        <!-- En-tête -->
        <div class="header-section">
            <h1>Corps Enseignant</h1>
            <a href="Ajouter_Corps_Enseignant.php" class="btn-ajouter">Ajouter un Corps Enseignant</a>
        </div>

        <!-- Filtres -->
        <div class="carte-filtres">
            <div class="etiquette-filtres">Filtres</div>
            <div class="ligne-filtres">
                <div class="groupe-filtre">
                    <label for="nom">Nom de famille</label>
                    <input id="nom" type="text" placeholder="Saisissez le nom de famille">
                </div>
                <div class="groupe-filtre">
                    <label for="prenom">Prénom</label>
                    <input id="prenom" type="text" placeholder="Saisissez le prénom">
                </div>
                <div class="groupe-filtre">
                    <label for="email">Email</label>
                    <input id="email" type="text" placeholder="Saisissez l'email">
                </div>
                <button class="bouton-filtrer">Filtrer</button>
            </div>
        </div>

        <!-- Résultats -->
        <div class="nombre-resultats">1 enseignant trouvé</div>

        <!-- Tableau -->
        <div class="enveloppe-tableau">
            <table>
                <thead>
                    <tr>
                        <th>Nom de famille</th>
                        <th>Prénom</th>
                        <th>Modules enseignés</th>
                        <th>Nombre d'heures</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Martins-Jacquelot</td>
                        <td>Jeff</td>
                        <td class="cellule-modules">
                            Git, Environnement de travail,<br>
                            Environnement de production, Monitorer<br>
                            une base de données + performance
                        </td>
                        <td class="cellule-heures">72h</td>
                        <td class="cellule-action">
                            <a href="fiche_enseignant.php" class="bouton-voir">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                                Accéder à la fiche
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>
</body>
</html>
