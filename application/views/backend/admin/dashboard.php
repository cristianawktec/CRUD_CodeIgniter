

<div class="row">

    <div class="col-sm-3">
        <a href="<?php echo base_url(); ?>index.php?admin/cliente">
            <div class="tile-stats tile-white-red">
                <div class="icon"><i class="fa fa-user"></i></div>
                <div class="num" data-start="0" data-end="<?php echo $this->db->count_all('clientes'); ?>"
                     data-duration="1500" data-delay="0">0 &pound;</div>
                <h3><?php echo get_phrase('Clientes') ?></h3>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="<?php echo base_url(); ?>index.php?admin/system_settings">
            <div class="tile-stats tile-white-gray">
                <div class="icon"><i class="fa fa-cog"></i></div>
                <div class="num">&nbsp;</div>
                <h3><?php echo get_phrase('Configurações') ?></h3>
            </div>
        </a>
    </div>
</div>