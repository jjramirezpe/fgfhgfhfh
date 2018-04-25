<?php $this->load->view('layout/header');
$url = $this->uri->segment(2);
?>


<div class="container-fluid">
	
<ul  class="nav nav-tabs">
  <?php if($url == 'pagar_gasto'): ?>
  <li><a href="<?php echo base_url('banco')?>">Transacciones</a></li>
  <li class="active disabled" class=""><a href="<?php echo base_url('banco/pagar_gasto')?>">Pagar gastos</a></li>
  <li><a href="<?php echo base_url('banco/cobrar_factura')?>">cobrar Factura</a></li>
  <li><a href="<?php echo base_url('banco/ver_cuentas')?>">Cuentas</a></li>
<?php endif;?>

</ul>

<h3 class="spacer-top text-danger">Transacción para el pago de un gasto</h3>

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
	      <label class="control-label col-sm-3" for="email">Seleccione una gasto  para el pago:</label>
	      <div class="col-sm-4">
	        	<select class="form-control" id="factura" name="factura" onchange="buscar_factura()">
	        		<option></option>
	        		<?php foreach($facturas as $items):?>
						<option value="<?php echo $items->id_gasto?>"><?php echo $items->factura?></option>
	        		<?php endforeach?>
	        	</select>
	      </div>
	    </div>

	    <div class="box-body table-responsive spacer-top">
              <table class="table table-hover" id="contenedor">
             	<tr>
             		<th>Numero</th>
             		<th>cliente</th>
             		<th>Descripción</th>
             		<th>fecha gasto</th>
             		<th>Valor factura</th>
             		<th>accion</th>
             	</tr>
              </table>
        </div>
	
  </div>
</form>
</div>


<script>
	
function buscar_factura()
{
	var factura = ($('#factura').val());
	$.ajax({
				type: 'POST',
				url:'<?php echo base_url("banco/buscar_gasto")?>',
				dataType: 'json',
				data: {factura: factura},
				success: function(data)
				{
				
					var numero = '<tr><td>'+data.result.factura+'</td>';
					var cliente = '<td>'+data.result.nombre+'</td>'
					var fecha = '<td>'+data.result.descripcion+'</td>'
					var vencimiento = '<td>'+data.result.fecha+'</td>'
					var valor = '<td>'+data.result.valor+'</td>'
					var input = '<td><button type="button" class="btn btn-success" onclick="pagar_factura()">Ingresar pago</button></td></tr>'


					/*valores para la transaccion*/
					var hidd = '<input type="hidden" value="'+data.result.id_gasto+'" name="id"/><input type="hidden" value="'+data.result.valor+'" name="tot"/><input type="hidden" value="'+data.result.factura+'" name="num"/><input type="hidden" value="'+data.result.nombre+'" name="proveedor"/>'


					$("#contenedor").append(numero+cliente+fecha+vencimiento+valor+hidd+input)

					/*eliminar la fila anterior*/
					if($("#contenedor tr").length > 2)
					{
						$("tr").eq(1).remove()
					}
				}

			});

}

function pagar_factura()
{
	$.ajax({
		 url : '<?php echo base_url("banco/pago_gasto")?>',
         type: "POST",
         data: $('#form').serialize(),
         dataType: "JSON",
         success: function(data)
         {
         	if(data.status == true)
         	{
         		alertify.alert(data.msj);
         		location.reload();
         	}
         	else
         	{
         		alertify.alert(data.msj);
         	}
         }
	})
}



</script>

</div>

<?php $this->load->view('layout/footer');?>