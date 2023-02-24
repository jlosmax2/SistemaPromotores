<?php require_once "front/barra/header.php"; ?>
<?php 

if($_SESSION['Privilegios']==2 or $_SESSION['Privilegios']==1){ 
	require_once "config.php";
	if(!empty($_POST)){
		
		try {
			$user_obj = new Cl_User();
			$data = $user_obj->Evaluar( $_POST );
			if($data)$success = USER_REGISTRATION_SUCCESS;
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
	require_once "./front/FRM/FRM_EvaluarProspecto.php";
	require_once "./front/pie/pie.php";
}
else
{
	header('location: logout.php');
}