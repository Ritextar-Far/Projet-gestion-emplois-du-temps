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

- PHP 7.4 ou supérieur
- MySQL
- Un serveur local (XAMPP, WAMP, MAMP ou équivalent)

### Installation

1. Cloner le dépôt :

   ```bash
   git clone https://github.com/Ritextar-Far/Projet-gestion-emplois-du-temps
   ```

2. Placer le dossier `gestion-licence/` dans le répertoire de votre serveur local (ex : `htdocs/` pour XAMPP).

3. Importer la base de données :
   - Créer une base de données MySQL
   - Importer le fichier SQL fourni

4. Configurer la connexion à la base de données dans le fichier `config.php`.

5. Accéder à l'application via `http://localhost/gestion-licence/`.

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
│   ├── enseignants.php
│   ├── enseignant_fiche.php
│   ├── modules.php
│   ├── module_fiche.php
│   ├── types.php
│   └── type_fiche.php
│
├── inclus/                    ← Morceaux réutilisables
│   ├── header.php               ← <head> + navbar
│   ├── footer.php
│   └── auth_check.php           ← Vérifie si connecté (inclus en haut de chaque page)
│
├── functions/                   ← Fonctions BDD par thème
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
└── config.php                ← Paramètres de connexion à la base de données (hôte, nom, utilisateur, mot de passe)

```

## Conventions

### Nommage des fichiers

- Les fichiers PHP des pages portent le nom de la fonctionnalité correspondante (ex : `calendrier.php`, `enseignants.php`).
- Les fiches de modification/ajout suivent le format `entité_fiche.php` (ex : `module_fiche.php`).
- Les actions spécifiques suivent le format `entité_action.php` (ex : `intervention_ajout.php`).

### Organisation du code

- **`pages/`** — Chaque page de l'application correspond à un fichier PHP unique.
- **`inclus/`** — Contient les éléments réutilisables inclus dans les pages (header, footer, vérification d'authentification).
- **`functions/`** — Contient les fonctions d'accès à la base de données, regroupées par thème.
- **`assets/`** — Contient les fichiers statiques (CSS, JS, images).

## Organisation

_En cours_

## Ressources

- Maquette Figma : [Enseignement supérieur](https://www.figma.com/design/AJZ4aDQO3E0v3PW0HmlhmR/Enseignement-sup%C3%A9rieur---light?node-id=0-1&p=f)
