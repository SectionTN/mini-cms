# Session 14 - Défi

Connexion enregistre le jeton toute seule : oui
Créer un article répond 201 sans copier le jeton : oui
Le fichier exporté ne contient aucun jeton : oui

Le dépôt est public. Que risquez-vous si le fichier exporté contient un jeton, et comment l'annuler ?
N'importe qui peut lire le jeton sur GitHub et agir en mon nom sur l'API : créer, modifier ou supprimer mes articles. Par défaut, un jeton Sanctum n'expire jamais. Pour l'annuler, j'appelle /api/logout avec ce jeton, ou je supprime sa ligne dans la table personal_access_tokens.

Pourquoi les écritures de l'API passent-elles par les Form Requests et PostPolicy plutôt que par des règles écrites dans Api\PostController ?
Une seule règle sert aux deux portes, le site et l'API. Si je change PostPolicy ou une Form Request, le site et l'API changent ensemble, et l'API ne peut pas devenir un raccourci qui contourne les droits du site.
