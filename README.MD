# EasyFit

Application e-commerce de vêtements de sport, développée avec Symfony 7.4 dans le cadre d'un titre professionnel DWWM.

---

## Fonctionnalités

- Catalogue produits avec catégories (hommes / femmes)
- Variantes produits (taille, couleur) avec gestion du stock
- Système de panier et de commandes
- Authentification utilisateur (inscription, connexion, profil, historique des commandes)
- Avis produits avec note de 1 à 5
- Interface d'administration via EasyAdmin (produits, catégories, utilisateurs, avis)
- Pages statiques (mentions légales, CGV, contact)

---

## Stack technique

| Couche | Technologie |
|---|---|
| Framework | Symfony 7.4 |
| PHP | >= 8.2 |
| ORM | Doctrine ORM 3.6 + Migrations |
| Base de données | MySQL / MariaDB |
| Admin | EasyAdmin Bundle 5.0 |
| Templates | Twig 3.0 |
| CSS | Tailwind CSS |

---

## Prérequis

- PHP >= 8.2
- Composer
- MySQL ou MariaDB
- Symfony CLI (optionnel mais recommandé)

---

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/guillaumebertil/easyfit.git
cd easyfit
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configurer l'environnement

Copier le fichier d'exemple et renseigner les variables :

```bash
cp .env .env.local
```

Modifier `.env.local` et adapter la ligne `DATABASE_URL` :

```dotenv
DATABASE_URL="mysql://utilisateur:motdepasse@127.0.0.1:3306/easyfit?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
```

### 4. Créer la base de données et appliquer les migrations

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5. Compiler les assets Tailwind

```bash
php bin/console tailwind:build
```

> En développement, utilisez `--watch` pour recompiler automatiquement à chaque modification :
>
> ```bash
> php bin/console tailwind:build --watch
> ```

### 6. Lancer le serveur de développement

Avec la Symfony CLI :

```bash
symfony server:start
```

Ou avec le serveur PHP intégré :

```bash
php -S localhost:8000 -t public/
```

L'application est accessible sur [http://localhost:8000](http://localhost:8000).

---

## Commandes utiles

```bash
# Vider le cache
php bin/console cache:clear

# Créer une migration après modification d'une entité
php bin/console make:migration

# Appliquer les migrations
php bin/console doctrine:migrations:migrate

# Lister les routes
php bin/console debug:router
```

---

## Structure du projet

```
easyfit/
├── assets/              # Sources CSS/JS (Tailwind, Stimulus)
├── config/              # Configuration Symfony (packages, routes, sécurité)
├── migrations/          # Migrations Doctrine
├── public/              # Racine web (index.php, assets compilés)
├── src/
│   ├── Controller/      # Contrôleurs (frontend + admin)
│   ├── Entity/          # Entités Doctrine
│   ├── Form/            # Types de formulaires Symfony
│   ├── Repository/      # Requêtes personnalisées
│   ├── Enum/            # Énumérations (statut de commande)
│   └── Twig/            # Extensions Twig
├── templates/           # Templates Twig
├── .env                 # Variables d'environnement (ne pas committer les secrets)
├── composer.json
└── tailwind.config.js
```

---

## Accès administration

L'interface d'administration est disponible à l'adresse `/admin`.

Pour créer un compte administrateur, modifier le rôle d'un utilisateur existant directement en base de données :

```sql
UPDATE user SET roles = '["ROLE_ADMIN"]' WHERE email = 'votre@email.com';
```

Ou via la console Symfony si un fixture ou une commande dédiée est disponible.

---

## Contrôle d'accès

| Zone | Rôle requis |
|---|---|
| `/admin` | `ROLE_ADMIN` |
| `/account`, `/cart`, `/order` | `ROLE_USER` |
| Catalogue, pages statiques | Public |

---

## Auteur

Guillaume Bertil — Projet réalisé dans le cadre du titre professionnel Développeur Web et Web Mobile (DWWM).
