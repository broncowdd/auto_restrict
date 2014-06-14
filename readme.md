#Auto_restrict


##(engish version below)(at least a poor one ;-)

##Info
Un simple script à inclure dans une page ou un script php pour en sécuriser l'accès.
- gestion de la création de login/passe lors de la première connexion
- option "rester connecté"
- prise en compte des problèmes de referer (seuls les scripts se trouvant sur le même serveur sont autorisés)
- tokens pour sécuriser les envois de données $_POST et $_GET.
- gestion transparente du bannissement d'IP lors de tentatives frauduleuses (login/passe faux, referrer erroné ou problème de token)

##Utilisation
Ajouter <?php include('auto_restrict.php'); ?> à une page et son accès est verrouillé.

On peut ainsi interdire l'accès à des pages de configuration par exemple.

S'il s'agit d'un script de gestion de données $_POST:

- on ajoute <?php include('auto_restrict.php'); ?> au début de la page de formulaire et la ligne <?php newToken();?> à l'intérieur de ce dernier.
- on ajoute <?php include('auto_restrict.php'); ?> dans le script appelé par le formulaire.
Auto_restrict se chargera de gérer les tokens pour ce formulaire.

S'il s'agit de données $_GET:

- on procède de même pour les formulaires
- on ajoute simplement <?php newToken(true); ?> à la fin des liens contenant des $_GET à sécuriser (la page cible devra inclure auto_restrict.php, bien entendu)


Pour créer un lien permettant de se déconnecter, ajouter simplement "?deconnexion=ok" à n'importe quelle url de page contenant un include d'auto_restrict.




##Information
A single script to include in a php page to secure the access.
- login/pass creation on first connection
- option "stay connected"
- handle the referer problems (allow only scripts on the same domain)
- tokens to secure the forms and the $_POST/$_GET data
- IP bannishment to avoid bruteforcing

## Use
Just add  <?php include('auto_restrict.php'); ?> in your script and the access is locked.
You can lock the access to an admin pge, a configuration script etc.

How to use with forms:
- add  <?php include('auto_restrict.php'); ?> in the form's page
- add  <?php include('auto_restrict.php'); ?> in the form's action php script
- add <?php newToken();?> inside the form.
- that's it !

if you want to secure a script's URL with $_GET commands:
- add  <?php include('auto_restrict.php'); ?> in the page containing the link
- add <?php newToken(true); ?> at the end of the link's href
- add  <?php include('auto_restrict.php'); ?> in the destination page 


To create a logout link, just add "?logout=ok" at the end (the target link must include auto_restrict, of course ^^).
