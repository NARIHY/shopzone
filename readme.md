Voici une version améliorée, claire et structurée de ton **README.md** pour la section installation/usage :

````md
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
````

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

### 5. Lancer le projet

Démarrer le backend :

```bash
php artisan serve
```

Démarrer le frontend :

```bash
npm run dev
```

