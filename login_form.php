<style>
	.form_content{
		/* Verdana-based sans serif stack */ font-family: Corbel, "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", "Bitstream Vera Sans", "Liberation Sans", Verdana, "Verdana Ref", sans-serif;

		box-shadow:0 1px 2px black;border-radius:3px;margin:auto; 
		border:1px solid rgba(0,0,0,0.2); font-size:18px;
		background-color:rgba(0,0,0,0.2);width:320px;padding:10px;
		text-shadow:0 1px 1px white;color:rgba(0,0,0,0.8);
	}
	h1{font-size:22px; text-align: center}
	label{display:block;}
	input{width:100%;}
	input[type=checkbox]+label{display:inline;width:auto;cursor:pointer;}
	input[type=checkbox]{display:inline;width:auto;}

	input{border-radius:3px;width:100%;}
	input[type=submit]{border:1px solid rgba(0,0,0,0.2);background-color:rgba(0,0,0,0.2);box-shadow: 0 1px 2px rgba(0,0,0,0.1)}
	input[type=submit]:hover{border:1px solid rgba(0,0,0,0.3);background-color:rgba(0,0,0,0.3);box-shadow: 0 1px 2px rgba(0,0,200,0.1)}
	#login, #pass{border:1px solid rgba(0,0,0,0.3);padding:3px;background-color:rgba(255,255,255,0.8);box-shadow: inset 0 1px 2px rgba(0,0,0,0.3)}
	#login{margin-bottom:20px;}
	#login:focus{background-color:white;border-color:lightblue;box-shadow:0 0 3px blue;}
	#pass:focus{background-color:white;box-shadow:0 0 3px red;}

	@media (max-width:600px){
		.form_content{width:90%;font-size:26px!important;}
		input{font-size:26px!important;}
	}
	@viewport{
	    width: device-width;
	    zoom:1;
	}
</style>

<div class="form_content">
	<form action='' method='post' name='' >
		<p class="logo">
			<svg width="300" height="250" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg">
			 <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->

			 <g>
			  <title>Layer 1</title>
			  <path fill="#000000" stroke="#000000" stroke-width="0" d="m203.05199,88.98661c0,0 9.2739,-38.36119 0,-51.33675c-9.27028,-13.01038 -12.97351,-21.64902 -33.42244,-27.85244s-12.97615,-4.96989 -27.81538,-4.33692c-14.8825,0.63395 -27.25573,8.64045 -27.25573,12.97328c0,0 -9.27113,0.6353 -12.97613,4.33883c-3.73602,3.73815 -9.90743,21.04728 -9.90743,25.3855s3.10317,33.42191 6.17141,39.59352l-3.66985,1.23498c-3.10233,35.8905 12.37569,40.22755 12.37569,40.22755c5.5695,33.42406 11.14449,19.21257 11.14449,27.85426s-5.57498,5.56979 -5.57498,5.56979s-4.93528,13.60822 -17.30812,18.58017c-12.37816,4.93597 -81.0934,31.52229 -86.66314,37.12648c-5.57012,5.56905 -4.93822,31.5547 -4.93822,31.5547l294.54443,0c0,0 0.63513,-25.98566 -4.97089,-31.5547c-5.57391,-5.60419 -74.25662,-32.19051 -86.629,-37.12648c-12.375,-4.97192 -17.31274,-18.58017 -17.31274,-18.58017s-5.60089,3.103 -5.60089,-5.56979s5.60089,5.56979 11.17001,-27.85426c0,0 15.44371,-4.33704 12.37801,-40.22755l-3.73911,0z" id="svg_1" stroke-linejoin="bevel" opacity="0.5"/>
			 </g>
			</svg>
		</p>
		<h1>
			<?php 
				$f=file_exists($auto_restrict['path_to_files'].'/auto_restrict_pass.php');
				if($f){echo '<h1>Identifiez-vous</h1>';}else{echo '<h1>Creez votre passe</h1>';} 
			?>
		</h1>
			<hr/>
			<label for='login'>Login </label>
			<input type='text' name='login' id='login' required="required" autofocus/>
			
		<label for='pass'>Passe </label>
		<input type='password' name='pass' id='pass'  required="required"/>	

		
		<?php if($f){echo '<hr/><input id="cookie" type="checkbox" value="cookie" name="cookie"/><label for="cookie">Rester connecté</label>';} ?>
		
		
		<hr/>
		<input type='submit' value='Connexion'/>	
	</form>
</div>
