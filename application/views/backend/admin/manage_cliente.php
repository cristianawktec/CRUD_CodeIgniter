
<button onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/add_clientes/');" 
    class="btn btn-primary pull-right">
        <?php echo "Adicionar Cliente"; ?>
</button>
<div style="clear:both;"></div>
<br>
<table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
        <tr>
            <th><?php echo get_phrase('Foto');?></th>
            <th><?php echo get_phrase('Nome');?></th>
            <th><?php echo get_phrase('E-mail');?></th>
            <th><?php echo get_phrase('Telefone');?></th>
            <th><?php echo get_phrase('Opções');?></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($cliente_info as $row) { ?>   
            <tr>
                <td><img src="<?php echo $this->crud_model->get_image_url('cliente' , $row['cliente_id']);?>" class="img-circle" width="40px" height="40px"></td>
                <td><?php echo $row['name']?></td>
                <td><?php echo $row['email']?></td>
                <td><?php echo $row['phone']?></td>
                <td>
                    <a  onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/edit_cliente/<?php echo $row['cliente_id']?>');" 
                        class="btn btn-default btn-sm btn-icon icon-left">
                            <i class="entypo-pencil"></i>
                            Editar
                    </a>
                    <a href="<?php echo base_url();?>index.php?admin/cliente/delete/<?php echo $row['cliente_id']?>" 
                        class="btn btn-danger btn-sm btn-icon icon-left" onclick="return checkDelete();">
                            <i class="entypo-cancel"></i>
                            Deletar
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    jQuery(window).load(function ()
    {
        var $ = jQuery;

        $("#table-2").dataTable({
            "sPaginationType": "bootstrap",
            "sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>"
        });

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });

        // Highlighted rows
        $("#table-2 tbody input[type=checkbox]").each(function (i, el)
        {
            var $this = $(el),
                    $p = $this.closest('tr');

            $(el).on('change', function ()
            {
                var is_checked = $this.is(':checked');

                $p[is_checked ? 'addClass' : 'removeClass']('highlight');
            });
        });

        // Replace Checboxes
        $(".pagination a").click(function (ev)
        {
            replaceCheckboxes();
        });
    });
</script>