## 🚀 Getting Started

### 1. Installer le certificat SSL
Certaines librairies (WorkOS, cURL) nécessitent un certificat racine.  
- Télécharger le fichier **cacert.pem** depuis [curl.se/ca/cacert.pem](https://curl.se/ca/cacert.pem)  
- Le placer dans un dossier accessible (ex. `C:/php/extras/ssl/cacert.pem`)  
- Mettre à jour ton `php.ini` :

```ini
[curl]
curl.cainfo = "C:/php/extras/ssl/cacert.pem"

[openssl]
openssl.cafile = "C:/php/extras/ssl/cacert.pem"
```

* Redémarrer ton serveur PHP / Laravel (`php artisan serve` ou Apache/Nginx)

---

### 2. Installer les dépendances

Frontend (Node.js / npm) :

```bash
npm install
```

Backend (PHP / Composer) :

```bash
composer install
```

---

### 3. Configurer WorkOS

* Créer un compte sur [WorkOS](https://workos.com)
* Récupérer la **clé API** (API Key) et la **clé de configuration (Client ID)**
* Les ajouter à ton `.env` :

```env
WORKOS_API_KEY=sk_test_************
WORKOS_CLIENT_ID=client_************
```

---

### 4. Configurer la base de données

Le projet utilise **MariaDB 11.x**.  
Mettre à jour le fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_de_ta_base
DB_USERNAME=ton_utilisateur
DB_PASSWORD=ton_mot_de_passe
```

Puis exécuter les migrations :

```bash
php artisan migrate
```

---

### 5. Configurer Redis & Broadcasting

Le projet utilise **Redis** pour le caching et le broadcasting en temps réel via Laravel Reverbs.

- Installer Redis si nécessaire ([Documentation officielle](https://redis.io/docs/getting-started/))  
- Configurer `.env` :

```env
BROADCAST_DRIVER=redis
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

- Lancer le serveur de queue Laravel pour gérer le broadcasting :

```bash
php artisan queue:work
```

---

### 6. Lancer le projet

Démarrer le backend :

```bash
php artisan serve
```

Démarrer le frontend :

```bash
npm run dev
```

---

# 🚀 INTEGRATION DE SYSTEM CODING IN PROD

## 🧩 Présentation

Ce module ajoute à votre application Laravel un **explorateur et éditeur de code intégré** directement dans l’interface web,  
avec gestion automatique du **versionnement Git** (commit + push) et **notifications en temps réel via Redis/ broadcasting**.

L’objectif est de permettre une **modification rapide et contrôlée du code en production ou en préproduction**,  
tout en garantissant la traçabilité via Git.

---

## ⚙️ Fonctionnalités principales

- 🗂️ Navigation dans l’arborescence du projet Laravel  
- 📝 Édition directe des fichiers avec **Ace Editor**  
- 💾 Sauvegarde instantanée via AJAX  
- 🧠 Commit et Push Git automatiques (`git add`, `git commit`, `git push`)  
- 🔔 Notification en temps réel des modifications via **Redis & Laravel Broadcasting**  
- 🧱 Protection contre les accès hors du répertoire du projet  
- 🔐 Sécurisé par `auth` et `MiddlewareValidateSessionWithWorkOS`  
- 🧭 Breadcrumb interactif pour naviguer dans l’arborescence  
- 🧰 Message de commit personnalisable avant chaque sauvegarde  

---

