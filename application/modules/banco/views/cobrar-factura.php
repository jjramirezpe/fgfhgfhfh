<?php $this->load->view('layout/header');
$url = $this->uri->segment(2);
//print_r($cuenta);
?>


<div class="container-fluid">
	
<ul  class="nav nav-tabs">
  <?php if($url == 'cobrar_factura'): ?>
  <li><a href="<?php echo base_url('banco')?>">Transacciones</a></li>
  <li class=""><a href="<?php echo base_url('banco/pagar_gasto')?>">Pagar gastos</a></li>
  <li class="active"><a href="<?php echo base_url('banco/cobrar_factura')?>">cobrar Factura</a></li>
  <li><a href="<?php echo base_url('banco/ver_cuentas')?>">Cuentas</a></li>
<?php endif;?>

</ul>

<h3 class="spacer-top text-success">Transacción para el cobro de una factura</h3>

<div class="panel panel-default spacer-top">
	<form class="form-horizontal" id="form">
  <div class="panel-body">
  	
		<div class="form-group">
	      <label class="control-label col-sm-3" for="email">Seleccione una cuenta para la transacción:</label>
	      <div class="col-sm-4">
	        	<select class="form-control" name="cuenta" id="cuenta">
	        		<?php foreach($cuentas as $items):?>
						<option value="<?php echo $items->id_cuenta?>"><?php echo $items->nombre?></option>
	        		<?php endforeach?>
	        	</select>
	      </div>
	    </div>

	    <div class="form-group">
	      <label class="control-label col-sm-3" for="email">Seleccione una Factura para el cobro:</label>
	      <div class="col-sm-4">
	        	<select class="form-control" id="factura" name="factura" onchange="buscar_factura()">
	        		<option></option>
	        		<?php foreach($facturas as $items):?>
						<option value="<?php echo $items->id_factura?>"><?php echo $items->numero_factura?></option>
	        		<?php endforeach?>
	        	</select>
	      </div>
	    </div>

	    <div class="box-body table-responsive spacer-top">
              <table class="table table-hover" id="contenedor">
             	<tr>
             		<th>Numero</th>
             		<th>cliente</th>
             		<th>Fecha factura</th>
             		<th>fecha vencimiento</th>
             		<th>Valor factura</th>
             		<th>accion</th>
             	</tr>
              </table>
        </div>
	
  </div>
</form>
</div>


</div>

<script>
	
function buscar_factura()
{
	var factura = ($('#factura').val());
	$.ajax({
				type: 'POST',
				url:'<?php echo base_url("banco/buscar_factura")?>',
				dataType: 'json',
				data: {factura: factura},
				success: function(data)
				{
				
					var numero = '<tr><td>'+data.result.numero_factura+'</td>';
					var cliente = '<td>'+data.result.nombre+'</td>'
					var fecha = '<td>'+data.result.fecha_factura+'</td>'
					var vencimiento = '<td>'+data.result.fecha_vencimiento+'</td>'
					var valor = '<td>'+data.result.total+'</td>'
					var input = '<td><button type="button" class="btn btn-success" onclick="cobrar_factura()">Ingresar pago</button></td></tr>'


					/*valores para la transaccion*/
					var hidd = '<input type="hidden" value="'+data.result.id_factura+'" name="id"/><input type="hidden" value="'+data.result.total+'" name="tot"/><input type="hidden" value="'+data.result.numero_factura+'" name="num"/>'


					$("#contenedor").append(numero+cliente+fecha+vencimiento+valor+hidd+input)

					/*eliminar la fila anterior*/
					if($("#contenedor tr").length > 2)
					{
						$("tr").eq(1).remove()
					}
				}

			});

}

function cobrar_factura()
{
	$.ajax({
		 url : '<?php echo base_url("banco/pago_factura")?>',
         type: "POST",
         data: $('#form').serialize(),
         dataType: "JSON",
         success: function(data)
         {
         	if(data.status == true)
         	{
         		alertify.alert(data.msj,function(){location.reload();});
         	}
         	else
         	{
         		alertify.alert(data.msj);
         	}
         }
	})
}



</script>

<?php $this->load->view('layout/footer');?>