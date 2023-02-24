<?php require_once "front/barra/header.php"; ?>


<?php 

if($_SESSION['Privilegios']==3 or $_SESSION['Privilegios']==1){ 
	if(!empty($_POST)){
		require_once 'config.php';
		try {
			$user_obj = new Cl_User();
			$data = $user_obj->RegistroProspecto( $_POST );
			if($data)$success = USER_REGISTRATION_SUCCESS;
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	}

		require_once "front/FRM/FRM_Registro.php";
		require_once "front/pie/pie.php";
}
else
{
	header('location: index.php');
}

?>
