
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
   $(document).ready(function(){
    $('#mitabla').DataTable({
      // "order": [[1, "asc"]],
      "language":{
        "lengthMenu": "Mostrar _MENU_ registros por página",
        "info": "Mostrando página _PAGE_ de _PAGES_",
        "infoEmpty": "No hay registros disponibles",
        "infoFiltered": "(filtrada de _MAX_ registros)",
        "loadingRecords": "Cargando...",
        "processing":     "Procesando...",
        "search": "Buscar:",
        "zeroRecords":    "No Se Encontró Ningun Registro",
        "paginate": {
          "next":       "Siguiente",
          "previous":   "Anterior"
        },          
      }
    }); 
  }); 
</script>

<body>
	<div class="container">
		<h4>Prospectos</h4>

    <div class="form-control" style="margin-top: 1%;">
      <div class="table-responsive">
       <table id="mitabla" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Primer Apellido</th>
                <th>Segundo Apellido</th>
                <th>Estatus</th>
                <th></th>

            </tr>
        </thead>
        <tbody>

          <?php
            $user_obj = new Cl_User();
            echo $data = $user_obj->DatosProspecto();
            ?>
            
        </tbody>

    </table>
  </div>
    </div>
  </div>
</body>

<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>