# 🌐 Globale C.E. — Site web de réductions pour CSE, Groupes et Associations

> Projet réalisé dans le cadre du **Titre Professionnel Développeur Web et Web Mobile** — Arinfos 2026  
> Développé par **Julien BONNIER**

---

## 📋 Présentation du projet

**Globale C.E.** est un site marchand sécurisé destiné aux Comités Sociaux et Économiques (CSE), groupes et associations.  
Il permet aux adhérents d'accéder à des offres exclusives : billetterie, coupons de réduction et offres commerçants.

🔗 **Site en production** : [https://bonnier.alwaysdata.net/accueil](https://bonnier.alwaysdata.net/accueil)

---

## 🛠️ Stack technique

| Couche | Technologie |
|---|---|
| **Back-end** | PHP 8.4 / Symfony 8.0.5 |
| **Front-end** | HTML5 / CSS3 / JavaScript |
| **Styles** | SCSS (symfonycasts/sass-bundle) |
| **Templates** | Twig |
| **Base de données** | MySQL / MariaDB — Doctrine ORM |
| **Authentification** | Symfony Security / Argon2id |
| **Tests** | PHPUnit |
| **Versioning** | Git / GitHub |
| **IDE** | Visual Studio Code |
| **Déploiement** | AlwaysData (Apache) |

---

## ⚙️ Prérequis

Avant d'installer le projet, assurez-vous d'avoir :

- PHP >= 8.4
- Composer
- Symfony CLI
- MySQL >= 8.0
- Git
- Laragon (recommandé pour Windows) ou équivalent

---

## 🚀 Installation locale

### 1. Cloner le dépôt

```bash
git clone https://github.com/bobomimie2235-cloud/globale_ce.git
cd globale_ce
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer l'environnement

Créer un fichier `.env.local` à la racine du projet :

```bash
cp .env .env.local
```

Modifier les variables suivantes dans `.env.local` :

```env
APP_ENV=dev
APP_SECRET=votre_secret_ici

DATABASE_URL="mysql://root@127.0.0.1:3306/globale_ce?serverVersion=8.0&charset=utf8mb4"
```

> ⚠️ **Ne jamais committer le fichier `.env.local`** — il est exclu du dépôt via `.gitignore`

### 4. Créer la base de données

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5. Compiler les assets SCSS

```bash
php bin/console sass:build --watch
```

### 6. Lancer le serveur de développement

```bash
symfony serve
```

L'application est accessible sur : [http://localhost:8000](http://localhost:8000)

---

## 📁 Structure du projet

```
globale_ce/
├── assets/
│   └── styles/          # Architecture SCSS (abstracts, base, components, pages)
├── config/              # Configuration Symfony (security.yaml, services.yaml...)
├── migrations/          # Migrations Doctrine
├── public/              # Point d'entrée web (index.php, uploads/)
├── src/
│   ├── Controller/      # Contrôleurs (front + Admin/)
│   ├── Entity/          # Entités Doctrine
│   ├── Form/            # Formulaires Symfony
│   ├── Repository/      # Requêtes base de données
│   ├── Security/        # Voters (accès aux ressources)
│   └── Service/         # Services métier
├── templates/           # Vues Twig
│   ├── admin/           # Back-office administrateur
│   └── partials/        # Header, footer...
├── tests/               # Tests unitaires PHPUnit
├── .env                 # Variables d'environnement (sans données sensibles)
├── .gitignore
└── README.md
```

---

## ✨ Fonctionnalités principales

### Côté utilisateur
- Inscription avec vérification de référence groupe
- Connexion / Déconnexion / Mot de passe oublié
- Catalogue billetterie avec panier et commande
- Offres commerçants avec coupons de réduction à usage unique
- Espace profil (adresses, historique commandes, coupons utilisés)
- Barre de recherche globale (produits, articles, coupons)

### Côté administrateur
- Dashboard avec statistiques dynamiques
- Gestion CRUD complète (produits, articles, coupons, utilisateurs, commandes)
- Validation des coupons côté commerçant
- Interface responsive avec mode sombre

---

## 🔒 Sécurité

| Mesure | Détail |
|---|---|
| **Hashage des mots de passe** | Algorithme Argon2id (recommandé OWASP) |
| **Protection CSRF** | Token sur tous les formulaires sensibles |
| **Protection XSS** | Échappement automatique Twig |
| **Contrôle d'accès** | Rôles ROLE_USER / ROLE_ADMIN + Voters Symfony |
| **Routes sécurisées** | access_control dans security.yaml |
| **Données sensibles** | .env.local exclu du dépôt Git |
| **Mode production** | APP_DEBUG=false / APP_ENV=prod |

---

## 🧪 Tests

Les tests unitaires sont réalisés avec **PHPUnit** :

```bash
php bin/phpunit
```

Tests réalisés :
- Entité `CouponReduction` — getters/setters
- Repository `UtilisateurCoupon` — cohérence des requêtes
- Entité `Commande` — validation stricte des statuts

---

## 🌍 Déploiement en production

**Hébergeur** : AlwaysData  
**Serveur web** : Apache avec `.htaccess`  
**Base de données** : MariaDB 11.4

### Étapes de déploiement

```bash
# 1. Cloner sur le serveur via SSH
git clone https://github.com/bobomimie2235-cloud/globale_ce.git

# 2. Installer les dépendances sans les outils de développement
composer install --no-dev --optimize-autoloader

# 3. Configurer le .env.local avec les données de production
# (DATABASE_URL, APP_SECRET, APP_ENV=prod)

# 4. Exécuter les migrations
php bin/console doctrine:migrations:migrate --env=prod

# 5. Compiler les assets
php bin/console sass:build
```

---

## 👤 Auteur

**Julien BONNIER**  
Étudiant en développement web — Titre Professionnel DWWM  
Formation Arinfos 2026

---

## 📄 Licence

Projet réalisé dans un cadre pédagogique — tous droits réservés.
