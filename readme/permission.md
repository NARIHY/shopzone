### 🧩 Hiérarchie des rôles (e-commerce complet)

| Rôle             | Description                                                                                                                  | Niveau d’accès                              |
| ---------------- | ---------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------- |
| **Super Admin**  | Plein accès (peut tout faire, y compris gestion des rôles et création d’admins)                                              | 🔥 Full access                              |
| **Admin**        | Gestion complète du site (produits, commandes, utilisateurs, mais pas le Super Admin)                                        | ⚙️ Accès total sauf super-administration    |
| **Propriétaire** | Directeur / propriétaire du magasin : observe, peut modifier les produits, mais ne touche pas à la gestion technique du site | 👁️ Accès limité mais large sur la boutique |
| **Modérateur**   | Gère le contenu (produits, avis, messages, réponses clients)                                                                 | 🛠️ Gestion du contenu                      |
| **Vendeur**      | Gère ses produits et commandes uniquement                                                                                    | 💼 Gestion opérationnelle                   |
| **Client**       | Peut consulter, acheter, gérer son profil                                                                                    | 🛒 Usage final                              |

---

### 🧱 Structure des tables (supposée)

* `roles(id, name)`
* `permissions(id, name)`
* `role_permissions(role_id, permission_id)`

---

### 🧩 Catégorisation logique des permissions

| Domaine                            | Exemples de permissions (`permissions.name`)                           |
| ---------------------------------- | ---------------------------------------------------------------------- |
| **Produits**                       | `view_products`, `create_products`, `edit_products`, `delete_products` |
| **Commandes**                      | `view_orders`, `create_orders`, `update_orders`, `delete_orders`       |
| **Utilisateurs**                   | `view_users`, `create_users`, `update_users`, `delete_users`           |
| **Rôles & Permissions**            | `view_roles`, `create_roles`, `update_roles`, `delete_roles`           |
| **Contenu (avis, messages, etc.)** | `view_reviews`, `reply_reviews`, `view_messages`, `reply_messages`     |
| **Profil personnel**               | `view_profile`, `update_profile`                                       |

---

### 🧠 Règles d’attribution (logique e-commerce)

| Rôle             | Permissions attribuées                                                             |
| ---------------- | ---------------------------------------------------------------------------------- |
| **Super Admin**  | ✅ Toutes les permissions (`SELECT * FROM permissions`)                             |
| **Admin**        | ✅ Tout sauf : création/suppression de Super Admin, modification de rôles critiques |
| **Propriétaire** | ✅ Lecture complète + gestion des produits & commandes                              |
| **Modérateur**   | ✅ Contenu du site (produits, avis, messages), pas de gestion d’utilisateurs        |
| **Vendeur**      | ✅ Ses produits et commandes uniquement                                             |
| **Client**       | ✅ Lecture produits, création commandes, gestion profil                             |

---

### ⚙️ Script SQL d’affectation automatique (générique)

```sql
-- ===================================================================
-- 🎯 Affectation automatique des permissions aux rôles
-- ===================================================================

-- ⚠️ Ajuste les IDs selon ta table roles
-- Exemple :
-- 1 = super_admin, 2 = admin, 3 = proprietaire, 4 = moderateur, 5 = vendeur, 6 = client

-- 1️⃣ SUPER ADMIN → toutes les permissions
INSERT INTO role_permissions (role_id, permission_id)
SELECT 1, id FROM permissions;

-- 2️⃣ ADMIN → toutes sauf la super-administration
INSERT INTO role_permissions (role_id, permission_id)
SELECT 2, id FROM permissions
WHERE name NOT IN ('create_super_admin', 'delete_super_admin');

-- 3️⃣ PROPRIÉTAIRE → lecture complète, produits et commandes
INSERT INTO role_permissions (role_id, permission_id)
SELECT 3, id FROM permissions
WHERE name IN (
    'view_products', 'create_products', 'edit_products',
    'view_orders', 'update_orders',
    'view_users',
    'view_dashboard'
);

-- 4️⃣ MODÉRATEUR → contenu du site
INSERT INTO role_permissions (role_id, permission_id)
SELECT 4, id FROM permissions
WHERE name IN (
    'view_products', 'edit_products',
    'view_reviews', 'reply_reviews',
    'view_messages', 'reply_messages'
);

-- 5️⃣ VENDEUR → produits et commandes (limité)
INSERT INTO role_permissions (role_id, permission_id)
SELECT 5, id FROM permissions
WHERE name IN (
    'view_products', 'create_products', 'edit_products',
    'view_orders', 'create_orders'
);

-- 6️⃣ CLIENT → lecture, achat, profil
INSERT INTO role_permissions (role_id, permission_id)
SELECT 6, id FROM permissions
WHERE name IN (
    'view_products', 'view_orders', 'create_orders',
    'view_profile', 'update_profile'
);
```

---

### 🧩 Étape bonus : nettoyage avant insertion (si tu veux éviter les doublons)

```sql
DELETE FROM role_permissions;
```

---

