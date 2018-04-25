<?php $this->load->view('layout/header');?>
<div class="container-fluid">
	<h4>Facturas</h4>
	<hr>
	<ul class="nav nav-tabs">
	  <li><a href="<?php echo base_url('ventas');?>">Todas</a></li>
	  <li><a href="<?php echo base_url('ventas/pagadas');?>">Pagadas</a></li>
	  <li><a href="<?php echo base_url('ventas/vencidas');?>">Vencidas</a></li>
	  <li class="active"><a href="<?php echo base_url('ventas/vigentes');?>">Vigentes</a></li>
	</ul>
	<a href="<?php echo base_url('ventas/nueva_factura');?>" class="btn btn-danger spacer-top">Nueva Factura</a>
</div>
</div>

<?php $this->load->view('layout/footer');?>