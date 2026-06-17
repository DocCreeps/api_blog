# 🚀 **API REST & GraphQL pour un Blog - Laravel 13**

> Une **API moderne** pour gérer un blog avec **REST** et **GraphQL**, incluant des fonctionnalités avancées comme l'authentification, les relations entre modèles, les likes, et les commentaires imbriqués.

---

## 🌟 **Fonctionnalités**

### **API REST**

- **Authentification** : Inscription, connexion, déconnexion avec **Sanctum**.
- **Gestion des articles** : CRUD complet avec pagination, filtrage par statut, catégorie, ou tag.
- **Gestion des catégories et tags** : CRUD avec relations Many-to-Many.
- **Commentaires** : Ajout, suppression, et réponses imbriquées.
- **Likes** : Système de likes/dislikes pour les articles et commentaires.
- **Soft Deletes** : Suppression logique pour les articles, commentaires, catégories et tags.
- **Pagination** : Prise en charge native pour toutes les listes.

### **GraphQL** (via Lighthouse)

- **Requêtes flexibles** : Récupération des données avec des filtres avancés (statut, catégorie, recherche).
- **Mutations** : Création, modification, et suppression des articles, commentaires, catégories et tags.
- **Relations** : Chargement des relations (ex: `posts` avec leurs `comments`, `tags`, `category`).
- **Pagination** : Support natif pour les listes paginées.
- **Sécurité** : Protection des mutations avec des **Policies** et **Sanctum**.

---

## 📦 **Technologies utilisées**


| Technologie      | Version | Usage                             |
| ---------------- | ------- | --------------------------------- |
| **Laravel**      | 13      | Framework principal               |
| **Sanctum**      | -       | Authentification API              |
| **Lighthouse**   | -       | GraphQL                           |
| **Eloquent**     | -       | ORM pour les modèles              |
| **Soft Deletes** | -       | Suppression logique               |


---

## 🛠 **Installation**

### **1. Prérequis**

- PHP 8.1+
- Composer 2.5+
- Laravel 13
- Base de données (MySQL, PostgreSQL, SQLite, etc.)

### **2. Cloner le projet**

```bash
git clone https://github.com/tu-projet/blog-api-laravel-13.git
cd blog-api-laravel-13
```

### **3. Installer les dépendances**

```bash
composer install
```

### **4. Configurer l'environnement**

Copie le fichier `.env.example` et renomme-le en `.env` :

```bash
cp .env.example .env
```

Génère une clé d'application :

```bash
php artisan key:generate
```

### **5. Configurer la base de données**

Modifie le fichier `.env` pour configurer ta base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_api
DB_USERNAME=root
DB_PASSWORD=
```

### **6. Exécuter les migrations**

```bash
php artisan migrate
```

### **7. Installer Lighthouse (GraphQL)**

```bash
composer require nuwave/lighthouse
php artisan lighthouse:install
```

### **8. Publier les configurations**

```bash
php artisan vendor:publish --provider="Nuwave\Lighthouse\LighthouseServiceProvider"
```

---

## 📂 **Structure du projet**

```
app/
├── Enums/
│   └── PostStatus.php          # Statuts des articles (DRAFT, PUBLISHED, ARCHIVED)
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── CategoryController.php
│   │   ├── CommentController.php
│   │   ├── CommentLikeController.php
│   │   ├── PostController.php
│   │   ├── PostLikeController.php
│   │   └── TagController.php
│   └── Requests/
│       ├── StoreCommentRequest.php
│       ├── StorePostRequest.php
│       └── UpdatePostRequest.php
├── Models/
│   ├── Category.php
│   ├── Comment.php
│   ├── Like.php
│   ├── Post.php
│   ├── Tag.php
│   └── User.php
├── Policies/
│   ├── CommentPolicy.php
│   └── PostPolicy.php
└── graphql/
    └── schema.graphql          # Schéma GraphQL

routes/
└── api.php                    # Routes API REST

