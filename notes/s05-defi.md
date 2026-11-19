# Session 05 - Défi : articles récents sur l'accueil

latestPosts contient au plus 3 articles : oui
Seuls les articles publiés sont transmis à la vue : oui
home.blade.php utilise x-post-card et @forelse : oui
Le nouveau test passe et php artisan test est vert : oui

Pourquoi get() et pas paginate(3) sur la page d'accueil ?
get() exécute la requête et renvoie directement une collection d'au plus 3 articles. paginate(3) ajouterait des liens de pagination qui n'ont pas de sens sur l'accueil.
