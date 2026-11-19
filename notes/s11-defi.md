# Session 11 - Défi

/mes-articles ne montre que mes articles, brouillons compris : oui
Un visiteur qui ouvre /mes-articles arrive sur /connexion : oui
Le cookie remember_web_ apparaît quand la case est cochée : oui
Les deux nouveaux tests passent et php artisan test est vert : oui

Pourquoi partir de $request->user()->posts() plutôt que de Post::where('user_id', $request->user()->id) ?
La relation porte déjà la condition sur l'utilisateur connecté. Aucun champ de formulaire ni paramètre d'adresse ne peut la diriger vers un autre utilisateur, et le code dit ce qu'il fait : les articles de cet utilisateur.

Pourquoi la case remember ne doit-elle pas être dans le tableau passé à Auth::attempt() ?
attempt() cherche l'utilisateur avec chaque clé du tableau sauf le mot de passe. Avec remember dedans, il chercherait une colonne remember dans users et la page afficherait une erreur SQL (no such column: remember).
