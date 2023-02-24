<?php
ob_start();
@session_start();
if(!isset($_SESSION['logged_in']))
{
	header('Location: index.php');
}


?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="./css/bootstrap.min.css" rel="stylesheet">
<script src="./js/bootstrap.min.js"></script>
<script src="./js/bootstrap.bundle.min.js"></script>
<link href="./css/General.css" rel="stylesheet">

<title>Sistema</title>
 <link rel="shortcut icon" href="./img/logo.png">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<div class="container">
  <div class="container-fluid">
  	
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php IF($_SESSION['Privilegios']==3 or $_SESSION['Privilegios']==1){ ?>
        <li class="nav-item">
          <a class="nav-link " href="Registro.php">Reguistro</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="Prospecto.php">Prospecto</a>
        </li>
    	<?php }?>
		<?php IF($_SESSION['Privilegios']==2 or $_SESSION['Privilegios']==1){ ?>
        <li class="nav-item">
          <a class="nav-link" href="Evaluacion.php">Evaluacion</a>
        </li>
        <?php }?>
      </ul>
      <div class="d-flex">
      	
      	<ul class="navbar-nav me-auto mb-2 mb-lg-0">
      	<li class="nav-item">
      		<div class="nav-link">Bienvenido, <?php echo $_SESSION['Usuario']; ?></div>
      	</li>
      	<li class="nav-item">
      		<div class="nav-link"> </div>
      	</li>
        <li class="nav-item">

          <a class="nav-link" href="./logout.php">Salir</a>
        </li>
      </ul>
      </div>
    </div>
	</div>
  </div>
</nav>

