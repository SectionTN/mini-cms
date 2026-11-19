# Session 10 - Robustesse

TagAdminTest contient 5 tests verts : oui
/posts/categorie/inconnue affiche Catégorie introuvable avec le statut 404 : oui
php artisan test est vert : oui

1. Pourquoi le message de messages() l'emporte-t-il sur celui de lang/fr/validation.php ?
Laravel cherche d'abord un message pour ce champ et cette règle dans la Form Request. Il ne lit le fichier de langue que si la Form Request n'en donne pas : le message le plus précis gagne.

2. Pourquoi phpunit.xml fixe-t-il APP_LOCALE ?
Les tests comparent des textes en français. phpunit.xml passe avant .env, donc les tests donnent le même résultat sur chaque poste et sur un clone propre.

3. Pourquoi CategoryController n'a-t-il besoin d'aucun try/catch ?
La Form Request renvoie les données invalides au formulaire, la liaison de modèle répond 404, le middleware répond 503 et le gestionnaire d'exceptions transforme ces erreurs en pages. Un try/catch cacherait seulement ces réponses.
