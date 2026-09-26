# Voyalo

Plateforme PHP/MySQL de réservation pour une agence de voyage camerounaise. Les prix sont exprimés en FCFA.

## Démarrage
1. Importer `schema.sql` dans MySQL 8+.
2. Copier `config/config.example.php` en `config/config.php` et renseigner les identifiants MySQL.
3. Servir ce dossier avec PHP 8.1+ (ex. `php -S localhost:8000`) et ouvrir `/`.
4. Ouvrir `/admin/setup.php` (par exemple `http://localhost/Voyalo/admin/setup.php`) et choisir les identifiants du premier administrateur. Cette page se désactive dès qu'un rôle administrateur existe.
5. Se connecter : le tableau de bord admin permet de gérer hôtels, chambres, trajets et horaires, circuits et dates de départ, promotions, utilisateurs et agents. Les images acceptées sont JPG, PNG ou WebP (4 Mo maximum).

## Structure
`config/` configuration et PDO; `includes/` sécurité, fonctions, layout; `assets/` CSS/JS; `api/` endpoints JSON; `admin/`, `agent/`, `client/` espaces métier; pages racine pour catalogue, authentification et checkout; `uploads/` médias ajoutés par l'exploitant.

## Modules de cette version
Inscription/connexion sécurisées, configuration du premier administrateur, gestion des catalogues, recherche d'hôtels, trajets et forfaits, réservation avec contrôle de disponibilité, simulation de paiement Orange Money/MTN MoMo/carte, tableau client/agent/admin. La simulation confirme immédiatement le paiement et ne contacte aucun opérateur. Aucun secret de production n'est inclus.
