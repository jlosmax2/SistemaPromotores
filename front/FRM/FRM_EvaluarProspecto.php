<body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-register" role="form" id="register-form" enctype="multipart/form-data">

        <div class="container" style=" margin-top: 1%;">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-control" style="margin-top: 1%;">
                    <h4>Evaluar Prospecto</h4>
                    
                        <div class="form-control" style="margin-top: 1%;">
                            <div class="row">
                                <div class="col-md-9">
                                    Datos del Prospecto

                                </div>
                                <?php
                                    $IdProspecto=$_GET['P'];
                                    $user_obj = new Cl_User();
                                    echo $data = $user_obj->DAtosGeneralesEvaluacion($IdProspecto);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                        <div class="d-grid gap-2" style="margin-top: 3%;">

                          <button class="btn btn-success" type="submit" name="Autorizar" value="<?php echo $IdProspecto.'_2';?>">Autorizar</button>

                          <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal<?php echo $IdProspecto;?>">
                              Rechazar
                            </button>

                        </div>
                </div>
            </div>
           
         </div><!-- Div del contenedor -->
    </form>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-register" role="form" id="usrform" enctype="multipart/form-data">
     <!-- Modal -->
            <div class="modal fade" id="Modal<?php echo $IdProspecto;?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Observaciones del rechazo</h5>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                  </div>
                  <div class="modal-body">
                        <div class="form-floating">
                          <textarea class="form-control" name="observacion" id="floatingTextarea2" style="height: 100px" form="usrform" required></textarea>
                          <label for="floatingTextarea2">Observación</label>
                        </div>
                    </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="Autorizar" value="<?php echo $IdProspecto.'_3';?>" class="btn btn-danger">Rechazar</button>
                  </div>
                </div>
              </div>
            </div>
        </form>
</body>