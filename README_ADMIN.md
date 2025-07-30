# Configuration de l'utilisateur Admin

## 🔧 Configuration

L'utilisateur administrateur est créé automatiquement via un seeder qui utilise les variables d'environnement.

### Variables d'environnement à configurer

Dans votre fichier `.env`, définissez :

```env
# Admin User Configuration
ADMIN_NAME="Votre Nom"
ADMIN_EMAIL="votre-email@example.com"
ADMIN_PASSWORD="votre-mot-de-passe-securise"
```

## 🚀 Installation

### 1. Copier le fichier d'environnement
```bash
cp .env.example .env
```

### 2. Configurer vos informations admin
Modifiez les variables `ADMIN_*` dans le fichier `.env` avec vos informations.

### 3. Générer la clé d'application
```bash
./vendor/bin/sail artisan key:generate
```

### 4. Lancer les migrations et seeders
```bash
./vendor/bin/sail artisan migrate --seed
```

## 🔑 Connexion à l'administration

Une fois configuré, vous pouvez accéder à l'interface d'administration :

- **URL** : http://localhost/admin
- **Email** : Celui défini dans `ADMIN_EMAIL`
- **Mot de passe** : Celui défini dans `ADMIN_PASSWORD`

## ⚡ Re-créer l'utilisateur admin

Si vous devez recréer l'utilisateur admin :

```bash
./vendor/bin/sail artisan db:seed --class=AdminUserSeeder
```

Cette commande utilise `updateOrCreate()`, donc elle met à jour l'utilisateur existant ou en crée un nouveau.

## 🔒 Sécurité

- Utilisez un mot de passe fort pour `ADMIN_PASSWORD`
- Ne commitez jamais le fichier `.env` dans votre dépôt Git
- Le fichier `.env.example` contient des valeurs d'exemple sécurisées