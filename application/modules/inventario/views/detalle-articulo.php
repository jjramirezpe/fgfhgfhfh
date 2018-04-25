<?php $this->load->view('layout/header');?>

<div class="container-fluid">
	

	<h4>Detalles para el articulo <span class="text-danger"><?php echo $articulo->referencia;?></span></h4>
	<hr>
	<ul class="breadcrumb">
	  <li><a href="<?php echo base_url('login')?>">Inicio</a></li>
	  <li><a href="<?php echo base_url('inventario')?>">Productos</a></li>
	  <li>Detalle</li>
	</ul>

	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-body">
			  	<p class="text-left">Referencia <?php echo $articulo->referencia;?></p>
			  	<p class="text-left"><?php echo $articulo->descripcion;?></p>
			  	<p class="text-left">Cantidad <span class="badge"><?php echo $articulo->cantidad;?> Unidades</span> <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal">Editar Cantidad</button></p>
			  	<p class="text-right">Precio de venta <span class="badge">$<?php echo number_format($articulo->precio,0,'.','.');?></span></p>
				<p class="text-right">Costo producto <span class="badge">$<?php echo number_format($articulo->costo,0,'.','.');?></span></p>
			  </div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-body">
			  	<?php $total = $articulo->costo * $articulo->cantidad ?>
			  	<p>Valor total en inventario <span class="badge">$<?php echo number_format($total,0,'.','.');?></span></p>
			  	<p>Unidades vendidas del articulo</p>
			  	<?php $ganancia = $articulo->precio - $articulo->costo ;?>
			  	<p class="text-right">Ganacia/unidad <span class="badge">$<?php echo number_format($ganancia, 0, '.', '.');?></span></p>
			  	<?php $porcentaje = ($ganancia / $articulo->precio) * 100;?>
			  	<p class="text-right">% de ganancia/unidad <span class="badge"><?php echo round($porcentaje);?>%</span></p>
			  	<?php $ganancia_total = $ganancia * $articulo->cantidad;?>
			  	<p class="text-right">Ganacia total <span class="badge">$<?php echo number_format($ganancia_total, 0, '.', '.');?></span>&nbsp;<a href="#" data-toggle="tooltip" title="Ganancia total que se obtiene en todas las canidades actuales en el inventario para este articulo."><span class=" badge badge-bg">?</span></a></p>
			  </div>
		    </div>
		</div>
	</div>

	<h4>Historial de actualizaciones</h4>
	<hr>

	<div class="table-responsive">
		<table class="table table-hover" id="historial">
		    <thead>
		      <tr>
		        <th>Fecha</th>
		        <th>transacción</th>
		        <th>Cantidad</th>
		        <th>Observación</th>
		        <th>Responsable</th>
		      </tr>
		    </thead>
		    <tbody>
		    	<?php foreach($detalle as $detalles):?>
		      <tr>
		        <td><?php echo $detalles->fecha;?></td>
		        <td>
		        	<?php
			        	if($detalles->transaccion == 1)
			        	{
			        		echo  'Deficit';
			        	}

			        if($detalles->transaccion == 2)
			        	{
			        		echo  'Aumento';
			        	}
			        ?>
		        </td>
		        <td><?php echo $detalles->cantidad;?></td>
		        <td><?php echo $detalles->comentario;?></td>
		        <td><?php echo $detalles->usuario;?></td>
		      </tr>
		  <?php endforeach?>
		    </tbody>
	    </table>
	</div>


</div>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Editar Stock para el articulo <?php echo $articulo->referencia;?></h4>
      </div>
      <div class="modal-body">
        <form  action="<?php echo base_url('inventario/actualizar_stock')?>/<?php echo $articulo->id_articulo;?>" method="post">
        	<div class="form-group">
			    Transacción
				  <select class="form-control" name="transaccion">
				    <option value="1">Deficit</option>
				    <option value="2">Aumento</option>
				  </select>
			</div>

			<div class="form-group">
			   cantidad
			    <input type="number" class="form-control" name="cantidad">
			</div>

			<div class="form-group">
			   Observación
			    <textarea class="form-control" rows="5" id="comment" name="comentario"></textarea>
			</div>
      </div>
      <div class="modal-footer">
      	 <button type="submit" class="btn btn-default">Guardar cambios</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});

 table = $('#historial').DataTable({  

        //lenguaje en español
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ Articulos ",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Total registros  _TOTAL_ ",
            "sInfoEmpty":      "Mostrando articulos del 0 al 0 de un total de 0 articulos",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar Articulo:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
                }

    });

</script>

<?php $this->load->view('layout/footer');?>