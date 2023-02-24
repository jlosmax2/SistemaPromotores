<?php
require_once 'Cl/messages.php';

define( 'DB_HOST', 'DESKTOP-4SJB32B\SQLEXPRESS' );//Servidor de la base de datos
 $NombreBD = array( "Database"=>"Sistema");
define( 'DB_NAME', $NombreBD);//Nombre de la base de datos

spl_autoload_register(function ($class)
{
	$parts = explode('_', $class);
	$path = implode(DIRECTORY_SEPARATOR,$parts);
	require_once $path . '.php';
});