# Session 01 - Cycle de vie d'une requête vers /heure

1. Le navigateur envoie `GET /heure` au serveur.
2. `public/index.php` est le seul point d'entrée : il charge l'autoloader de Composer (`vendor/autoload.php`).
3. Il charge ensuite `bootstrap/app.php`, qui crée l'application et déclare `routes/web.php` dans `withRouting`.
4. Le routeur cherche dans `routes/web.php` une route GET pour `/heure` (sinon : erreur 404) et exécute sa closure.
5. La closure renvoie la vue `resources/views/heure.blade.php` avec l'heure et la date.
6. Blade compile la vue en HTML et Laravel renvoie la réponse HTTP au navigateur.
