<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-register" role="form" id="register-form" enctype="multipart/form-data">

<div class="container" style=" margin-top: 1%;">
    <div class="form-control" style="margin-top: 1%;">
    <h4>Registro del prospecto</h4>
    
    <div class="form-control" style="margin-top: 1%;">
        <div class="row">
            <div class="col-md-12">
                Datos Principales

            </div>
            <div class="col-md-4">
                <label for="exampleFormControlInput1" class="form-label">Nombre *</label>
                <span class="col-md-1 col-md-offset-2 text-center"><i class="fa fa-user bigicon"></i></span>
                <input id="Nombre" name="Nombre" type="text" placeholder="Nombre" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="exampleFormControlInput1" class="form-label">Primer Apellido *</label>
                <span class="col-md-1 col-md-offset-2 text-center"><i class="fa fa-user bigicon"></i></span>
                <input id="fname" name="ApePaterno" type="text" placeholder="Primer Apellido" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="exampleFormControlInput1" class="form-label">Segundo Apellido</label>
                <span class="col-md-1 col-md-offset-2 text-center"><i class="fa fa-user bigicon"></i></span>
                <input id="lname" name="ApeMaterno" type="text" placeholder="Segundo Apellido" class="form-control">
            </div>
        </div>
    </div>
    <div class="form-control" style="margin-top: 1%;">
        <div class="row">
            <div class="col-md-12">

                Domicilio
            </div>
              <div class="col-md-3">
                <label for="exampleFormControlInput1" class="form-label">Calle *</label>
                <span class="col-md-1 col-md-offset-2 text-center"><i class="fa fa-user bigicon"></i></span>
                <input id="Calle" name="Calle" type="text" placeholder="Calle" class="form-control" required>      
            </div>
            <div class="col-md-3">
                <label for="exampleFormControlInput1" class="form-label">Numero de casa *</label>
                <span class="col-md-1 col-md-offset-2 text-center"><i class="fa fa-user bigicon"></i></span>
                <input id="Ncasa" name="NCasa" type="text" placeholder="Numero de casa" class="form-control" required>      
            </div>
            <div class="col-md-3">
                <label for="exampleFormControlInput1" class="form-label">Colonia *</label>
                <span class="col-md-1 col-md-offset-2 text-center"><i class="fa fa-user bigicon"></i></span>
                <input id="Colonia" name="Colonia" type="text" placeholder="Colonia" class="form-control" required>      
            </div>
            <div class="col-md-3">
                <label for="exampleFormControlInput1" class="form-label">Codigo Postal *</label>
                <span class="col-md-1 col-md-offset-2 text-center"><i class="fa fa-user bigicon"></i></span>
                <input id="CP" name="CP" type="text" placeholder="Codigo Postal" class="form-control" required>      
            </div>

        </div>
    </div>
    <div class="form-control" style="margin-top: 1%;">
        <div class="row">
            <div class="col-md-12">
                Datos Generales
            </div>
            <div class="col-md-3">
              <label for="exampleFormControlInput1" class="form-label">RFC *</label>
                <span class="col-md-1 col-md-offset-2 text-center"><i class="fa fa-user bigicon"></i></span>
                <input id="RFC" name="RFC" type="text" placeholder="RFC" class="form-control" maxlength="13" required>  
            </div>
            <div class="col-md-3">
              <label for="exampleFormControlInput1" class="form-label">Numero Celular *</label>
                <span class="col-md-1 col-md-offset-2 text-center"><i class="fa fa-user bigicon"></i></span>
                <input id="Celular" name="Celular" type="text" placeholder="Numero Celular" class="form-control" maxlength="10" required>  
            </div>
        </div>


    </div>
    <div class="form-control" style="margin-top: 1%;">
        <div class="row">
            <div class="col-md-12">
                Documentacion
            </div>
            <div class="col-md-12">
                <div id="newRow"></div>
            </div>
            <div class="col-md-2">
                <!-- <br><button id="addRow" type="button" class="btn btn-info">Agregar Documento</button> -->
                <br><a><img id="addRow" src="./img/add.png" width="40" height="40" /></a>
            </div>
        </div>
    </div>

        <div style="margin-top:1%">
            <button type="submit" class="btn btn-primary">Registro de prospecto</button>
        </div>
    </div>
    </div>
</form>
</body>

<script type="text/javascript">
// agregar registro
$("#addRow").click(function () {
var html = '';
html += '<div id="inputFormRow" class="form-control" style="margin-top: 1%;">';
html += '';

html += '<label for="exampleFormControlInput1" class="form-label">Nombre del Documento *</label>';
html += '<span class="col-md-1 col-md-offset-2 text-center"><i class="fa fa-user bigicon"></i></span>';
html += '<br><input name="NombreDocumentacion[]" type="text" placeholder="Nombre del Documento" class="form-control" required>';
html += '<br><input type="file" name="file[]" class="form-control m-input" required>';

html += '<div class="input-group-append">';
html += '<br><button id="removeRow" type="button" class="btn btn-danger">Borrar</button>';
html += '';
html += '</div>';

$('#newRow').append(html);
});

// borrar registro
$(document).on('click', '#removeRow', function () {
$(this).closest('#inputFormRow').remove();
});

</script>