<style>
body{text-align:center;}
	.content{min-height:500px;border-radius:2px;box-shadow:0 1px 1px #666;text-align:left;vertical-align:top;display:inline-block; border:2px solid #999;font-family: 'georgia'; font-size:18px;background-color:#CCC;width:200px;padding:20px;text-shadow:0 1px 1px white;color:black;}
	h1{font-size:22px; }
	label{display:block;}
	input{width:100%;}
	fieldset{margin-top:20px;text-align:center;}
	a, .green{text-shadow:0 0 3px green;color:darkgreen;text-decoration:none;font-style:italic}
	a:hover{text-shadow:0 0 3px yellow;color:orange;}
	.fatal_error{background-color:red;display:inline-block;border-radius:3px;box-shadow: 0 0 1px red;color:white;padding:10px;text-shadow:0 1px 1px maroon;}
	.red{text-shadow:0 0 3px red;color:darkred;}
	.admin_password{background:red;color:pink;border:1px solid maroon;border-radius: 3px;box-shadow: inset 0 0 1px; padding:3px;}
	.admin_password_label{color:red;font-size:14px;}
	header{font-size: 25px; margin-bottom:30px;}
	footer{margin-top:30px;font-size:14px;}
</style>

<?php include('auto_restrict.php'); ?>

<?php 
	// using post data AFTER auto_restrict's include (important)
	if (!empty($_POST['case'])){echo '<script>alert("j\'ai bien reçu le contenu $_POST suivant:'.strip_tags($_POST['case']).'");</script>';}
	if (!empty($_GET['case'])){echo '<script>alert("j\'ai bien reçu le contenu $_GET suivant:'.strip_tags($_GET['case']).'");</script>';}

?>
<body>
<header> 
Cette page contient un include d'auto_restrict: <br/> on ne peut donc y accéder que loggé...</header>
<div class='content'>
	<h1>Auto_restrict.php</h1><hr/>
	Vous etes <em>connecté!</em><br/>
	<?php if (!isset($_COOKIE[$auto_restrict['cookie_name']])){?>
	Durée d'inactivité avant déconnexion:<em> <?php echo $auto_restrict['session_expiration_delay']; ?> min.</em>
	<?php }else{ ?>
	Durée normale d'inactivité avant déconnexion:<em> <?php echo $auto_restrict['session_expiration_delay']; ?> min.</em>
	mais la case rester connecté étant cochée, la "session" restera valide durant <em> <?php echo $auto_restrict['cookie_expiration_delay']; ?> jour(s)</em>
	<?php } ?>
	<fieldset>
	<a href='index.php' >Recharger cette page</a>
	<a href='?deconnexion=ok' class="red">DECONNEXION</a>
	</fieldset>
</div>


<div class='content'>
	<h1>Gestion des tokens pour les formulaires (post)</h1><hr/>
	
	<fieldset><legend class="red">Un formulaire non sécurisé par token</legend>
		<form action="?" method="post">
			<input type="text" name="case" placeholder="tapez un truc"/><input type="submit"/>
		</form>
	</fieldset>	
	<fieldset><legend class="green">Un formulaire avec token</legend>
		<form action="?" method="post">
			<input type="text" name="case" placeholder="tapez un truc"/><input type="submit"/>
			<?php newToken(); ?>
		</form>
		<small>On n'ajoute que <em>&lt;?php newToken(); ?></em></small>
	</fieldset>

	
</div>

<div class='content'>
	<h1>Gestion des tokens pour les formulaires (get)</h1><hr/>
	
	<fieldset><legend class="red">Un formulaire non sécurisé par token</legend>
		<form action="?" method="get">
			<input type="text" name="case" placeholder="tapez un truc"/><input type="submit"/>
		</form>
	</fieldset>	
	<fieldset><legend class="green">Un formulaire avec token</legend>
		<form action="?" method="get">
			<input type="text" name="case" placeholder="tapez un truc"/><input type="submit"/>
			<?php sameToken(); ?>
		</form>
		<small>On n'ajoute que <em>&lt;?php sameToken(); ?> pour réutiliser le token généré avec newToken()</em></small>
	</fieldset>

	
</div>

<div class='content'>
	<h1>Formulaire sensible: ajout du mot de passe administrateur</h1><hr/>
	
	<fieldset><legend class="green">Un formulaire avec token + mot de passe admin</legend>
		<form action="?" method="post">
			<input type="text" name="case" placeholder="donnée sensible"/>
			<?php sameToken(); adminPassword('Mot de passe admin:');?>
			<input type="submit"/>
		</form>
		<small>On n'ajoute que <em>&lt;?php adminPassword(); ?> pour ajouter une case mot de passe admin</em></small>
	</fieldset>

	
</div>
<footer><a href="https://github.com/broncowdd/auto_restrict">auto_restrict v3.0</a> par <a href="http://warriordudimanche.net">Bronco</a></footer>
</body>
