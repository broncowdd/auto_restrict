<?php 

	/**
	 * auto_restrict
	 * @author bronco@warriordudimanche.com / www.warriordudimanche.net
	 * @copyright open source and free to adapt (keep me aware !)
	 * @version 3.1 - one user only version / version mono utilisateur
	 *   
	 * This script locks a page's access  
	 * Just include it in the page you want to lock  
	 * It does all for you:  
	 *   - login/pass creation
	 *   - auto redirect to login form
	 *   - session's expiration
	 *   - login & logout (to logout, add ?logout $_GET var)
	 *   - referrer errors (same domain)
	 *   - auto ban IP and (auto unban)
	 *   - tokens to secure post and get forms (just add <?php newToken(); ?> to the form or <?php sameToken();?> to repeat a previously generated token, in case of various forms in a same page)
	 *   - easyly secure sensitive actions adding admin password in your form (just add <?php adminPassword(); ?>, auto_restrict will exit if password is not correct)
	 * ToDo:
	 *   - secure post and get data
	 *   - add a log connection file
	 *   
	 *   
	 * Verrouille l'accès à une page
	 * Il suffit d'inclure ce fichier pour bloquer l'accès...
	 * gestion de l'expiration de session, 
	 * gestion de la connexion et de la déconnexion.
	 * gestion des différences entre le domaine referer et le domaine sur lequel le script est hébergé (si différent -> pas ok)
	 * gestion du bannissement des adresses ip en cas de bruteforcing ou de referer anormal
	 * gestion des tokens de sécurisation à ajouter aux forms en une commande <?php newToken();?>; le script se charge seul de vérifier le token
	 * génération aléatoire de la clé de cryptage
	 * sécurisation par mot de passe sur les actions sensibles (il suffit d'ajouter <?php adminPassword(); ?> à un formulaire pour qu'auto_restrict bloque en cas de mauvais mot de passe)
	 *
	 * Améliorations eventuelles:
	 * ajouter la sécurisation du $_POST/$_GET ?
	 * ajouter un fichier log de connexion
	 * 
	*/	
	session_start();
	// ------------------------------------------------------------------
	// default config
	// ------------------------------------------------------------------
	// you can modify this config before the include('auto_restrict.php');
	if (!isset($auto_restrict['error_msg'])){					$auto_restrict['error_msg']='Erreur - impossible de se connecter.';}// utilisé si on ne veut pas rediriger
	if (!isset($auto_restrict['cookie_name'])){					$auto_restrict['cookie_name']='auto_restrict';}// nom du cookie
	if (!isset($auto_restrict['session_expiration_delay'])){	$auto_restrict['session_expiration_delay']=1;}//minutes
	if (!isset($auto_restrict['cookie_expiration_delay'])){		$auto_restrict['cookie_expiration_delay']=30;}//days
	if (!isset($auto_restrict['IP_banned_expiration_delay'])){	$auto_restrict['IP_banned_expiration_delay']=90;}//seconds
	if (!isset($auto_restrict['max_login_fails'])){				$auto_restrict['max_login_fails']=5;}
	if (!isset($auto_restrict['just_die_on_errors'])){			$auto_restrict['just_die_on_errors']=true;}// end script immediately instead of include loginform in case of banished ip or referer problem;
	if (!isset($auto_restrict['tokens_expiration_delay'])){		$auto_restrict['tokens_expiration_delay']=300;}//seconds
	if (!isset($auto_restrict['use_GET_tokens_too'])){			$auto_restrict['use_GET_tokens_too']=true;}
	if (!isset($auto_restrict['use_ban_IP_on_token_errors'])){	$auto_restrict['use_ban_IP_on_token_errors']=true;}
	if (!isset($auto_restrict['redirect_error'])){				$auto_restrict['redirect_error']='index.php';}// si précisé, pas de message d'erreur
	if (!isset($auto_restrict['domain'])){						$auto_restrict['domain']=$_SERVER['SERVER_NAME'];}
	if (!empty($_SERVER['HTTP_REFERER'])){						$auto_restrict['referer']=returndomain($_SERVER['HTTP_REFERER']);}else{$auto_restrict['referer']='';}
	
	
	
	// ------------------------------------------------------------------
	// we create login pass and secure it, thanks to JérômeJ (http://www.olissea.com/)
	// ------------------------------------------------------------------
	// handles user login creation process 
	// creates pass.php with secured login pass data
	if(file_exists('pass.php')) include('pass.php');
	if(!isset($auto_restrict['pass'])){
		if(isset($_POST['pass'])&&isset($_POST['login'])&&$_POST['pass']!=''&&$_POST['login']!=''){ 
			$salt = md5(uniqid('', true));
			$auto_restrict['encryption_key']=md5(uniqid('', true));
	
			file_put_contents('pass.php', '<?php $auto_restrict["login"]="'.$_POST['login'].'";$auto_restrict["encryption_key"]='.var_export($auto_restrict['encryption_key'],true).';$auto_restrict["salt"] = '.var_export($salt,true).'; $auto_restrict["pass"] = '.var_export(hash('sha512', $salt.$_POST['pass']),true).'; $auto_restrict["tokens_filename"] = "tokens_'.var_export(hash('sha512', $salt.uniqid('', true)),true).'.php";$auto_restrict["banned_ip_filename"] = "banned_ip_'.var_export(hash('sha512', $salt.uniqid('', true)),true).'.php";?>');
			include('login_form.php');exit();
		}
		else{ 
			include('login_form.php');exit();
		}
	}
	// ------------------------------------------------------------------
	// load banned ip
	// ------------------------------------------------------------------
	if (is_file($auto_restrict["banned_ip_filename"])){include($auto_restrict["banned_ip_filename"]);}
	// ------------------------------------------------------------------



	// ------------------------------------------------------------------
	// user tries to login
	// ------------------------------------------------------------------	
	if (isset($_POST['login'])&&isset($_POST['pass'])){
		log_user($_POST['login'],$_POST['pass']);
		if (isset($_POST['cookie'])){setcookie($auto_restrict['cookie_name'],sha1($_SERVER['HTTP_USER_AGENT']),time()+$auto_restrict['cookie_expiration_delay']*1440);}
	}
	// ------------------------------------------------------------------
	// user wants to logout (?logout $_GET var)
	// ------------------------------------------------------------------	
	if (isset($_GET['deconnexion'])||isset($_GET['logout'])){log_user('dis','connect');}
	// ------------------------------------------------------------------	
	// ------------------------------------------------------------------	
	// if here, there's no login/logout process.
	// Check referrer, ip
	// session duration...
	// on problem, out !
	// ------------------------------------------------------------------
	if (!is_ok()){session_destroy();include('login_form.php');exit();} 
	// ------------------------------------------------------------------

	// ------------------------------------------------------------------
	// if here, there was no security problem.
	// Now, if there is an admin password post data,
	// it means that the submitted form is a secured one:
	// check if password is correct (if not => ban ip and stop here)
	// ------------------------------------------------------------------	

	if (isset($_POST['admin_password'])){
		$pass=hash('sha512', $auto_restrict["salt"].$_POST['admin_password']);
		if ($auto_restrict['pass']!=$pass){
			add_banned_ip();
			death('The admin password is wrong... too bad !');
		}
		
	} 
	



	// ------------------------------------------------------------------	
	// crypt functions 
	// form http://www.info-3000.com/phpmysql/cryptagedecryptage.php
	// ------------------------------------------------------------------
	function GenerationCle($Texte,$CleDEncryptage) 
	  { 
	  $CleDEncryptage = md5($CleDEncryptage); 
	  $Compteur=0; 
	  $VariableTemp = ""; 
	  for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++) 
		{ 
		if ($Compteur==strlen($CleDEncryptage))
		  $Compteur=0; 
		$VariableTemp.= substr($Texte,$Ctr,1) ^ substr($CleDEncryptage,$Compteur,1); 
		$Compteur++; 
		} 
	  return $VariableTemp; 
	  }
	function Crypte($Texte,$Cle) 
	  { 
	  srand((double)microtime()*1000000); 
	  $CleDEncryptage = md5(rand(0,32000) ); 
	  $Compteur=0; 
	  $VariableTemp = ""; 
	  for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++) 
		{ 
		if ($Compteur==strlen($CleDEncryptage)) 
		  $Compteur=0; 
		$VariableTemp.= substr($CleDEncryptage,$Compteur,1).(substr($Texte,$Ctr,1) ^ substr($CleDEncryptage,$Compteur,1) ); 
		$Compteur++;
		} 
	  return base64_encode(GenerationCle($VariableTemp,$Cle) );
	  }
	function Decrypte($Texte,$Cle) 
	  { 
	  $Texte = GenerationCle(base64_decode($Texte),$Cle);
	  $VariableTemp = ""; 
	  for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++) 
		{ 
		$md5 = substr($Texte,$Ctr,1); 
		$Ctr++; 
		$VariableTemp.= (substr($Texte,$Ctr,1) ^ $md5); 
		} 
	  return $VariableTemp; 
	  }
	  

	// ------------------------------------------------------------------

	function id_user(){
		$id=$_SERVER['REMOTE_ADDR'];
		$id.='-'.$_SERVER['HTTP_USER_AGENT'];
		$id.='-'.session_id();
		return $id;	
	}

	

	function is_ok(){
		// check session vars
		// in case of problem, destroy session and redirect
		global $auto_restrict;
		$expired=false;
		
		if (!checkReferer()){return death("You are definitely NOT from here !");}
		if (!checkIP()){return death("Hey... you were banished, fuck off !");}
		if (!checkToken()){return death("You need a valid token to do that, boy !");}


		if (isset($_COOKIE[$auto_restrict['cookie_name']])&&$_COOKIE[$auto_restrict['cookie_name']]==sha1($_SERVER['HTTP_USER_AGENT'])){return true;}
		if (!isset($_SESSION['id_user'])){return false;}
		
		if ($_SESSION['expire']<time()){$expired=true;}
		
		$sid=Decrypte($_SESSION['id_user'],$auto_restrict['encryption_key']);
		$id=id_user();
		if ($sid!=$id || $expired==true){// problème d'identité
			return false;
		}else{ // all fine
			//session can survive a bit more ^^
			$_SESSION['expire']=time()+(60*$auto_restrict['session_expiration_delay']);
			return true;
		}
	}
	
	function death($msg="Don't try to be so clever !"){global $auto_restrict;if ($auto_restrict['just_die_on_errors']){die('<p class="fatal_error">'.$msg.'</p>');}else{return false;}}
	
	function log_user($login_donne,$pass_donne){
		// create session vars
		global $auto_restrict;
		if ($auto_restrict['login']===$login_donne && $auto_restrict['pass']===hash('sha512', $auto_restrict["salt"].$pass_donne)){
			$_SESSION['id_user']=Crypte(id_user(),$auto_restrict['encryption_key']);
			$_SESSION['login']=$auto_restrict['login'];	
			$_SESSION['expire']=time()+(60*$auto_restrict['session_expiration_delay']);			
			return true;
		}else if ($login_donne!='dis'&&$pass_donne!='connect'){
			add_banned_ip();
		}
		exit_redirect();
		return false;
	}

	function redirect_to($page){header('Location: '.$page); }
	function exit_redirect(){
		global $auto_restrict;
		@session_unset();
		@session_destroy();
		setcookie($auto_restrict['cookie_name'],'',time()+1);
		if ($auto_restrict['redirect_error']&&$auto_restrict['redirect_error']!=''){
				redirect_to($auto_restrict['redirect_error']);
		}else{exit($auto_restrict['error_msg']);}
	}


	// ------------------------------------------------------------------
	// REFERER 
	// ------------------------------------------------------------------
	function returndomain($url){$domaine=parse_url($url);return $domaine['host'];}
	
	function checkReferer(){
		global $auto_restrict;
		if ($auto_restrict['domain']!=$auto_restrict['referer']&&!empty($auto_restrict['referer'])){
			// log IP to ban it
			if (isset($_SERVER['REMOTE_ADDR'])){add_banned_ip();}
			return false;
		}else{return true;}
	}	


	// ------------------------------------------------------------------
	// TOKENS 
	// ------------------------------------------------------------------
	// return true if token situation is ok
	function checkToken(){
		global $auto_restrict;	
		if(empty($_POST)&&empty($_GET)){return true;}// no post or get data, no need of a token

		if (// from login form, no need of a token
			count($_POST)==2&&isset($_POST['login'])&&isset($_POST['pass'])
			||
			count($_POST)==3&&isset($_POST['login'])&&isset($_POST['pass'])&&isset($_POST['cookie'])
		){return true;} 

		// SESSION token too old ? out ! (but no ip_ban)
		if ($_SESSION['token_expiration_time']<@date('U')){ 
			return false;
		}

		// secure $_POST with token
		if (!empty($_POST)){
			if (!isset($_POST['token'])){// no token given ? get out !
				if ($auto_restrict['use_ban_IP_on_token_errors']){add_banned_ip();}
				return false;
			}
			if (!isset($_SESSION['token'])){// Problem with session token ? get out !
				if ($auto_restrict['use_ban_IP_on_token_errors']){add_banned_ip();}
				return false;
			}
			if ($_SESSION['token']!=$_POST['token']){// tokens are different ? GET OUT !
				if ($auto_restrict['use_ban_IP_on_token_errors']){add_banned_ip();}
				var_dump($_SESSION);var_dump($_POST);exit('3');
				return false;
			}
		}

		// secure $_GET with token
		if (!empty($_GET)&&$auto_restrict['use_GET_tokens_too']){
			if (!isset($_GET['token'])){// no token given ? get out !
				if ($auto_restrict['use_ban_IP_on_token_errors']){add_banned_ip();} 
				return false;
			}
			$token=$_GET['token'];
			if (!isset($_SESSION['token'])){
				if ($auto_restrict['use_ban_IP_on_token_errors']){add_banned_ip();}
				return false;
			}
		}

		// when all is fine, return true after erasing the token (one use only)
		unset($_SESSION['token']);
		unset($_SESSION['token_expiration_time']);
		return true;
	}

	// create a token, echo a hidden input, sets the session token
	// if $token_only==true, echo only the token.
	function newToken($token_only=false){
		global $auto_restrict;
		$token=hash('sha512',uniqid('',true));
		$_SESSION['token_expiration_time']=@date('U')+$auto_restrict['tokens_expiration_delay'];
		$_SESSION['token']=$token;
		if (!$token_only){echo '<input type="hidden" value="'.$token.'" name="token"/>';}
		else {echo $token;}
	}

	// repeat the previously generated token hidden input (or echo only the token)
	function sameToken($token_only=false){
		if (empty($_SESSION['token'])){echo 'Token error: no session token !';return;}
		if (!$token_only){echo '<input type="hidden" value="'.$_SESSION['token'].'" name="token"/>';}
		else {echo $_SESSION['token'];}
	}


	// ------------------------------------------------------------------
	// ADMIN ONLY PROTECTION 
	// ------------------------------------------------------------------
	// echo a password input form to secure sensitive sections
	function adminPassword($label='',$placeholder=''){
		if (!empty($label)){$label='<label for="admin_password" class="admin_password_label">'.$label.'</label>';}
		if (!empty($placeholder)){$placeholder=' placeholder="'.$placeholder.'" ';}
		echo $label.'<input id="admin_password" type="password" class="admin_password" name="admin_password" '.$placeholder.'/>';
	}


	// ------------------------------------------------------------------
	// IP 
	// ------------------------------------------------------------------
	// increment the IP counter in the banned IP file
	function add_banned_ip($ip=null){
		if(empty($ip)){$ip=$_SERVER['REMOTE_ADDR'];}
		global $auto_restrict;
		
		if (isset($auto_restrict["banned_ip"][$ip])){
			$auto_restrict["banned_ip"][$ip]['nb']++;
		}else{
			$auto_restrict["banned_ip"][$ip]['nb']=1;
		}
		
		$auto_restrict["banned_ip"][$ip]['date']=@date('U')+$auto_restrict['IP_banned_expiration_delay'];
		file_put_contents($auto_restrict["banned_ip_filename"],'<?php /*Banned IP*/ $auto_restrict["banned_ip"]='.var_export($auto_restrict["banned_ip"],true).' ?>');
	}

	function remove_banned_ip($ip=null){
		if(empty($ip)){$ip=$_SERVER['REMOTE_ADDR'];}
		global $auto_restrict;
		if (isset($auto_restrict["banned_ip"][$ip])){
			unset($auto_restrict["banned_ip"][$ip]);
		}
		file_put_contents($auto_restrict["banned_ip_filename"],'<?php /*Banned IP*/ $auto_restrict["banned_ip"]='.var_export($auto_restrict["banned_ip"],true).' ?>');
	}

	// check if user IP is banned or not
	function checkIP($ip=null){
		if(empty($ip)){$ip=$_SERVER['REMOTE_ADDR'];}
		global $auto_restrict;

		if (isset($auto_restrict["banned_ip"][$ip])){
			if ($auto_restrict["banned_ip"][$ip]['nb']<$auto_restrict['max_login_fails']){return true;} // below max login fails 
			else if ($auto_restrict["banned_ip"][$ip]['date']>=@date('U')){return false;} // active banishment 
			else if ($auto_restrict["banned_ip"][$ip]['date']<@date('U')){remove_banned_ip($ip);return true;} // old banishment 
			return false;
		}else{return true;}// ip is ok
	}
?>
