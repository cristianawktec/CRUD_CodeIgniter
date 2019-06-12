<?php
$single_cliente_info = $this->db->get_where('clientes', array('cliente_id' => $param2))->result_array();
foreach ($single_cliente_info as $row) {
?>
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-heading">
                    <div class="panel-title">
                        <h3><?php echo "Editar Cliente"; ?></h3>
                    </div>
                </div>

                <div class="panel-body">

                    <form role="form" class="form-horizontal form-groups-bordered" action="<?php echo base_url(); ?>index.php?admin/cliente/update/<?php echo $row['cliente_id']; ?>" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Nome'); ?></label>

                            <div class="col-sm-5">
                                <input type="text" name="name" class="form-control" id="field-1" value="<?php echo $row['name']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('E-mail'); ?></label>

                            <div class="col-sm-5">
                                <input type="email" name="email" class="form-control" id="field-1" value="<?php echo $row['email']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Telefone'); ?></label>

                            <div class="col-sm-5">
                                <input type="text" name="phone" class="form-control" id="field-1" value="<?php echo $row['phone']; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo get_phrase('Foto'); ?></label>

                            <div class="col-sm-5">

                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
                                        <img src="<?php echo $this->crud_model->get_image_url('cliente' , $row['cliente_id']);?>" alt="...">
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                    <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new">Selecione a imagem</span>
                                            <span class="fileinput-exists">Mudar</span>
                                            <input type="file" name="image" accept="image/*">
                                        </span>
                                        <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remover</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-sm-3 control-label col-sm-offset-2">
                            <input type="submit" class="btn btn-success" value="Atualizar">
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>
<?php } ?>