```

---

## 🔌 **API REST - Endpoints**

### **Authentification**


| Méthode | Endpoint    | Description                             |
| ------- | ----------- | --------------------------------------- |
| `POST`  | `/register` | Inscription d'un utilisateur            |
| `POST`  | `/login`    | Connexion d'un utilisateur              |
| `POST`  | `/logout`   | Déconnexion (nécessite un token valide) |


### **Articles**


| Méthode  | Endpoint        | Description                                     |
| -------- | --------------- | ----------------------------------------------- |
| `GET`    | `/posts`        | Liste des articles publiés (paginés)            |
| `GET`    | `/posts/{post}` | Détails d'un article                            |
| `POST`   | `/posts`        | Créer un article (authentification requise)     |
| `PUT`    | `/posts/{post}` | Modifier un article (authentification requise)  |
| `DELETE` | `/posts/{post}` | Supprimer un article (authentification requise) |


### **Catégories**


| Méthode  | Endpoint                 | Description                                        |
| -------- | ------------------------ | -------------------------------------------------- |
| `GET`    | `/categories`            | Liste des catégories                               |
| `GET`    | `/categories/{category}` | Détails d'une catégorie                            |
| `POST`   | `/categories`            | Créer une catégorie (authentification requise)     |
| `PUT`    | `/categories/{category}` | Modifier une catégorie (authentification requise)  |
| `DELETE` | `/categories/{category}` | Supprimer une catégorie (authentification requise) |


### **Tags**


| Méthode  | Endpoint      | Description                                 |
| -------- | ------------- | ------------------------------------------- |
| `GET`    | `/tags`       | Liste des tags                              |
| `GET`    | `/tags/{tag}` | Détails d'un tag                            |
| `POST`   | `/tags`       | Créer un tag (authentification requise)     |
| `PUT`    | `/tags/{tag}` | Modifier un tag (authentification requise)  |
| `DELETE` | `/tags/{tag}` | Supprimer un tag (authentification requise) |


### **Commentaires**


| Méthode  | Endpoint                 | Description                                         |
| -------- | ------------------------ | --------------------------------------------------- |
| `POST`   | `/posts/{post}/comments` | Ajouter un commentaire (authentification requise)   |
| `DELETE` | `/comments/{comment}`    | Supprimer un commentaire (authentification requise) |


### **Likes**


| Méthode | Endpoint                   | Description                                                             |
| ------- | -------------------------- | ----------------------------------------------------------------------- |
| `POST`  | `/posts/{post}/like`       | Ajouter/supprimer un like sur un article (authentification requise)     |
| `POST`  | `/comments/{comment}/like` | Ajouter/supprimer un like sur un commentaire (authentification requise) |


---

## 📡 **GraphQL - Schéma**

### **Types**

- **User** : Utilisateur avec ses articles, commentaires et likes.
- **Post** : Article avec son auteur, catégorie, tags, commentaires et likes.
- **Category** : Catégorie avec ses articles.
- **Tag** : Tag avec ses articles.
- **Comment** : Commentaire avec son auteur, article parent, et réponses.

### **Requêtes (Queries)**

```graphql
# Récupérer tous les articles publiés
query {
  posts(status: PUBLISHED) {
    data {
      id
      title
      content
      status
      user {
        id
        name
      }
      category {
        id
        name
      }
      tags {
        id
        name
      }
      comments {
        data {
          id
          content
          user {
            id
            name
          }
        }
      }
    }
    paginatorInfo {
      total
      currentPage
      lastPage
    }
  }
}

# Récupérer un article spécifique
query {
  post(id: 1) {
    id
    title
    content
    user {
      name
    }
    comments {
      data {
        id
        content
        user {
          name
        }
      }
    }
  }
}

# Récupérer le profil de l'utilisateur connecté
query {
  me {
    id
    name
    email
    posts {
      data {
        id
        title
      }
    }
  }
}
```

### **Mutations**

```graphql
# Créer un article
mutation {
  createPost(
    title: "Mon premier article"
    content: "Contenu de mon article..."
    category_id: 1
    status: PUBLISHED
    tags: [1, 2]
  ) {
    id
    title
    status
  }
}

# Modifier un article
mutation {
  updatePost(
    id: 1
    title: "Titre mis à jour"
    content: "Contenu mis à jour..."
  ) {
    id
    title
    content
  }
}

# Supprimer un article
mutation {
  deletePost(id: 1) {
    id
  }
}

