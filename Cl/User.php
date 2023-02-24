<?php
class Cl_User
{
	/**
	 * @var va a contener la conexión de base de datos
	 */
	protected $_con;
	
	/**
	 * Inializar DBclass 
	 */

	public function __construct()
	{
		$db = new Cl_DBclass();
		$this->_con = $db->con;
	}
	/**
	 * Registro de usuarios
	 * @param array $data
	  */

	/**
	 * Este metodo para iniciar sesión
	 * @param array $data
	 * @return retorna falso o verdadero
	 */
	public function login( array $data )
	{
		$_SESSION['logged_in'] = false;
		if( !empty( $data ) )
		{
			
			// Trim todos los datos entrantes:
			$trimmed_data = array_map('trim', $data);
			
			// escapar de las variables para la seguridad
			$Usuario =  $trimmed_data['Usuario'];
			$password =  $trimmed_data['password'];


			$query="select count(*) AS Registro from Usuarios U inner join TiposUsuarios TS on U.IdTipoUsuario=TS.IdTipoUsuario where U.Usuario = '$Usuario' AND U.Contrasena='$password' ";
			$result = sqlsrv_query($this->_con, $query);
			$row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC);
			if ($row['Registro']==1) {
				$query="select * from Usuarios U inner join TiposUsuarios TS on U.IdTipoUsuario=TS.IdTipoUsuario where U.Usuario = '$Usuario' AND U.Contrasena='$password' ";

				$result = sqlsrv_query($this->_con, $query);
				if($result === false) {
				    die(print_r(sqlsrv_errors(), true));
				}
				$row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC);
				$_SESSION = $row;
				$_SESSION['logged_in'] = true;
				 sqlsrv_close($this->_con);
				return true;
			}
			else
			{
				sqlsrv_close($this->_con);
				 throw new Exception( LOGIN_FAIL );
			}

		} else{
			throw new Exception( LOGIN_FIELDS_MISSING );
		}
	}
	
	/**
	 * Este metodo para cerrar las sesión
	 */
	public function logout()
	{
		session_unset();
		session_destroy();
		header('Location: index.php');
	}

	public function RegistroProspecto( array $data )
	{
		if( !empty( $data ) )
		{
			// Trim todos los datos entrantes:

			
			// escapar de las variables para la seguridad
			$Nombre =  $_POST['Nombre'];
			$filename = $_POST['NombreDocumentacion'];
			$ApePaterno =  $_POST['ApePaterno'];
			$ApeMaterno =  $_POST['ApeMaterno'];
			$Calle =  $_POST['Calle'];
			$NCasa =  $_POST['NCasa'];
			$Colonia =  $_POST['Colonia'];
			$CP =  $_POST['CP'];
			$RFC =  $_POST['RFC'];
			$Celular =  $_POST['Celular'];
			$Documentacion =  $_POST['Documentacion'];
			$IdUsuario = $_SESSION['IdUsuario'];


			$query="INSERT INTO Prospectos (Nombre,ApePaterno,ApeMaterno,Celular,RFC,IdEstatus,IdUsuario) 
			VALUES ('$Nombre','$ApePaterno','$ApeMaterno','$Celular','$RFC',1,$IdUsuario)";
			IF(sqlsrv_query($this->_con, $query))
			{
				$queryIdProspecto="SELECT IdProspecto FROM Prospectos WHERE RFC='$RFC'";
				$resultIdProspecto = sqlsrv_query($this->_con, $queryIdProspecto);
				$rowIdProspecto = sqlsrv_fetch_array( $resultIdProspecto, SQLSRV_FETCH_ASSOC);
				$IdProspecto = $rowIdProspecto['IdProspecto'];
				$query="INSERT INTO Domicilio (IdProspecto,Calle,NumeroCasa,Colonia,CP) 
   						VALUES ($IdProspecto,'$Calle','$NCasa','$Colonia','$CP')";
   					IF(sqlsrv_query($this->_con, $query))
					{
						foreach($_FILES["file"]['tmp_name'] as $key => $tmp_name)
						{
							//Validamos que el file exista
							if($_FILES["file"]["name"][$key]) {
								//$filename = $_POST['NombreDocumentacion']; //Obtenemos el nombre original del file
								$source = $_FILES["file"]["tmp_name"][$key]; //Obtenemos un nombre temporal del file
								$Tipo = $_FILES['file']['type'][$key];
								$fileExt=explode('/', $Tipo);
								print_r($fileExt[1]);
								$directorio = 'docs/'; //Declaramos un  variable con la ruta donde guardaremos los files
								$NombreCompletoDocumento = $filename[$key].'.'.$fileExt[1];
								//Validamos si la ruta de destino existe, en caso de no existir la creamos
								if(!file_exists($directorio)){
									mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
								}
								
								$dir=opendir($directorio); //Abrimos el directorio de destino
								$target_path = $directorio.'/'.$NombreCompletoDocumento; //Indicamos la ruta de destino, así como el nombre del file
								
								//Movemos y validamos que el file se haya cargado correctamente
								//El primer campo es el origen y el segundo el destino
								if(move_uploaded_file($source, $target_path)) 
								{	
									$query="INSERT INTO Documentos (IdProspecto,NombreArchivo) VALUES ($IdProspecto,'$NombreCompletoDocumento')";
										IF(sqlsrv_query($this->_con, $query))
										{

											print "<script>alert(\"Insercion correcta.\");window.location='Registro.php';</script>";
										}
									} else {	
									print "<script>alert(\"Ha ocurrido un error, por favor inténtelo de nuevo.\");window.location='Registro.php';</script>";
								}
								closedir($dir); //Cerramos el directorio de destino
							}
						}
					}
			}
			ELSE{
				print "<script>alert(\"Error al realizar la Insercion.\");window.location='Registro.php';</script>";
			}			
		}
	}
	
	public function DatosProspecto()
	{
		$IdUsuario = $_SESSION['IdUsuario'];
		$Rango = $_SESSION['IdTipoUsuario'];
		$FRM="";
		if($Rango==1 or $Rango==2)
		{
			$query="SELECT P.*,D.*,O.Observacion,EP.NomEstatus FROM Prospectos P 
				LEFT JOIN EstatusProspectos EP ON P.IdEstatus=EP.IdEstatus 
				LEFT JOIN Domicilio D ON P.IdProspecto=D.IdProspecto
				LEFT JOIN Observaciones O ON O.IdProspecto=D.IdProspecto";
		}
		else
		{
			$query="SELECT P.*,D.*,O.Observacion,EP.NomEstatus FROM Prospectos P 
				LEFT JOIN EstatusProspectos EP ON P.IdEstatus=EP.IdEstatus 
				LEFT JOIN Domicilio D ON P.IdProspecto=D.IdProspecto
				LEFT JOIN Observaciones O ON O.IdProspecto=D.IdProspecto
				where P.IdUsuario in ($IdUsuario)";
		}
		
		$result = sqlsrv_query($this->_con, $query);
        while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {

        	$IdProspecto= $row['IdProspecto'];
      		$FRM.= '<tr>
		            	<td>'.$row['Nombre'].'</td>
		            	<td>'.$row['ApePaterno'].'</td>
		            	<td>'.$row['ApeMaterno'].'</td>
		            	<td>'.$row['NomEstatus'].'</td>';

		            	if ($Rango==1 or $Rango==3) 
		            	{
		            		$FRM.= '<td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Prospecto_'.$IdProspecto.'">
							  VER
							</button>

							<!-- Modal -->
							<div class="modal fade" id="Prospecto_'.$IdProspecto.'"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
							  <div class="modal-dialog">
							    <div class="modal-content">
							      <div class="modal-header">
							        <h4 class="modal-title" id="staticBackdropLabel">Prospecto</h4>
							        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							      </div>
							      <div class="modal-body">
							      	<div class="form-control" style="margin-top: 1%;">
							      	Datos Principales
							      		<div class="row">
            								<div class="col-md-12">
							        			<b>Nombre: </b>'.$row['Nombre'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Primer Apellido: </b>'.$row['ApePaterno'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Segundo Apellido: </b>'.$row['ApeMaterno'].'
							        		</div>
							        	</div>
							        </div>
							        <div class="form-control" style="margin-top: 1%;">
							      	Domicilio
							      		<div class="row">
            								<div class="col-md-12">
							        			<b>Calle: </b>'.$row['Calle'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Numero de casa: </b>'.$row['NumeroCasa'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Colonia: </b>'.$row['Colonia'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Codigo Postal: </b>'.$row['CP'].'
							        		</div>
							        </div>
							      </div>
							      <div class="form-control" style="margin-top: 1%;">
							      	Datos Generales
							      		<div class="row">
            								<div class="col-md-12">
							        			<b>RFC: </b>'.$row['RFC'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Numero de Celular: </b>'.$row['Celular'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Documentos: </b><br>';

							        			$RegDocQ="SELECT COUNT(*) AS RegistroDoc  FROM Documentos where IdProspecto in ($IdProspecto)";
							        			$Regresult = sqlsrv_query($this->_con, $RegDocQ);	
							        			$Reg = sqlsrv_fetch_array( $Regresult, SQLSRV_FETCH_ASSOC);
							        		if($Reg['RegistroDoc']!=0)
							        		{
							        			$DocQ="SELECT IdDocumento,IdProspecto,NombreArchivo  FROM Documentos where IdProspecto in ($IdProspecto)";
							        			// print "<script>alert(\"$DocQ .\");window.location='Prospecto.php';</script>";

												$result = sqlsrv_query($this->_con, $DocQ);		
													
										        while($Doc = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
										        {
										        	$NombreCompletoDocumento= $Doc['NombreArchivo'];
							        				$FRM.= '<a href="./docs/'.$NombreCompletoDocumento.'" target="_blank">'. $NombreCompletoDocumento.'</a><br>';
							        			}
						        			}else
						        			{
						        				$FRM.='Sin Documentos';
						        			}

							        		$FRM.= '</div>';
							        		
							        $FRM.= '</div>
							      </div>
							      <div class="form-control" style="margin-top: 1%;">
							      	Adicional
							      		<div class="row">
            								<div class="col-md-12">
							        			<b>Estatus: </b>'.$row['NomEstatus'].'
							        		</div>
							        		';
							        		IF($row['IdEstatus']==3){$FRM.= '
							        		<div class="col-md-12">
							        			<b>Opservacion: </b>'.$row['Observacion'].'
							        		</div>';
							        		}
							        		$FRM.= '</div>
							      </div>
							      <div class="modal-footer">
							        
							      </div>
							    </div>
							  </div>
							</div>
							</td>';
		            	}
		            	elseif ($Rango==1 or $Rango==2) 
		            	{
		            		$FRM.= '<td><a class="btn btn-primary" href="./EvaluarProspecto.php">Evaluar</a></td>';
		            	}

		            	

		        	$FRM.= '</tr>';
		}
		return $FRM;
	}


	public function Evaluar()
	{
		$Entrante= $_POST['Autorizar'];
		$fileExt=explode('_', $Entrante);
		$IdProspecto=$fileExt[0];
		$Estatus=$fileExt[1];
		$queryEstatus="update prospectos set IdEstatus=$Estatus where IdProspecto in ($IdProspecto)";


		if(sqlsrv_query($this->_con, $queryEstatus))
		{

			if($Estatus==3)
			{
				$observacion= $_POST['observacion'];
				$IdUsuario = $_SESSION['IdUsuario'];
				$queryobservacion="INSERT INTO Observaciones (Idprospecto,IdUsuario,Observacion) VALUES ($IdProspecto,$IdUsuario,'$observacion')";

				if(!sqlsrv_query($this->_con, $queryobservacion))
				{
					sqlsrv_close($this->_con);
					print "<script>alert(\"ERROR #318 .\");window.location='EvaluarProspecto.php?P=$IdProspecto';</script>";
				}
				
			}
		}
		else
		{
			sqlsrv_close($this->_con);
			print "<script>alert(\"ERROR #311 .\");window.location='EvaluarProspecto.php?P=$IdProspecto';</script>";
		}
		sqlsrv_close($this->_con);
		print "<script>alert(\"ACTUALIZACIÓN DE ESTATUS CORRECTO.\");window.location='EvaluarProspecto.php?P=$IdProspecto';</script>";
	}

	public function DAtosGeneralesEvaluacion($IdProspecto)
	{
		$IdUsuario = $_SESSION['IdUsuario'];
		$Rango = $_SESSION['IdTipoUsuario'];
		$FRM="";
		$Id = $IdProspecto;
		if($Rango==1 or $Rango==2)
		{
			$query="SELECT P.*,D.*,O.Observacion,EP.NomEstatus FROM Prospectos P 
				LEFT JOIN EstatusProspectos EP ON P.IdEstatus=EP.IdEstatus 
				LEFT JOIN Domicilio D ON P.IdProspecto=D.IdProspecto
				LEFT JOIN Observaciones O ON O.IdProspecto=D.IdProspecto
				where P.IdProspecto in ($Id)";
		}
		else
		{
			print "<script>alert(\"NO TIENES PERMISO PARA ESTAR EN EST PAGINA .\");window.location='logout.php';</script>";
		}
		
		$result = sqlsrv_query($this->_con, $query);
        $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC);

        	$IdProspecto= $row['IdProspecto'];
			 $FRM.= '<div class="form-control" style="margin-top: 1%;">
							      	Datos Principales
							      		<div class="row">
            								<div class="col-md-12">
							        			<b>Nombre: </b>'.$row['Nombre'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Primer Apellido: </b>'.$row['ApePaterno'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Segundo Apellido: </b>'.$row['ApeMaterno'].'
							        		</div>
							        	</div>
							        </div>
							        <div class="form-control" style="margin-top: 1%;">
							      	Domicilio
							      		<div class="row">
            								<div class="col-md-12">
							        			<b>Calle: </b>'.$row['Calle'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Numero de casa: </b>'.$row['NumeroCasa'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Colonia: </b>'.$row['Colonia'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Codigo Postal: </b>'.$row['CP'].'
							        		</div>
							        </div>
							      </div>
							      <div class="form-control" style="margin-top: 1%;">
							      	Datos Generales
							      		<div class="row">
            								<div class="col-md-12">
							        			<b>RFC: </b>'.$row['RFC'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Numero de Celular: </b>'.$row['Celular'].'
							        		</div>
							        		<div class="col-md-12">
							        			<b>Documentos: </b><br>';

							        			$RegDocQ="SELECT COUNT(*) AS RegistroDoc  FROM Documentos where IdProspecto in ($IdProspecto)";
							        			$Regresult = sqlsrv_query($this->_con, $RegDocQ);	
							        			$Reg = sqlsrv_fetch_array( $Regresult, SQLSRV_FETCH_ASSOC);
							        		if($Reg['RegistroDoc']!=0)
							        		{
							        			$DocQ="SELECT IdDocumento,IdProspecto,NombreArchivo  FROM Documentos where IdProspecto in ($IdProspecto)";
							        			// print "<script>alert(\"$DocQ .\");window.location='Prospecto.php';</script>";

												$result = sqlsrv_query($this->_con, $DocQ);		
													
										        while($Doc = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
										        {
										        	$NombreCompletoDocumento= $Doc['NombreArchivo'];
							        				$FRM.= '<a href="./docs/'.$NombreCompletoDocumento.'" target="_blank">'. $NombreCompletoDocumento.'</a><br>';
							        			}
						        			}else
						        			{
						        				$FRM.='Sin Documentos';
						        			}

							        		$FRM.= '</div>';
							        		
							        $FRM.= '</div>
							      </div>
							      <div class="form-control" style="margin-top: 1%;">
							      	Adicional
							      		<div class="row">
            								<div class="col-md-12">
							        			<b>Estatus: </b>'.$row['NomEstatus'].'
							        		</div>
							        		';
							        		IF($row['IdEstatus']==3){$FRM.= '
							        		<div class="col-md-12">
							        			<b>Opservacion: </b>'.$row['Observacion'].'
							        		</div>';
							        		}
							    $FRM.= '</div></div>';


		return $FRM;
	}

	public function EvalucionProspecto()
	{
		$IdProspecto= $_POST['Evaluacion'];

		//print "<script>alert(\"ID: $IdProspecto.\");window.location='Evaluacion.php';</script>";
		header('location: ./EvaluarProspecto.php?P='.$IdProspecto);

	}

	public function DatosProspectoEvaluacion()
	{
		$IdUsuario = $_SESSION['IdUsuario'];
		$Rango = $_SESSION['IdTipoUsuario'];
		$FRM="";
		if($Rango==1 or $Rango==2)
		{
			$query="SELECT P.*,D.*,EP.NomEstatus FROM Prospectos P 
				LEFT JOIN EstatusProspectos EP ON P.IdEstatus=EP.IdEstatus 
				LEFT JOIN Domicilio D ON P.IdProspecto=D.IdProspecto";
		}

		
		$result = sqlsrv_query($this->_con, $query);
        while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {

        	$IdProspecto= $row['IdProspecto'];
      		$FRM.= '<tr>
		            	<td>'.$row['Nombre'].'</td>
		            	<td>'.$row['ApePaterno'].'</td>
		            	<td>'.$row['ApeMaterno'].'</td>
		            	<td>'.$row['NomEstatus'].'</td>';

		            	if ($Rango==1 or $Rango==2) 
		            	{
		            		$FRM.= '<td style="text-align: center"><button class="btn btn-info"value="'.$IdProspecto.'" name="Evaluacion" type="submit">Evaluacion</button>
		            			</td>';
		            	}

		            	

		        	$FRM.= '</tr>';
		}
		return $FRM;
	}



	
	public function RegistroDocumentos()
	{
		$IDUsuario = $_POST['IDUsuario'];

		//$IDUsuario=$_SESSION['IDUsuario'];
		$query="SELECT * FROM catalogotipodocumentos";
		$result = mysqli_query($this->_con, $query);
		$row=mysqli_fetch_array($result);
		$queryConvocatoria="SELECT MAX(IDUnionUsuCon) as IDUnionUsuCon from usuarios_convocatorias where IDUsuario='$IDUsuario'";
		$resultConvocatoria = mysqli_query($this->_con, $queryConvocatoria);
		$rowConvocatoria=mysqli_fetch_array($resultConvocatoria);
		$IDUnionUsuCon=$rowConvocatoria['IDUnionUsuCon'];
		do
			{
				
				$IDTipoDocumentos=$row['IDTipoDocumentos'];
			
				if( !empty( $_POST[$row['IDTipoDocumentos']] ) )
					{
						if ($_FILES)
						{
							if($IDTipoDocumentos)
							{     
								//print_r($_FILES);
					 			//$IDTipoDocumentos=1;
					 				$fileSize=$_FILES[$row['IDTipoDocumentos']]['size']; 
					 				$file=$_FILES[$row['IDTipoDocumentos']];
					 				
					 				
					 			if($fileSize>0)
					 			{
					 					
									    $fileName=$_FILES[$row['IDTipoDocumentos']]['name'];
									    $fileTmpName=$_FILES[$row['IDTipoDocumentos']]['tmp_name']; 
									    $fileError=$_FILES[$row['IDTipoDocumentos']]['error']; 
									    $fileType=$_FILES[$row['IDTipoDocumentos']]['type'];    
									    $fileExt=explode('.', $fileName);
									    $fileActuakExt=strtolower(end($fileExt));
									    $fileName='PDF_'.$row['IDTipoDocumentos'].'_'.$IDUsuario.'_'.$IDUnionUsuCon.'.pdf';
									    $filedestino='Documentos/'.$fileName;
									    $allowed=array('pdf');
									  
									    $queryNombreDocumento="SELECT NombreDocumento from documentos WHERE IDTipoDocumentos='$IDTipoDocumentos' and IDUnionUsuCon='$IDUnionUsuCon'";

									    $resultado=mysqli_query($this->_con, $queryNombreDocumento);
										$rowNombreDocumento=mysqli_fetch_array($resultado);

								    if (in_array($fileActuakExt, $allowed))
								    {
								      if ($fileError===0) 
								      	{
								          if ($fileSize<5000000) 
								          	{
								          		if (!empty($rowNombreDocumento['NombreDocumento'])) 
								          		{
								          			$filedestino='Documentos/'.$rowNombreDocumento['NombreDocumento'];
								          		}
								          		
								            	if (is_file($filedestino)) 
								               		{    
								                    	if(unlink($filedestino))
								                    	{
								                    		//se borra el nombre de la bd y se coloca el nuevo nombre
								                    		$dato='';

																$dato.=date('Y');
																$dato.=date('m');
																$dato.=date('j');
																$dato.=date('h');
																$dato.=date('i');
																$dato.=date('s');

																$fileName='PDF_'.$row['IDTipoDocumentos'].'_'.$IDUsuario.'_'.$dato.'.pdf';
																$query="UPDATE `documentos` SET NombreDocumento='$fileName', Progreso='1' WHERE IDTipoDocumentos='$IDTipoDocumentos' and IDUnionUsuCon='$IDUnionUsuCon'";
															mysqli_query($this->_con, $query);
								                    		$filedestino='Documentos/'.$fileName;
								                    		if (move_uploaded_file($fileTmpName, $filedestino)) 
								                    		{
								                    			
								                    			
																	header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
																	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
																	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
																	header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE 
																	clearstatcache();
																	mysqli_close($this->_con);
								                    			print "<script>alert(\"Documento cambiado exitosamente.\");window.location='documentos.php';</script>";
								                    		}
								                    		else
								                    		{
								                    			mysqli_close($this->_con);
								                    			print "<script>alert(\"hay un problema para remplasar el archivo.\");window.location='documentos.php';</script>";
								                    		}
								                    		
								                    	}
								                    	else
								                    	{
								                    		print "<script>alert(\"hay un problema al remplazar el archivo.\");window.location='documentos.php';</script>";
								                    	}
								                	}
								           		else
								                {
												
												if(move_uploaded_file($fileTmpName, $filedestino))
													{
										
														
													}
													else
													{
														print "<script>alert(\"Hay un problema al cargar su archivo. #$filedestino \");window.location='documentos.php';</script>";
													}
								        		 }
								        	}
								          	else
									          {
									            throw new Exception( ARCHIVO_GRANDE );
									          }
								    	}
									      else
										      {
										         throw new Exception(ERRORSUBIR);
										      }
								    }
										    else
										    {
										       throw new Exception(DOCUMENTOPDF);
										    }
					   			}
					   			else
					   			{
					   				throw new Exception(ERROR);
					   			}
							}
						}
						else
						{
							throw new Exception(TRANSFERENCIA);
						}
					}
			}while($row=mysqli_fetch_array($result));
		
		
	}


	

	
}