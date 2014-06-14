<style>
body{text-align:center;}
	.content{border-radius:2px;box-shadow:0 1px 1px #666;text-align:left;vertical-align:top;display:inline-block; border:2px solid #999;font-family: 'georgia'; font-size:18px;background-color:#CCC;width:200px;padding:20px;text-shadow:0 1px 1px white;color:black;}
	h1{font-size:22px; }
	label{display:block;}
	input{width:100%;}
	fieldset{margin-top:20px;text-align:center;}
	a, .green{text-shadow:0 0 3px green;color:darkgreen;text-decoration:none;font-style:italic}
	a:hover{text-shadow:0 0 3px yellow;color:orange;}
	.fatal_error{background-color:red;display:inline-block;border-radius:3px;box-shadow: 0 0 1px red;color:white;padding:10px;text-shadow:0 1px 1px maroon;}
	.red{text-shadow:0 0 3px red;color:darkred;}
</style>

<?php include('auto_restrict.php'); ?>

<?php 
	// using post data AFTER auto_restrict's include (important)
	if (!empty($_POST['case'])){echo '<script>alert("j\'ai bien reçu le contenu $_POST suivant:'.strip_tags($_POST['case']).'");</script>';}
	if (!empty($_GET['case'])){echo '<script>alert("j\'ai bien reçu le contenu $_GET suivant:'.strip_tags($_GET['case']).'");</script>';}

?>
<body>
<h1> On ne peut accéder à cette page que loggé...</h1>
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
			<?php newToken(); ?>
		</form>
		<small>On n'ajoute que <em>&lt;?php newToken(); ?></em></small>
	</fieldset>

	
</div>
</body>