# Ajouter un commentaire
mutation {
  createComment(
    post_id: 1
    content: "Super article !"
  ) {
    id
    content
  }
}

# Ajouter un like à un article
mutation {
  likePost(id: 1) {
    id
  }
}
```

---

## 🔐 **Authentification**

### **Sanctum**

- **Inscription** : `POST /register` avec `name`, `email`, `password`, et `password_confirmation`.
- **Connexion** : `POST /login` avec `email` et `password`. Retourne un **token** à utiliser pour les requêtes protégées.
- **Déconnexion** : `POST /logout` (nécessite un token valide).

### **Utilisation du token**

Pour les requêtes protégées, ajoute le token dans l'en-tête `Authorization` :

```http
Authorization: Bearer {ton_token}
```

---

## 📌 **Exemples d'utilisation**

### **1. Créer un article (REST)**

```bash
curl -X POST \
  http://ton-domaine.test/api/posts \
  -H 'Authorization: Bearer {ton_token}' \
  -H 'Content-Type: application/json' \
  -d '{
    "title": "Mon premier article",
    "content": "Contenu de mon article...",
    "category_id": 1,
    "status": "published",
    "tags": [1, 2]
  }'
```

### **2. Récupérer un article avec ses relations (GraphQL)**

```graphql
query {
  post(id: 1) {
    id
    title
    content
    user {
      name
    }
    category {
      name
    }
    tags {
      name
    }
    comments {
      data {
        content
        user {
          name
        }
      }
    }
  }
}
```

### **3. Ajouter un commentaire (REST)**

```bash
curl -X POST \
  http://ton-domaine.test/api/posts/1/comments \
  -H 'Authorization: Bearer {ton_token}' \
  -H 'Content-Type: application/json' \
  -d '{
    "content": "Super article !",
    "parent_id": null
  }'
```

---

## 🛡 **Sécurité**

- **Policies** : Les actions de modification/suppression sont protégées par des **Policies** (ex: seul l'auteur peut modifier/supprimer son article).
- **Sanctum** : Toutes les routes protégées nécessitent un token valide.
- **Validation** : Toutes les requêtes sont validées (ex: `StorePostRequest`, `UpdatePostRequest`).
- **Soft Deletes** : Les modèles utilisent `SoftDeletes` pour éviter la suppression définitive.

---

## 🔧 **Personnalisation**

### **1. Modifier les statuts des articles**

Le statut des articles est géré par un **Enum** (`PostStatus`). Tu peux le modifier dans `app/Enums/PostStatus.php` :

```php
enum PostStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
}
```

### **2. Ajouter des champs aux modèles**

Pour ajouter des champs (ex: `image` pour les articles), modifie les migrations et les modèles :

```php
// Dans la migration
$table->string('image')->nullable();

// Dans le modèle Post
protected $fillable = ['title', 'slug', 'content', 'status', 'user_id', 'category_id', 'image'];
```

### **3. Personnaliser les requêtes GraphQL**

Modifie le fichier `graphql/schema.graphql` pour ajouter des champs ou des mutations personnalisées.

---

## 📝 **Notes**

- **Pagination** : Toutes les listes sont paginées par défaut (10 éléments par page).
- **Relations** : Les modèles utilisent **Eloquent** pour gérer les relations (ex: `hasMany`, `belongsTo`, `belongsToMany`).
- **Soft Deletes** : Les modèles supprimés sont conservés en base de données (utilise `deleted_at` pour les filtrer).
- **Lighthouse** : Le schéma GraphQL est défini dans `graphql/schema.graphql`.

---

## 🚀 **Améliorations possibles**

- **Cache** : Utiliser **Redis** pour cache les requêtes fréquentes (ex: liste des articles).
- **Recherche avancée** : Intégrer **Scout** ou **Algolia** pour une recherche full-text.
- **Notifications** : Ajouter des notifications pour les nouveaux commentaires ou likes.
- **Rate Limiting** : Limiter le nombre de requêtes par utilisateur.
- **Webhooks** : Notifier des services externes lors de la création/modification d'articles.
- **Tests** : Ajouter des tests unitaires et d'intégration pour couvrir toutes les fonctionnalités.
