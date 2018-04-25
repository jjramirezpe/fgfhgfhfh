<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>invoice tools</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap-3.3.7/css/bootstrap.css')?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/datatables/css/jquery.dataTables.min.css')?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.min.css')?>">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css')?>">
	
	<script  src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js') ?>"></script>
	<script  src="<?php echo base_url('assets/jquery/jquery.number.min.js') ?>"></script>
	<script  src="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/bootstrap-3.3.7/js/bootstrap.js')?>"></script>
	<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
	<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
	<!--alertyfi-->
	<script  src="<?php echo base_url('assets/alertify/lib/alertify.js') ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/alertify/themes/alertify.core.css')?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/alertify/themes/alertify.default.css')?>">


</head>
<body>

<nav class="navbar navbar-default">
  	<div class="container-fluid">
  		<div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span> 
	      </button>
	    </div>

	    <div class="collapse navbar-collapse" id="myNavbar">

	    <ul class="nav navbar-nav">
	    <?php if ($this->uri->segment(1)=='inicio'): ?>
	      <li class="text-center active"><a href="<?php echo base_url('inicio');?>"><i class="glyphicon glyphicon-signal gly-2x gly-color-red"></i><br>Inicio</a></li>
	  	<?php else:?>
	  		<li class="text-center"><a href="<?php echo base_url('inicio');?>"><i class="glyphicon glyphicon-signal gly-2x gly-color-red"></i><br>Inicio</a></li>	
	    <?php endif; ?>

	    <?php if ($this->uri->segment(1)=='clientes' or $this->uri->segment(1)=='proveedores' or $this->uri->segment(1)=='usuarios'): ?>
			<li class="text-center active"><a href="<?php echo base_url('clientes');?>"><i class="glyphicon glyphicon-user gly-2x gly-color-blue"></i><br>Contactos</a></li>
		<?php else: ?>
			<li class="text-center"><a href="<?php echo base_url('clientes');?>"><i class="glyphicon glyphicon-user gly-2x gly-color-blue"></i><br>Contactos</a></li>
		<?php endif; ?>

		<?php if ($this->uri->segment(1)=='inventario'): ?>
			<li class="text-center active"><a href="<?php echo base_url('inventario');?>"><i class="glyphicon glyphicon-list-alt gly-2x gly-color-yellow"></i><br>Productos</a></li>
		<?php else: ?>
			<li class="text-center"><a href="<?php echo base_url('inventario');?>"><i class="glyphicon glyphicon-list-alt gly-2x gly-color-yellow"></i><br>Productos</a></li>
		<?php endif; ?>


	      
	      <?php if ($this->uri->segment(1)=='ventas'): ?>
	   	     <li class="text-center active"><a href="<?php echo base_url('ventas');?>"><i class="glyphicon glyphicon-shopping-cart gly-2x gly-color-red"></i><br>Ventas</a></li>
	   	  <?php else: ?>
	   	     <li class="text-center"><a href="<?php echo base_url('ventas');?>"><i class="glyphicon glyphicon-shopping-cart gly-2x gly-color-red"></i><br>Ventas</a></li>
	   	  <?php endif; ?>


	   	  <?php if ($this->uri->segment(1)=='gastos'): ?>
	   	     <li class="text-center active"><a href="<?php echo base_url('gastos');?>"><i class="glyphicon glyphicon-paste gly-2x gly-color-brown"></i><br>gastos</a></li>
	   	  <?php else: ?>
	   	     <li class="text-center"><a href="<?php echo base_url('gastos');?>"><i class="glyphicon glyphicon-paste gly-2x gly-color-brown"></i><br>gastos</a></li>
	   	  <?php endif; ?>


	   	  <?php if ($this->uri->segment(1)=='banco'): ?>
	   	     <li class="text-center active"><a href="<?php echo base_url('banco');?>"><i class="glyphicon glyphicon-piggy-bank gly-2x gly-color-blue"></i><br>Banco</a></li>
	   	  <?php else: ?>
	   	     <li class="text-center"><a href="<?php echo base_url('banco');?>"><i class="glyphicon glyphicon-piggy-bank gly-2x gly-color-blue"></i><br>Banco</a></li>
	   	  <?php endif; ?>


	   	  <?php if ($this->uri->segment(1)=='pedidos'): ?>
	   	     <li class="text-center active"><a href="<?php echo base_url('pedidos');?>"><i class="glyphicon glyphicon-save-file gly-2x gly-color-yellow"></i><br>pedidos</a></li>
	   	  <?php else: ?>
	   	     <li class="text-center"><a href="<?php echo base_url('pedidos');?>"><i class="glyphicon glyphicon-save-file gly-2x gly-color-yellow"></i><br>Pedidos</a></li>
	   	  <?php endif; ?>


	   	  

	   	  <!--<li class="text-center"><a href="<?php base_url();?>"><i class="glyphicon glyphicon-level-up gly-2x gly-color-brown"></i><br>Nomina</a></li>-->
	   	  <!--<li class="text-center"><a href="<?php base_url();?>"><i class="glyphicon glyphicon-modal-window gly-2x gly-color-red"></i><br>Ficha</a></li>-->
	   	  <li class="text-center"><a href="<?php base_url();?>"><i class="glyphicon glyphicon-tasks gly-2x gly-color-blue"></i><br>Reportes</a></li>
	   	  <li class="text-center"><a href="<?php base_url();?>"><i class="glyphicon glyphicon-hdd gly-2x gly-color-green"></i><br>Respaldos</a></li>
	   	  
	    </ul>

	    <ul class="nav navbar-nav navbar-right">
	      <li class="dropdown text-center">
	        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="glyphicon glyphicon-cog gly-2x gly-color-red"></i><br>
	        <span class="caret"></span></a>
	        <ul class="dropdown-menu">
	        	<?php if($this->uri->segment(1)=='configuracion'):?>
	          		<li><a class="active" href="<?php echo base_url('configuracion')?>">Configuración</a></li>
	      		<?php else :?>
					<li><a class="active" href="<?php echo base_url('configuracion')?>">Configuración</a></li>
	      		<?php endif?>
	          <li class="divider"></li>
	          <li><a href="#">Respaldos</a></li>
	          <li class="divider"></li>
	          <li><a href="#">Salir</a></li>
	        </ul>
	      </li>
	      <!--<a href="#" data-toggle="popover" title="Popover Header" data-placement="bottom" data-content="Some content inside the popover">Toggle popover</a>-->
	    </ul>
		</div>
  </div>
</nav>


