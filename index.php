<?php 
ob_start();
session_start();
require_once 'config.php'; 
?>
<?php 
	if( !empty( $_POST )){
		try {
			
			$user_obj = new Cl_User();
			$data = $user_obj->login( $_POST );
			if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'])
			{
				if($_SESSION['Privilegios'] == '1')
				{
					header('location: Registro.php');
				}
				if($_SESSION['Privilegios'] == '2')
				{
						header('location: Evaluacion.php');
				}
				if($_SESSION['Privilegios']=='3')
				{
					header('Location: Registro.php');
				}
			}
		} 
		catch (Exception $e) 
		{
			$error = $e->getMessage();
		}
	}
	//print_r($_SESSION);
	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Promotores</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/logo.png">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </head>
  
  <body >
  	
	<div class="container">

		<?php require_once 'templates/ads.php';?>
		<div class="login-form">
			<?php require_once 'templates/message.php';?>
			<div class="form-header">
				<i class=""><img src="img/logo.png" width="90" height="90"></i>
			</div>
			<form id="login-form" method="post" class="form-signin" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input name="Usuario" type="text" class="form-control" placeholder="USUARIO" autofocus> 
				<input name="password" id="password" type="password" class="form-control" placeholder="CONTRASEÑA"> 
				<button class="btn btn-block bt-login" type="submit" id="submit_btn" data-loading-text="Iniciando....">Iniciar sesión</button>
			</form>

		</div>
	</div>

	<!-- /container -->
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/login.js"></script>
  </body>

</html>

