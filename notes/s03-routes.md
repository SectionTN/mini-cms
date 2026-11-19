# Session 03 - Routes, contrôleurs et layout

## Défi

URL produite par route('about') :
`http://localhost:8000/a-propos`

URL produite par route('posts.show', 'routes-et-controleurs') :
`http://localhost:8000/posts/routes-et-controleurs`

URL produite par route('posts.archive', 2025) :
`http://localhost:8000/posts/archive/2025`

Pourquoi /posts/archive/abc et /posts/article-inconnu répondent toutes deux 404, et quelle ligne de code en est responsable dans chaque cas ?
`/posts/archive/abc` : aucune route ne correspond, car `whereNumber('year')` dans `routes/web.php` n'accepte que des chiffres. `/posts/article-inconnu` : la route `posts.show` correspond, mais le slug n'existe pas dans le tableau, donc `abort(404)` dans `PostController::show` répond 404.

Que se passe-t-il si une vue appelle route('posts.show') sans paramètre ?
Laravel lève une erreur `Missing required parameter for [Route: posts.show]`, car l'URI contient `{slug}`.
