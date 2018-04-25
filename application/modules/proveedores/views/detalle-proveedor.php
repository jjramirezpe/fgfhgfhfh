<?php $this->load->view('layout/header');?>

<div class="container-fluid">
	
	<div class="row">
		<div class="col-md-5">
			<h3><?php echo $proveedor->nombre;?></h3>
		</div>
		<div class="col-md-7 text-right">
			<div class="btn-group">
			  <button type="button" class="btn btn-default">Estado de cuenta</button>
			  <button type="button" class="btn btn-primary">Ingresar Factura</button>
			</div>
		</div>
	</div>
	<hr>

	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  		<h4>Contacto</h4>
			  	</div>
			  <div class="panel-body">
			  	<div class="row">
			  		<div class="col-md-6">
				  		<p><?php echo $proveedor->direccion;?><br><?php echo $proveedor->ciudad;?></p>
				  		
				  		<p>Email <a href="mailto:<?php echo $proveedor->email;?>"><?php echo $proveedor->email;?></a></p>
				  		<p>Télefono <a href="callto:<?php echo $proveedor->telefono;?>" target="_blank"><?php echo $proveedor->telefono;?></a> </p>
				  		<p>NIT <?php echo $proveedor->nit;?></p>
			  		</div>

			  		<div class="col-md-6 text-right" >
			  			
			  		</div>
			  	</div>
			  		
			  </div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
			  		<h4>Notas</h4>
			  	</div>
			  <div class="panel-body">
			  	<?php 
			  		foreach($notas as $nota)
			  		{
			  			echo '<p>'.$nota->nota.'</p>';
			  		} 
			  	?>
			  	<p class="text-right"><a href="javascript:void(0)" data-toggle="modal" data-target="#modal_note">Crear una nota</a></p>
			  </div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading x">
			  		<h4>Facturas</h4>
			  	</div>

			  <div class="panel-body">
			  	<div class="table-responsive">
			  		<table class="table table-hover">
					    <thead>
					      <tr>
					        <th>Estado</th>
					        <th>N°</th>
					        <th>Fecha</th>
					        <th>Vencimiento</th>
					        <th>Importe</th>
					      </tr>
					    </thead>
					    <tbody>
					      <tr>
					        <td></td>
					        <td></td>
					        <td></td>
					        <td></td>
					        <td></td>
					      </tr>
					    </tbody>
					  </table>
					  Pendiente por realizar
			  	</div>
			  </div>
			</div>
		</div>
	</div>
</div>




<div id="modal_note" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nueva nota para <?php echo $proveedor->nombre;?></h4>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo base_url('proveedores/add_note/').$proveedor->id_proveedor;?>"">
		  <div class="form-group">
			  <textarea class="form-control" rows="5" name="nota" id="nota" placeholder="Escriba la nota..."></textarea>
			  <input type="hidden" name="id" id="id">
		  </div>
		  
		
      </div>
      <div class="modal-footer">
      	<button type="submit" class="btn btn-default">Crear Nota</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>


<?php $this->load->view('layout/footer');?>