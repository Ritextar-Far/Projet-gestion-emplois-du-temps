# Gestion Licence

Une plateforme web permettant de gérer le calendrier des interventions pédagogiques d'une promotion de licence.

## Présentation du projet

Ce projet est réalisé dans le cadre du **projet final de BTS SIO 1ère année** par un groupe de **4 personnes**.

L'application permet à l'équipe administrative de centraliser et faciliter la gestion du planning des interventions : structurer les enseignements, gérer les intervenants, planifier les cours et visualiser le calendrier de la promotion.

## Technologies utilisées

- **HTML** — Balisage des pages
- **CSS** — Mise en forme
- **JavaScript** — Interactions côté client
- **PHP** — Langage côté serveur
- **MySQL** — Base de données

## Démarrer le projet

### Prérequis

- PHP 8.5
- MySQL
- Un serveur local (XAMPP, WAMP, MAMP ou équivalent)

### Installation

1. Cloner le dépôt :

   ```bash
   git clone https://github.com/Ritextar-Far/Projet-gestion-emplois-du-temps
   ```

2. Placer le dossier `gestion-licence/` dans le répertoire de votre serveur local (ex : `htdocs/` pour XAMPP).

3. Importer la base de données :
   - Aller sur phpmyadmin
   - Importer le fichier SQL fourni dans l'onglet "Importer"
   - Executer.

5. Configurer la connexion à la base de données dans le fichier `config.php`.

6. Accéder à l'application via `http://localhost/gestion-licence/`.

## Arborescence du projet

```
gestion-licence/
│
├── index.php                    ← Redirige vers login ou calendrier
│
├── pages/                       ← Une page = un fichier
│   ├── login.php
│   ├── logout.php
│   ├── calendrier.php
│   ├── interventions.php
│   ├── intervention_ajout.php
│   ├── intervention_modif.php
│   ├── corps_enseignants.php
│   ├── enseignant_fiche.php
│   ├── modules.php
│   ├── module_fiche.php
│   ├── types.php
│   └── type_fiche.php
│
├── public/          
│   ├── Inclus/ 
|       ├── header.php                                  ← <head> + navbar
|       ├── footer.php
|       ├── auth_check.php                              ← Verifie si connecté (inclus en haut de chaque page
|       ├── config.php                                  ← Paramètres de connexion à la base de données (hôte, nom, utilisateur, mot de passe)
|
│   ├── database/                               ← requête php stocké ici
|         ├── requeteconnexion.php              
│         ├── ...
|
│
├── fonctions/                   ← Fonctions BDD par thème
│   ├── db.php                   ← Connexion PDO
│   ├── interventions.php
│   ├── enseignants.php
│   ├── modules.php
│   └── types.php
│
├── assets/
│   ├── css/style.css
│   └── js/script.js
│

```

## Conventions

### Nommage des fichiers

- Les fichiers PHP des pages portent le nom de la fonctionnalité correspondante (ex : `calendrier.php`, `enseignants.php`).
- Les fiches de modification/ajout suivent le format `fiche_type.php` (ex : `module_fiche.php`).
- Les actions spécifiques suivent le format `entité_action.php` (ex : `intervention_ajout.php`).

### Organisation du code

- **`pages/`** — Chaque page de l'application correspond à un fichier PHP unique.
- **`inclus/`** — Contient les éléments réutilisables inclus dans les pages (header, footer, vérification d'authentification).
- **`fonctions/`** — Contient les fonctions d'accès à la base de données, regroupées par thème.
- **`assets/`** — Contient les fichiers statiques (CSS, JS, images).

## Organisation

_En cours_

## Ressources

- Maquette Figma : [Enseignement supérieur](https://www.figma.com/design/AJZ4aDQO3E0v3PW0HmlhmR/Enseignement-sup%C3%A9rieur---light?node-id=0-1&p=f)
