# Session 02 - Git et GitHub

Réponses rédigées avec mes propres mots, puis comparées au corrigé de la page.

## 1. Index et commit

Réponse : `git add` place un changement du répertoire de travail dans l'index. `git commit` enregistre le contenu de l'index dans le dépôt local. `git push` l'envoie ensuite sur GitHub.

## 2. Branche et tag

Réponse : une branche (`main`) avance à chaque nouveau commit. Un tag (`lab-01`) reste fixé sur un seul commit et désigne toujours l'état de fin de séance.

## 3. Le fichier .env

Réponse : `.env` contient `APP_KEY` et les réglages propres à mon poste (et des secrets en production), il ne doit jamais être publié. Un autre développeur copie `.env.example` en `.env` puis lance `php artisan key:generate`.

## 4. Le dossier vendor/

Réponse : `vendor/` est généré par Composer et contient des milliers de fichiers. `composer install` le reconstruit avec les versions exactes de `composer.lock`.

## 5. Git Credential Manager

Réponse : le jeton est rangé dans le gestionnaire d'identifiants du système (entrée `git:https://github.com`). On le supprime en fin de séance sur un poste partagé de l'université, sinon la personne suivante peut pousser en mon nom.
