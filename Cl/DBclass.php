<?php
class Cl_DBclass
{
	/**
	 * @var $con llevará a cabo la conexión de base de datos
	 */
	public $con;
	
	/**
	 * Esto creará la conexión de base de datos
	 */
	public function __construct()
	{
		$this->con = sqlsrv_connect(DB_HOST, DB_NAME);

		if(!$this->con ) {
			echo "Conexión no se pudo establecer.<br />";
		    die( print_r( sqlsrv_errors(), true));
		}
	}
}