#Auto_restrict 3.1 
##(engish version below)(at least a poor one ;-)
http://warriordudimanche.net/article289/auto-restrict-3-0-you-shall-not-pass-at-least-without-a-token

Un simple script à inclure dans une page ou un script php pour en sécuriser l'accès.
Il faut inclure ce script avant la partie à sécuriser ou le plus tôt possible dans la page.

##Fonctions:
- gestion de la création de login/passe lors de la première connexion
- option "rester connecté"
- prise en compte des problèmes de referer (seuls les scripts se trouvant sur le même serveur sont autorisés)
- tokens pour sécuriser les envois de données $_POST et $_GET.
- gestion transparente du bannissement d'IP lors de tentatives frauduleuses (login/passe faux, referrer erroné ou problème de token)
- variables de configuration du script avec valeurs par défaut
- sécurisation des formulaires sensibles via une simple fonction


##Usage: 
Ajouter <?php include('auto_restrict.php'); ?> à une page et son accès est verrouillé.

On peut ainsi interdire l'accès à des pages de configuration par exemple.
S'il s'agit d'un script de gestion de données $_POST:
- on ajoute <?php include('auto_restrict.php'); ?> au début de la page de formulaire et la ligne <?php newToken();?> à l'intérieur de ce dernier.
- on ajoute <?php include('auto_restrict.php'); ?> dans le script appelé par le formulaire.
Auto_restrict se chargera de gérer les tokens pour ce formulaire.
- pour un formulaire sensible, on peut ajouter <?php adminPassword(); ?> dans le formulaire et auto_restrict vérifiera le passe avant de passer la main et bloquera en cas de passe erroné.

S'il s'agit de données $_GET:
- on procède de même pour les formulaires
- on ajoute simplement <?php newToken(true); ?> à la fin des liens contenant des $_GET à sécuriser (la page cible devra inclure auto_restrict.php, bien entendu)

Pour créer un lien permettant de se déconnecter, ajouter simplement "?logout=ok" ou"?deconnexion=ok" à n'importe quelle url contenant un include d'auto_restrict.




##Information
A single script to include in a php page to secure the access.
- login/pass creation on first connection
- option "stay connected"
- handle the referer problems (allow only scripts on the same domain)
- tokens to secure the forms and the $_POST/$_GET data
- IP bannishment to avoid bruteforcing
- easy secure sensitive forms with admin password 

## Use
Just add  <?php include('auto_restrict.php'); ?> in your script and the access is locked.
You can lock the access to an admin pge, a configuration script etc.

How to use with forms:
- add  <?php include('auto_restrict.php'); ?> in the form's page
- add  <?php include('auto_restrict.php'); ?> in the form's action php script
- add <?php newToken();?> inside the form.
- when you want to secure a sensitive form, use <?php adminPassword();?> inside the form. You can specify a label text and/or a placeholder text adminPassword('label text',placeholder text');
- that's it !

if you want to secure a script's URL with $_GET commands:
- add  <?php include('auto_restrict.php'); ?> in the page containing the link
- add <?php newToken(true); ?> at the end of the link's href
- add  <?php include('auto_restrict.php'); ?> in the destination page 


To create a logout link, just add "?logout=ok" at the end (the target link must include auto_restrict, of course ^^).
