<?php $this->load->view('layout/header');?>

<div class="container-fluid">
	
	<div class="row">
		<div class="col-md-5">
			<h3><?php echo $cliente->nombre;?></h3>
		</div>
		<div class="col-md-7 text-right">
			<div class="btn-group">
			  <a class="btn btn-primary" href="<?php echo base_url('ventas/factura/estado_cuenta')?>/<?php echo $cliente->id_cliente;?>"><span class="glyphicon glyphicon-folder-open"></span> &nbsp;&nbsp;Estado de Cuenta</a>
			</div>
		</div>
	</div>
	<ul class="breadcrumb">
			  <li><a href="<?php echo base_url('login')?>">Inicio</a></li>
			  <li><a href="<?php echo base_url('clientes')?>">Clientes</a></li>
			  <li>Detalle Usuario</li>
			</ul>
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
				  		<p><?php echo $cliente->direccion;?><br><?php echo $cliente->ciudad;?></p>
				  		
				  		<p>Email <a href="mailto:<?php echo $cliente->email;?>"><?php echo $cliente->email;?></a></p>
				  		<p>Télefono <a href="callto:<?php echo $cliente->telefono;?>" target="_blank"><?php echo $cliente->telefono;?></a> </p>
				  		<p>NIT <?php echo $cliente->nit;?></p>
			  		</div>

			  		<div class="col-md-6 text-right" >
			  			<p>Condiciones de pago  <?php echo $cliente->condiciones;?> dias</p>
			  			<p class="alert-success">Cupo Total  $<?php echo number_format($cliente->monto, '0','.', '.');?></p>
			  			<p class="alert-danger">Deuda $<?php echo number_format($balance->total, '0','.', '.');?></p>
			  			<p class="alert-warning">Cupo Disponible  $
			  				<?php
			  					$saldo = $cliente->monto - $balance->total;
			  				 	echo number_format($saldo, '0','.', '.');
			  				?>
			  			</p>
			  			<p>&nbsp;</p>
			  			<p class="text-info"><strong>Total Facturado $<?php echo number_format($total_facturado->total, '0','.', '.');?></strong> </p>
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

	<!--<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading x">
			  		<h4>Facturas</h4>
			  	</div>

			  <div class="panel-body">
			  	<div class="table-responsive spacer-top">
   <table id="table" class="table table-hover" width="100%">
                <thead>
                <tr>
                  <th>Estado</th>
                  <th>N°</th>
                  <th>Fecha</th>
                  <th>Vencimiento</th>
                  <th>Total</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
                
              </table>

</div>
			  </div>-->
	

	<div class="panel-group" id="accordion">
 
  <div class="panel panel-default panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="vigentes" data-toggle="collapse" data-parent="#accordion" href="#vigentes">
        Facturas Vigentes <span class="glyphicon glyphicon-triangle-bottom"></span></a>
      </h4>
    </div>
    <div id="vigentes" class="panel-collapse collapse">
      <div class="panel-body">
      	<div class="table-responsive">
   			<table id="table" class="table table-hover" width="100%">
                <thead>
                <tr>
                  <th>N°</th>
                  <th>Fecha</th>
                  <th>Vencimiento</th>
                  <th>Total</th>
                </tr>
                </thead>
                <tbody>
					<?php foreach($facturas as $items) : ?>
						<?php if(strtotime(date('m/d/Y')) < strtotime($items->fecha_vencimiento)) : ?>
						<tr>
							<td><a href="<?php echo base_url('ventas/mostrar_factura/detalle').'/'.$items->id_factura?>"><?php echo $items->numero_factura;?></a></td>
							<td><?php echo $items->fecha_factura;?></td>
							<td><?php echo $items->fecha_vencimiento;?></td>
							<td><?php echo number_format($items->total, 0, '.', ',');?></td>
						</tr>
					<?php endif ?>
					<?php endforeach ?>
                </tbody>
                
             </table>

</div>
      </div>
    </div>
  </div>
  <div class="panel panel-default panel-danger">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#vencidas">
        Facturas Vencidas <span class="glyphicon glyphicon-triangle-bottom"></span></a>
      </h4>
    </div>
    <div id="vencidas" class="panel-collapse collapse">
      <div class="panel-body">
      	<div class="table-responsive">
   			<table id="table" class="table table-hover" width="100%">
                <thead>
                <tr>
                  <th>N°</th>
                  <th>Fecha</th>
                  <th>Vencimiento</th>
                  <th>Total</th>
                </tr>
                </thead>
                <tbody>
					<?php foreach($facturas as $items) : ?>
						<?php if(strtotime(date('m/d/Y')) > strtotime($items->fecha_vencimiento)) : ?>
						<tr>
							<td><a href="<?php echo base_url('ventas/mostrar_factura/detalle').'/'.$items->id_factura?>"><?php echo $items->numero_factura;?></a></td>
							<td><?php echo $items->fecha_factura;?></td>
							<td class="text-danger"><?php echo $items->fecha_vencimiento;?></td>
							<td><?php echo number_format($items->total, 0, '.', ',');?></td>
						</tr>
					<?php endif ?>
					<?php endforeach ?>
                </tbody>
                </tbody>
                
             </table>

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
        <h4 class="modal-title">Nueva nota para <?php echo $cliente->nombre;?></h4>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo base_url('clientes/add_note/').$cliente->id_cliente;?>"">
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
<script>
  
  /*funciones javascript para el CRUD del cliente */
function EstadoCuenta()
{
	var id = <?php echo $this->uri->segment('3')?>;
	
	$.ajax({
				type: 'POST',
				url:'<?php echo base_url("clientes/estado_cuenta")?>',
				dataType: 'json',
				data: {id: id},
				success: function(data)
				{
					if(data.status == true)
					{
						alertify.alert(data.id)
					}
				}
			});
}
</script>


<?php $this->load->view('layout/footer');?>