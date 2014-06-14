Auto_restrict

Un simple script à inclure dans une page ou un script php pour en sécuriser l'accès.
- gestion de la création de login/passe lors de la première connexion
- option "rester connecté"
- prise en compte des problèmes de referer (seuls les scripts se trouvant sur le même serveur sont autorisés)
- tokens pour sécuriser les envois de données $_POST et $_GET.
- gestion transparente du bannissement d'IP lors de tentatives frauduleuses (login/passe faux, referrer erroné ou problème de token)

Ajouter <?php include('auto_restrict.php'); ?> à une page et son accès est verrouillé.
On peut ainsi interdire l'accès à des pages de configuration par exemple.
S'il s'agit d'un script de gestion de données $_POST:
    - on ajoute <?php include('auto_restrict.php'); ?> au début de la page de formulaire et la ligne <?php newToken();?> à l'intérieur de ce dernier.
    - on ajoute <?php include('auto_restrict.php'); ?> dans le script appelé par le formulaire.
	Auto_restrict se chargera de gérer les tokens pour ce formulaire.

S'il s'agit de données $_GET:
    - on procède de même pour les formulaires
    - on ajoute simplement <?php newToken(true); ?> à la fin des liens contenant des $_GET à sécuriser (la page cible devra inclure auto_restrict.php, bien entendu)


Pour créer un lien permettant de se déconnecter, ajouter simplement "?deconnexion=ok" à n'importe quelle url contenant un include d'auto_restrict.