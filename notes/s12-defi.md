# Session 12 - Défi

1. Pourquoi role n'est-il pas dans #[Fillable], et comment votre contrôleur le modifie-t-il quand même ?
Hors de #[Fillable], role ne peut pas être rempli en masse : un champ role ajouté à la main au formulaire d'inscription, ou un $request->all() un jour, ne créerait jamais d'administrateur. UserController affecte l'attribut lui-même, à partir d'une valeur validée par la règle in:author,admin et après la vérification de la UserPolicy, puis appelle save().

2. Pourquoi le callback de Gate::before doit-il renvoyer null, et non false, pour un auteur ?
false est une réponse définitive : Laravel refuserait tout à un auteur, même la modification de ses propres articles. null veut dire pas d'avis : Laravel continue et demande au Gate ou à la méthode de la Policy concernés.
