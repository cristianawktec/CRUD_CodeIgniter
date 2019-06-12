<div class="row">
    <div class="col-md-12">

        <div class="panel panel-primary" data-collapsed="0">

            <div class="panel-heading">
                <div class="panel-title">
                    <h3><?php echo "Adicionar Cliente"; ?></h3>
                </div>
            </div>

            <div class="panel-body">

                <form role="form" class="form-horizontal form-groups-bordered" action="<?php echo base_url(); ?>index.php?admin/cliente/create" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Nome'); ?></label>

                        <div class="col-sm-5">
                            <input type="text" name="name" class="form-control" id="field-1" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('E-mail'); ?></label>

                        <div class="col-sm-5">
                            <input type="email" name="email" class="form-control" id="field-1" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Senha'); ?></label>

                        <div class="col-sm-5">
                            <input type="password" name="password" class="form-control" id="field-1" >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Telefone'); ?></label>

                        <div class="col-sm-5">
                            <input type="text" name="phone" class="form-control" id="field-1" >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('Foto'); ?></label>

                        <div class="col-sm-5">

                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
                                    <img src="http://placehold.it/200x150" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                <div>
                                    <span class="btn btn-white btn-file">
                                        <span class="fileinput-new">Selecione uma imagem</span>
                                        <span class="fileinput-exists">Mudar</span>
                                        <input type="file" name="image" accept="image/*">
                                    </span>
                                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remover</a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-3 control-label col-sm-offset-2">
                        <input type="submit" class="btn btn-success" value="Salvar">
                    </div>
                </form>

            </div>

        </div>

    </div>
</div>