<?php require_once "./front/barra/header.php"; ?>

<?php 

if($_SESSION['Privilegios']==3 or $_SESSION['Privilegios']==1){ 
		require_once "config.php";
		require_once "front/FRM/FRM_Prospecto.php";
		require_once "front/pie/pie.php";

}
else
{
	header('location: logout.php');
}


