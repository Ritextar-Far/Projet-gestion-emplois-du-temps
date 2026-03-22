<?php
require_once(dirname(__FILE__) . '/../inclus/config.php');
// dirname(__FILE__) = en clair le dossier où db.php se trouve, peu importe qui l'appelle, comme ça le chemin il est pas "corrompu" ou cassé peu importe

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
