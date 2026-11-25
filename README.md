# Mini-CMS

Projet fil rouge de l'Atelier Framework Côté Serveur (Laravel 13, PHP 8.3+), 3e année MDW, ISET Sidi Bouzid.

Auteur : Mohamed Rayen Sbai, groupe MDW3.

## Description

Mini-CMS est une petite plateforme de publication d'articles. Les visiteurs lisent les articles publiés, classés par catégorie, par tag et par année. Les auteurs connectés écrivent leurs articles, gardent des brouillons et les publient. Les administrateurs gèrent les catégories, les tags et les rôles des comptes. Une API JSON donne accès aux mêmes données à d'autres programmes, avec des jetons Sanctum pour écrire.

Chaque session du cours se termine par un tag Git (`lab-01` à `lab-08b`).

## Fonctionnalités

- Pages publiques : accueil, liste paginée des articles publiés, archive par année, articles d'une catégorie ou d'un tag, page d'un article avec ses tags et les articles de la même catégorie, page À propos.
- Comptes : inscription, connexion avec « Se souvenir de moi », déconnexion, page « Mes articles » avec ses brouillons.
- Rédaction : création, modification, suppression, publication et dépublication d'un article, avec validation par des Form Requests et messages en français.
- Autorisations : `PostPolicy` (un auteur ne modifie que ses articles, un brouillon n'est visible que par son auteur et les administrateurs), Gates `manage-categories` et `manage-tags`, middleware `admin` sur tout `/admin`.
- Back-office des administrateurs : catégories, tags et rôle de chaque compte.
- Pages d'erreur 404 et 503 dans le layout du site.
- API JSON : articles, catégories et tags en lecture, filtres `?category=` et `?tag=`, connexion par jeton Sanctum, écritures protégées (détails dans la section API).
- Tests Pest sur le site et sur l'API (`php artisan test`).

Routes du site :

| Méthode | URI | Nom | Action |
|---|---|---|---|
| GET | `/` | `home` | `PageController@home` |
| GET | `/a-propos` | `about` | `PageController@about` |
| GET | `/heure` | `time` | `PageController@time` |
| GET | `/posts` | `posts.index` | `PostController@index` |
| GET | `/posts/archive/{year}` | `posts.archive` | `PostController@archive` |
| GET | `/posts/categorie/{category:slug}` | `posts.category` | `PostController@byCategory` |
| GET | `/posts/tag/{tag:slug}` | `posts.tag` | `PostController@byTag` |
| GET | `/posts/create` | `posts.create` | `PostController@create` |
| POST | `/posts` | `posts.store` | `PostController@store` |
| GET | `/posts/{post}` | `posts.show` | `PostController@show` |
| GET | `/posts/{post}/edit` | `posts.edit` | `PostController@edit` |
| PUT/PATCH | `/posts/{post}` | `posts.update` | `PostController@update` |
| DELETE | `/posts/{post}` | `posts.destroy` | `PostController@destroy` |
| PATCH | `/posts/{post}/publication` | `posts.publication` | `PostController@togglePublication` |
| GET | `/mes-articles` | `my-posts` | `PostController@mine` |
| GET | `/inscription` | `register` | `Auth\RegisterController@create` |
| POST | `/inscription` | `register.store` | `Auth\RegisterController@store` |
| GET | `/connexion` | `login` | `Auth\LoginController@create` |
| POST | `/connexion` | `login.store` | `Auth\LoginController@store` |
| POST | `/deconnexion` | `logout` | `Auth\LoginController@destroy` |
| GET | `/admin/categories` | `admin.categories.index` | `CategoryController@index` |
| GET | `/admin/categories/create` | `admin.categories.create` | `CategoryController@create` |
| POST | `/admin/categories` | `admin.categories.store` | `CategoryController@store` |
| GET | `/admin/categories/{category}/edit` | `admin.categories.edit` | `CategoryController@edit` |
| PUT/PATCH | `/admin/categories/{category}` | `admin.categories.update` | `CategoryController@update` |
| DELETE | `/admin/categories/{category}` | `admin.categories.destroy` | `CategoryController@destroy` |
| GET | `/admin/tags` | `admin.tags.index` | `TagController@index` |
| GET | `/admin/tags/create` | `admin.tags.create` | `TagController@create` |
| POST | `/admin/tags` | `admin.tags.store` | `TagController@store` |
| GET | `/admin/tags/{tag}/edit` | `admin.tags.edit` | `TagController@edit` |
| PUT/PATCH | `/admin/tags/{tag}` | `admin.tags.update` | `TagController@update` |
| DELETE | `/admin/tags/{tag}` | `admin.tags.destroy` | `TagController@destroy` |
| GET | `/admin/utilisateurs` | `admin.users.index` | `UserController@index` |
| PATCH | `/admin/utilisateurs/{user}/role` | `admin.users.role` | `UserController@updateRole` |

## Installation

Prérequis : PHP 8.3 ou plus avec les extensions curl, fileinfo, mbstring, openssl, pdo_sqlite, sqlite3 et zip, Composer 2, Node.js LTS avec npm, et Git. La base est un fichier SQLite.

### Bash

```bash
git clone https://github.com/SectionTN/mini-cms.git
cd mini-cms
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan test
composer run dev
```

### PowerShell

```powershell
git clone https://github.com/SectionTN/mini-cms.git
cd mini-cms
composer install
npm install
npm run build
copy .env.example .env
php artisan key:generate
New-Item database\database.sqlite -ItemType File
php artisan migrate:fresh --seed
php artisan test
composer run dev
```

`npm run build` est nécessaire avant les tests : le layout charge ses fichiers avec `@vite`. Ouvrir ensuite http://localhost:8000 (et non l'adresse de Vite sur le port 5173). Attention : `migrate:fresh --seed` vide la base avant de la remplir.

## Comptes de test

Le seeder crée ces comptes, tous avec le mot de passe `password` :

| Compte | Rôle |
|---|---|
| `test@example.com` | administrateur |
| deux comptes aux adresses aléatoires | auteur |

Pour afficher l'adresse d'un compte auteur :

```bash
php artisan tinker --execute="echo App\Models\User::where('role', 'author')->value('email');"
```

## API

Toutes les adresses commencent par `/api`. Chaque requête envoie l'en-tête `Accept: application/json`, et chaque réponse, erreurs comprises, est en JSON. Une collection Postman (format v2.1) est fournie dans `docs/postman/mini-cms.postman_collection.json`.

### Endpoints

| Méthode | URI | Nom | Jeton | Réponse |
|---|---|---|---|---|
| GET | `/api/posts` | `api.posts.index` | non | articles publiés, paginés (`?per_page=` de 1 à 50, 5 par défaut), filtres `?category=<slug>` et `?tag=<slug>` |
| GET | `/api/posts/{post}` | `api.posts.show` | non | un article publié, avec son contenu |
| GET | `/api/categories` | `api.categories.index` | non | les catégories, avec `posts_count` (articles publiés) |
| GET | `/api/categories/{category}` | `api.categories.show` | non | une catégorie, avec `posts_count` |
| GET | `/api/tags` | `api.tags.index` | non | les tags, avec `posts_count` (articles publiés) |
| GET | `/api/tags/{tag}` | `api.tags.show` | non | un tag, avec `posts_count` |
| POST | `/api/login` | `api.login` | non | 201 avec un jeton |
| POST | `/api/logout` | `api.logout` | oui | 204, le jeton est révoqué |
| GET | `/api/user` | | oui | le compte du jeton |
| POST | `/api/posts` | `api.posts.store` | oui | 201 avec l'article créé |
| PUT/PATCH | `/api/posts/{post}` | `api.posts.update` | oui | l'article modifié |
| DELETE | `/api/posts/{post}` | `api.posts.destroy` | oui | 204 |

### Authentification par jeton

1. `POST /api/login` avec un corps JSON `{"email": "test@example.com", "password": "password"}`.
2. La réponse 201 contient `token` (par exemple `1|...`) et `token_type` qui vaut `Bearer`. Le jeton n'est affiché qu'une fois : la base n'en garde qu'une empreinte.
3. Chaque requête protégée envoie l'en-tête `Authorization: Bearer <jeton>`.
4. `POST /api/logout` avec ce jeton le supprime. Les autres jetons du même compte restent valides.

Les écritures suivent les mêmes règles que le site : un auteur ne modifie et ne supprime que ses propres articles.

### Codes d'erreur

| Code | Quand | Corps |
|---|---|---|
| 401 | jeton absent, faux ou révoqué sur une route protégée | `{"message": "Unauthenticated."}` |
| 403 | la Policy refuse : brouillon, article d'un autre auteur | `{"message": "This action is unauthorized."}` |
| 404 | article, catégorie ou tag introuvable | `{"message": "No query results for model ..."}` |
| 422 | données invalides : `per_page`, slug inconnu, champ manquant, mauvais mot de passe | `{"message": "...", "errors": {...}}`, en français |

Avec `APP_DEBUG=true` (en local), les corps 403 et 404 ajoutent `exception`, `file`, `line` et `trace`.

### Exemple curl.exe

Se déconnecter (remplacer `VOTRE_JETON` par un jeton obtenu avec `POST /api/login`). La commande n'affiche rien : la réponse 204 n'a pas de corps.

```powershell
curl.exe -X POST -H "Accept: application/json" -H "Authorization: Bearer VOTRE_JETON" http://localhost:8000/api/logout
```

Sur macOS et Linux, la même commande s'écrit avec `curl` au lieu de `curl.exe`.

## Captures d'écran

### Page de bienvenue

![Page « Bienvenue sur Mini-CMS », une vue Blade renvoyée par une route closure](screenshots/s01-bienvenue.png)

### Page d'accueil

![Page d'accueil de Mini-CMS](screenshots/s01-accueil.png)

### Liste des routes

![Sortie de php artisan route:list](screenshots/s01-route-list.png)

### Page À propos

![Page A propos](screenshots/s02-a-propos.png)

### Accueil avec le layout

![Page d'accueil avec le layout](screenshots/s03-accueil.png)

### Liste des articles

![Liste des articles](screenshots/s03-articles.png)

### Détail d'un article

![Détail d'un article](screenshots/s03-article.png)

### Session 04

![Liste des articles](screenshots/s04-articles.png)

### Session 05

![Liste paginée des articles](screenshots/s05-articles.png)

![Formulaire avec erreurs de validation](screenshots/s05-formulaire.png)

### Session 06

![Formulaire de modification prérempli](screenshots/s06-modification.png)

![Confirmation de suppression](screenshots/s06-suppression.png)

### Session 07

![Liste des articles avec leur catégorie, leur auteur et leurs tags](screenshots/s07-liste-relations.png)

![Page d'un article avec ses tags et les articles de la même catégorie](screenshots/s07-detail-tags.png)

### Session 08

![Formulaire de modification avec la catégorie et les tags](screenshots/s08-formulaire-relations.png)

![Page des articles d'une catégorie](screenshots/s08-page-categorie.png)

### Session 09

![Page d'un article avec le message flash « Article créé. »](screenshots/s09-message-flash.png)

![Page Article introuvable pour un identifiant inexistant](screenshots/s09-article-introuvable.png)

### Session 10

Back-office des catégories et des tags, messages de validation en français, pages 404 et 503 dans le layout du site.

![Formulaire de catégorie envoyé vide, avec les messages de validation en français](screenshots/s10-validation.png)

![Page 404 du site pour une adresse inconnue](screenshots/s10-404.png)

### Session 11

![Barre de navigation d'un utilisateur connecté, avec le message « Vous êtes connecté. »](screenshots/s11-connexion.png)

![Page 403 quand un auteur ouvre la modification de l'article d'un autre auteur](screenshots/s11-interdit.png)

### Session 12

![Page 403 « Réservé aux administrateurs. » pour un compte auteur sur /admin/tags](screenshots/s12-admin-interdit.png)

![Barre de navigation d'un compte auteur, sans les liens Catégories et Tags](screenshots/s12-menu-auteur.png)

### Session 13

![Bruno : réponse JSON de GET /api/posts?per_page=2, avec data, links et meta](screenshots/s13-postman-liste.png)
![Bruno : réponse 422 de GET /api/posts?per_page=500, avec le message en français](screenshots/s13-postman-422.png)

### Session 14

![Bruno : POST /api/posts avec un jeton Bearer, onglet Body, réponse 201 Created](screenshots/s14-postman-201.png)
![Bruno : PUT sur l'article d'un autre auteur, réponse 403](screenshots/s14-postman-403.png)

## Choix d'architecture

### Pourquoi les écritures de l'API passent par PostPolicy

Je voulais une seule règle pour décider qui peut modifier ou supprimer un article. Le site et l'API utilisent les mêmes Form Requests et la même `PostPolicy`, donc si je change la règle, les deux portes changent ensemble. L'API ne peut pas devenir un raccourci qui contourne les droits du site. Et comme `authorize()` passe avant `rules()`, un auteur qui touche à l'article d'un autre reçoit 403 avant même de voir un message de validation.

### Pourquoi preventLazyLoading est activé

Sans chargement anticipé, une boucle qui lit `$post->user` lance une requête SQL par article. C'est le problème N+1 : la page marche, elle est juste plus lente, donc on peut ne jamais le remarquer. Avec `preventLazyLoading`, Laravel lève une erreur en local dès que ça arrive, et je suis obligé de charger les relations avec `with()` dans le contrôleur. En production, il est désactivé (`! app()->isProduction()`) pour qu'un oubli ne fasse pas tomber le site.

### Pourquoi l'API utilise des jetons et non la session

Le groupe `api` n'a ni session ni cookie, il ne se souvient de rien d'une requête à l'autre. Une application mobile, un script ou un client comme Bruno ou Postman n'est pas un navigateur, alors il envoie son jeton dans l'en-tête `Authorization: Bearer` à chaque requête. Chaque appareil a son propre jeton, et `/api/logout` ne supprime que celui de la requête, sans déconnecter les autres. La base ne garde qu'une empreinte du jeton : même volée, elle ne donne aucun jeton utilisable.

## Comparaison avec le starter kit Livewire

| Critère | Mini-CMS (authentification manuelle) | Starter kit Livewire |
|---|---|---|
| Routes d'authentification | `routes/web.php` (`/inscription`, `/connexion`, `/deconnexion`) | enregistrées par Fortify depuis son paquet, absentes de `routes/web.php` |
| Inscription | `app/Http/Controllers/Auth/RegisterController.php` et `RegisterRequest` | `app/Actions/Fortify/CreateNewUser.php` (`Validator::make` puis `User::create`) et `resources/views/pages/auth/register.blade.php` |
| Connexion | `app/Http/Controllers/Auth/LoginController.php` (`store` : `Auth::attempt`, `regenerate`) | contrôleur de Fortify et `resources/views/pages/auth/login.blade.php` |
| Déconnexion | `LoginController::destroy` (`logout`, `invalidate`, `regenerateToken`) | route `logout` de Fortify, mêmes opérations dans `app/Livewire/Actions/Logout.php` |
| Limitation des tentatives | aucune | 5 tentatives par minute par e-mail et adresse IP (`app/Providers/FortifyServiceProvider.php`) |
| Mot de passe oublié | absent | `Features::resetPasswords()` dans `config/fortify.php` |
| Vérification de l'e-mail | absente | `Features::emailVerification()`, `MustVerifyEmail` sur `User`, middleware `verified` sur `/dashboard` |
| Double authentification | absente | `Features::twoFactorAuthentication()` et `Features::passkeys()` |
| Rôles et autorisations | colonne `role`, Gates `manage-categories` et `manage-tags`, `PostPolicy`, middleware `admin` (`EnsureUserIsAdmin`) | aucun, à écrire soi-même |
| Tests | `AuthTest`, `AuthorizationTest`, `AdminAccessTest`, `PostVisibilityTest` | 10 fichiers dans `tests/Feature` (Auth, Settings, Dashboard), écrits avec PHPUnit |

Ce que je garderais du kit dans un vrai projet : la limitation des tentatives de connexion, le mot de passe oublié et la vérification de l'e-mail, car les écrire soi-même prend du temps et une erreur y coûte cher. Je garderais en revanche mes propres rôles, Gates et Policies, que le kit ne fournit pas.
