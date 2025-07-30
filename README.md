# Quiz Filament - Laravel avec Sail

## 🐳 Démarrage avec Docker Sail

### Prérequis
- Docker Desktop installé et lancé
- Git

### Installation

```bash
# 1. Cloner le projet
git clone <repository-url>
cd quiz-filament

# 2. Copier les variables d'environnement
cp .env.example .env

# 3. Démarrer les conteneurs Docker
./sail up -d

# 4. Installer les dépendances
./sail composer install

# 5. Générer la clé d'application
./sail artisan key:generate

# 6. Lancer les migrations et seeders
./sail artisan migrate
./sail artisan db:seed --class=QuizSeeder

# 7. Créer un utilisateur admin Filament
./sail artisan make:filament-user

# 8. Installer les dépendances front-end
./sail npm install
./sail npm run build
```

### Accès à l'application

- **Application** : http://localhost
- **Interface Admin Filament** : http://localhost/admin
- **MySQL** (port 3306) : accessible via localhost:3306
  - User: sail
  - Password: password
  - Database: quiz_filament

### Commandes courantes

```bash
# Démarrer les services
./sail up -d

# Arrêter les services
./sail down

# Voir les logs
./sail logs

# Accéder au shell du conteneur
./sail shell

# Lancer Artisan
./sail artisan migrate
./sail artisan tinker

# Composer
./sail composer install
./sail composer update

# NPM
./sail npm install
./sail npm run dev
./sail npm run build

# Accéder à MySQL
./sail mysql
```

### Configuration de base

Le projet est configuré avec :
- **PHP 8.4**
- **MySQL 8.0**
- **Node.js 22**
- **Laravel 12**
- **Filament v3**

### Structure des données

- **Thèmes** : Catégories de questions
- **Questions** : Questions avec 9 bonnes réponses + 1 piège
- **Sessions** : Parties de quiz multijoueurs
- **Joueurs** : Participants avec scores

### Development

```bash
# Mode développement avec hot reload
./sail npm run dev

# Tests
./sail artisan test

# Code style
./sail composer pint

# Optimisations
./sail artisan optimize
./sail artisan config:cache
./sail artisan route:cache
```

### Troubleshooting

**Les conteneurs ne démarrent pas :**
```bash
./sail down
docker system prune -a
./sail up -d
```

**Problème de permissions :**
```bash
./sail down
sudo chown -R $USER:$USER .
./sail up -d
```

**Base de données corrompue :**
```bash
./sail down -v  # Supprime les volumes
./sail up -d
./sail artisan migrate:fresh --seed
```

### Production

Pour le déploiement en production, utiliser les images Docker optimisées ou un hébergeur supportant Laravel comme Laravel Forge, DigitalOcean App Platform, etc.

---

**Développé par Franck** 🚀  
*Quiz multijoueurs moderne avec Laravel & Filament*