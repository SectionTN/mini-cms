# Session 13 - Défi

/api/posts/9 affiche tags avec des noms : oui
/api/posts ne contient aucun body, /api/posts/9 en contient un : oui
Aucune LazyLoadingViolationException : oui

Pourquoi ne pas envoyer body dans la liste, et qui en profite ?
La liste reste légère : moins d'octets à envoyer et une réponse plus rapide, surtout sur un téléphone. Le client qui affiche la liste n'a besoin que du titre et de l'extrait, et il ne charge le contenu complet que si la personne ouvre un article.

Un brouillon renvoie 403. Quel avantage aurait un 404 pour un brouillon ?
Un 404 ne dit pas qu'un brouillon existe avec ce numéro, alors qu'un 403 le confirme. On garde 403 pour suivre la règle de PostPolicy, la même pour le site et pour l'API.
