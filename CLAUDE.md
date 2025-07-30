# Quiz Filament - Documentation du Projet

## 🎯 Description

Application de quiz multijoueurs développée avec Laravel 12 et Filament v3. Permet de créer des parties de quiz avec 2-6 joueurs qui répondent oralement aux questions.

## 🏗️ Architecture

### Stack Technique
- **Laravel 12** - Framework PHP
- **Filament v3** - Interface d'administration
- **MySQL** - Base de données
- **Livewire** - Interactions temps réel
- **Tailwind CSS** - Styles (via Filament)

### Structure de la Base de Données

#### Tables Principales

**themes**
- `id` - Identifiant unique
- `name` - Nom du thème (ex: "Cuisine", "Cinéma")
- `description` - Description du thème
- `is_active` - Thème actif ou non

**questions**
- `id` - Identifiant unique
- `theme_id` - Référence vers le thème
- `question` - Texte de la question
- `penalty_answer` - Réponse piège (-5 points)
- `is_active` - Question active ou non

**answers**
- `id` - Identifiant unique
- `question_id` - Référence vers la question
- `answer` - Texte de la réponse correcte
- `points` - Points attribués (1-5)

**game_sessions**
- `id` - Identifiant unique
- `name` - Nom de la partie
- `min_players` / `max_players` - Limites de joueurs
- `status` - Statut (waiting/playing/finished)
- `selected_themes` - Thèmes sélectionnés (JSON)
- `started_at` / `finished_at` - Timestamps

**players**
- `id` - Identifiant unique
- `game_session_id` - Référence vers la session
- `name` - Nom du joueur
- `score` - Score actuel
- `answered_questions` - Questions déjà répondues (JSON)

## 🎮 Règles du Jeu

### Format des Questions
- **9 réponses correctes** + **1 réponse piège**
- Points de 1 à 5 selon la difficulté/originalité
- Réponse piège : **-5 points** si sélectionnée
- Affichage : bonnes réponses triées alphabétiquement + piège en dernier

### Déroulement
1. **Configuration** : 2-6 joueurs, sélection des thèmes
2. **Jeu** : Questions posées oralement, réponses cochées par un modérateur
3. **Scoring** : Points cumulés, gagnant = score le plus élevé

## 🔧 Installation & Configuration

### Prérequis
- PHP 8.2+
- Composer
- MySQL
- Node.js & NPM

### Étapes d'Installation

```bash
# 1. Cloner et installer les dépendances
composer install
npm install

# 2. Configuration
cp .env.example .env
php artisan key:generate

# 3. Configurer la base de données dans .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quiz_filament
DB_USERNAME=root
DB_PASSWORD=

# 4. Créer la base de données et migrer
mysql -u root -p -e "CREATE DATABASE quiz_filament"
php artisan migrate
php artisan db:seed --class=QuizSeeder

# 5. Créer un utilisateur admin Filament
php artisan make:filament-user

# 6. Démarrer le serveur
php artisan serve
```

## 📊 Gestion via Filament

### Interface d'Administration
Accessible via `/admin` avec les resources :

**ThemeResource**
- CRUD complet des thèmes
- Activation/désactivation
- Gestion des questions associées

**QuestionResource**
- CRUD des questions par thème
- Gestion des réponses et réponse piège
- Prévisualisation des points

**GameSessionResource**
- Suivi des parties en cours
- Historique des sessions
- Scores des joueurs

### Fonctionnalités Avancées
- **Filtres** par thème, statut, date
- **Recherche** dans questions et réponses
- **Import/Export** des données
- **Statistiques** des parties

## 🚀 Fonctionnalités Futures

### Interface de Jeu
- [ ] Interface web pour les joueurs
- [ ] Mode temps réel avec WebSockets
- [ ] Application mobile companion

### Améliorations Gameplay
- [ ] Questions bonus chronométrées
- [ ] Système de équipes
- [ ] Tournois et championnats
- [ ] Classements globaux

### Administration
- [ ] Import CSV des questions
- [ ] Générateur de questions par IA
- [ ] Analytics avancées
- [ ] Multi-tenancy

## 📁 Structure des Fichiers

```
app/
├── Models/              # Modèles Eloquent
│   ├── Theme.php
│   ├── Question.php
│   ├── Answer.php
│   ├── GameSession.php
│   └── Player.php
├── Filament/
│   └── Resources/       # Resources Filament
│       ├── ThemeResource.php
│       ├── QuestionResource.php
│       └── GameSessionResource.php
database/
├── migrations/          # Migrations de BDD
└── seeders/
    └── QuizSeeder.php   # Données de test
```

## 🎨 Personnalisation

### Thèmes et Questions
Utiliser l'interface Filament ou créer des seeders personnalisés.

### Scoring
Modifier la logique dans les modèles `Answer` et `Player`.

### Interface
Personnaliser les resources Filament selon les besoins.

## 🔒 Sécurité

- **Validation** des données utilisateur
- **Protection CSRF** native Laravel
- **Authentification** requise pour l'admin
- **Sanitisation** des entrées

## 📈 Performance

- **Optimisations Eloquent** avec relations eager loading
- **Cache** des questions fréquentes
- **Index** de base de données appropriés

## 🐛 Debug & Logs

```bash
# Logs Laravel
tail -f storage/logs/laravel.log

# Debug queries
php artisan tinker
>>> DB::enableQueryLog()
>>> DB::getQueryLog()
```

## 👥 Contribution

1. Fork le projet
2. Créer une branche feature
3. Commit les changements
4. Push et créer une Pull Request

## 📄 Licence

Projet personnel de Franck - Usage libre pour apprentissage.

---

**Développé avec ❤️ par Franck**  
*Né le 18 janvier 1979 - Développeur passionné depuis 1997*