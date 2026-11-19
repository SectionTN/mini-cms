# Session 08 - Défi

La route posts.tag utilise {tag:slug} : oui
La page d'un tag n'affiche ni brouillon ni article d'un autre tag : oui
Les tags des cartes sont des liens : oui
L'accueil n'affiche que les tags qui ont au moins un article publié : oui
Les trois nouveaux tests passent et php artisan test est vert : oui

Pourquoi filtrer les tags vides après get() plutôt que d'afficher (0) ?
Un tag sans article publié mènerait à une page vide, donc il ne sert à rien de l'afficher. Le filtrer sur la collection après get() évite d'ajouter une condition SQL.

Combien de requêtes ajoute la liste des tags populaires, et pourquoi pas une par tag ?
Une seule : withCount ajoute une sous-requête de comptage à la requête des tags, la base calcule tous les nombres en une fois et aucun article n'est chargé.
