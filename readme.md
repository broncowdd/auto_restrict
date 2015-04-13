#Auto_restrict 3.3 
##(engish version below)(at least a poor one ;-)
- 3.1 http://warriordudimanche.net/article289/auto-restrict-3-0-you-shall-not-pass-at-least-without-a-token
- 3.3 http://warriordudimanche.net/article314/auto-restrict-3-3-toujours-plus-loin-toujours-plus-haut

Un simple script à inclure dans une page ou un script php pour en sécuriser l'accès.
Il faut inclure ce script avant la partie à sécuriser ou le plus tôt possible dans la page.

##Fonctions:
- gestion de la création de login/passe lors de la première connexion
- option "rester connecté" (cookie + token correspondant)
- prise en compte des problèmes de referer (seuls les scripts se trouvant sur le même serveur sont autorisés)
- tokens pour sécuriser les envois de données $_POST et $_GET.
- gestion transparente du bannissement d'IP lors de tentatives frauduleuses (login/passe faux, referrer erroné ou problème de token)
- variables de configuration du script avec valeurs par défaut
- sécurisation des formulaires sensibles via une simple fonction (ajout d'une case répéter passe admin)
- sécurisation automatique et transparente des $_POST et $_GET via un strip_tags (débrayable)


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



##Configuration du script
Certaines variables de configuration permettent d'adapter le comportement d'auto_restrict, il suffit de les redéfinir avant include:
- $auto_restrict['just_die_if_not_logged']: si à true, ne charge pas le formulaire de login si aucun utilisateur n'est loggué (pour éviter de voir apparaître le formulaire de connexion lors d'un accès via Ajax à un script php protégé par exemple)
- $auto_restrict['just_die_on_errors']: si à true, toute action dont la sécurité est compromise génère un message d'erreur; à false, la session est fermée et on est redirigé vers le formulaire de login.
- $auto_restrict['session_expiration_delay']: durée de vie de la session en minutes 
- $auto_restrict['cookie_expiration_delay']: durée de vie du cookie en jours
- $auto_restrict['IP_banned_expiration_delay']: durée de bannissement d'IP en secondes
- $auto_restrict['max_security_issues_before_ban'])): nombre maximum de problèmes de sécurité avant bannissement
- $auto_restrict['tokens_expiration_delay']: durée de vie des tokens en secondes
- $auto_restrict['use_GET_tokens_too']: utiliser également les tokens pour les variables $_GET
- $auto_restrict['use_ban_IP_on_token_errors']: utiliser le système de bannissement lors d'une erreur de token
- $auto_restrict['POST_striptags'] & $auto_restrict['GET_striptags']: strip_tags de sécurité automatique sur les données post et get 
- $auto_restrict['root'], $auto_restrict['path_from_root']: en cas d'appel depuis des scripts placés à des endroits différents de l'arborescence, il peut être utile de spécifier le root et le chemin vers auto_restrict.

### exemple de configuration plus avancée
Un fichier php appelé via ajax. 
```
// rétablissement du contexte d'appel *****************
$auto_restrict['root']='../../';
// éviter expiration du token après un usage si le user répète la commande 
// sans recharger le formulaire d'origine (pas de renouvellement du token)
$auto_restrict['kill_tokens_after_use']=false; 
$auto_restrict['path_from_root']='./LIBS';
include($auto_restrict['root'].'LIBS/auto_restrict.php');
// ********************************************
(...)
```

De même, on pourra modifier la durée d'expiration des tokens ou de la session en fonction du type de page: 
- page de config d'une appli: délais courts (peu de temps de travail)
- page de création de contenu: délais plus longs.


##Information
A single script to include in a php page to secure the access.
- login/pass creation on first connection
- option "stay connected"
- handle the referer problems (allow only scripts on the same domain)
- tokens to secure the forms and the $_POST/$_GET data
- IP bannishment to avoid bruteforcing
- easy secure sensitive forms with admin password 
- secure post & get data

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


##Configuration
Ther's a few configuration vars. Change them before the script's include:
- $auto_restrict['just_die_if_not_logged']: if true, don't redirect to login form if there's no user logged (avoid redirect in case on ajax access to the page for example)
- $auto_restrict['just_die_on_errors']: if true, the script just ends on all the security problems; if false, redirect to login form on security problem.
- $auto_restrict['session_expiration_delay']: session duration in minutes
- $auto_restrict['cookie_expiration_delay']: cookie duration in days
- $auto_restrict['IP_banned_expiration_delay']: bannishment duration in seconds
- $auto_restrict['max_security_issues_before_ban']))
- $auto_restrict['tokens_expiration_delay']: tokens duration in minutes
- $auto_restrict['use_GET_tokens_too']: also use tokens with $_GET vars
- $auto_restrict['use_ban_IP_on_token_errors']: ban IP if there's a token security problem
