# 🍳 HelloRecettes

![Symfony](https://img.shields.io/badge/Symfony-7.4-000000?style=for-the-badge&logo=symfony&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

## 🎯 Plateforme collaborative de partage de recettes culinaires

### Créez, partagez et découvrez des recettes avec une communauté passionnée de cuisine

---

## 📋 À propos

**HelloRecettes** est une application web moderne de gestion de recettes culinaires qui permet aux utilisateurs de :

- 📝 **Créer et gérer** leurs propres recettes avec ingrédients
- 🌍 **Partager** leurs créations avec la communauté
- ⭐ **Noter et découvrir** les recettes publiques
- 📸 **Illustrer** leurs plats avec des photos
- 🔒 **Garder privées** certaines recettes ou les rendre publiques
- 👤 **Gérer leur profil** avec pseudo ou en anonyme

## ✨ Fonctionnalités principales

### Pour les utilisateurs

- ✅ Système d'authentification sécurisé
- 🎨 Interface moderne et responsive avec design orange/dark
- 🥕 Gestion complète des ingrédients
- 👨‍🍳 Création de recettes détaillées (temps, difficulté, nombre de personnes, prix)
- ⭐ Système de favoris et de notation communautaire
- 📷 Upload de photos pour les recettes
- 🔍 Recherche et filtrage de recettes publiques
- 💬 Formulaire de contact

### Administration

- 🛡️ **Back-office EasyAdmin** pour la gestion complète :
  - CRUD des ingrédients
  - CRUD des recettes
  - CRUD des utilisateurs
  - Vue d'ensemble des statistiques

### API REST

- 🚀 **API Platform** Open Source pour les développeurs
- 📚 Documentation interactive Swagger/OpenAPI
- 🔓 Accès public aux recettes partagées
- 🔗 Endpoint : `/api`

## 🛠️ Technologies

### Backend

- **[Symfony 7.4](https://symfony.com/)** - Framework PHP moderne
- **PHP 8.3** - Langage serveur
- **Doctrine ORM** - Gestion de base de données
- **API Platform** - Création d'API REST
- **EasyAdmin** - Interface d'administration

### Frontend

- **Twig** - Moteur de templates
- **Bootstrap 5** (Bootswatch Slate) - Framework CSS
- **CSS personnalisé** - Design orange/dark moderne

### Outils de développement

- **[Composer](https://getcomposer.org/)** - Gestionnaire de dépendances PHP
- **[Symfony CLI](https://symfony.com/download)** - Outil en ligne de commande
- **[Faker](https://fakerphp.github.io/)** - Génération de données de test
- **[PHPUnit](https://phpunit.de/)** - Tests unitaires et fonctionnels
- **[Rector](https://getrector.org/)** - Refactoring automatisé
- **[Mailtrap](https://mailtrap.io/)** - Test d'envoi d'emails

## 📦 Installation

### Prérequis

- PHP 8.3 ou supérieur
- Composer
- Symfony CLI
- MySQL/MariaDB ou PostgreSQL

### Étapes d'installation

```bash
# Cloner le repository
git clone https://github.com/Caro639/HelloRecettes.git
cd HelloRecettes

# Installer les dépendances
composer install

# Configurer les variables d'environnement
cp .env .env.local
# Éditer .env.local avec vos paramètres de base de données

# Créer la base de données
php bin/console doctrine:database:create

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# (Optionnel) Charger les fixtures
php bin/console doctrine:fixtures:load

# Démarrer le serveur de développement
symfony server:start
```

L'application sera accessible sur `https://127.0.0.1:8000`

## 🚀 Utilisation

### Créer un compte administrateur

```bash
php bin/console app:create-admin
```

### Lancer les tests

```bash
php bin/phpunit
```

### Accéder aux différentes interfaces

- 🏠 **Application** : `https://127.0.0.1:8000`
- 🛡️ **Admin** : `https://127.0.0.1:8000/admin`
- 🔌 **API** : `https://127.0.0.1:8000/api`

## 📸 Captures d'écran

### Interface moderne avec thème sombre et accents orange, design responsive adapté à tous les écrans

## 🧪 Tests

L'application inclut des tests unitaires et fonctionnels :

```bash
# Créer un nouveau test
php bin/console make:test

# Exécuter tous les tests
php bin/phpunit

# Tests avec couverture de code
php bin/phpunit --coverage-html coverage
```

## 🔧 Extensions VS Code recommandées

Pour une meilleure expérience de développement :

- **PHP**

  - PHP IntelliSense (DEVSENSE)
  - PHP Debug (DEVSENSE)
  - PHP Profiler (DEVSENSE)
  - PHP CS Fixer
  - PHP DocBlocker
  - PHP Namespace Resolver

- **Symfony**

  - Symfony Code Snippets
  - Symfony Console

- **Frontend**

  - Twig Language 2
  - Twig Code Snippets

- **Autres**
  - Composer (DEVSENSE)
  - YAML (Red Hat)

## 👨‍💻 Auteur

**Caro639** - [GitHub](https://github.com/Caro639)

---

### Fait avec ❤️ et Symfony
