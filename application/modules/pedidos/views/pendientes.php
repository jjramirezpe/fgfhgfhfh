<?php $this->load->view('layout/header');?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<h3 class="text-danger">Pedido N° <?php echo $numero_pedido;?></h3>
		</div>

		<div class="col-md-6 text-right">
			<button type="button" class="btn btn-primary" onclick="ingresar_pedido()">Ingresar Pedido</button>
		</div>
	</div>

	<ul class="nav nav-tabs">
	  <li><a href="<?php echo base_url('pedidos')?>">Pedidos pendientes</a></li>
	  <li class="active"><a href="<?php echo base_url('pedidos/nuevo_pedido')?>">Nuevo pedido</a></li>
	</ul>
	
	<hr>

	<form id="form">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
				  <label for="sel1">Seleccione un cliente:</label>
				  <select class="form-control" name="id_cliente" id="id_cliente">
				  	<option></option>
				  	<?php foreach($cliente as $item):?>
				    <option value="<?php echo $item->id_cliente?>"><?php echo $item->nombre?></option>
					<?php endforeach?>
				  </select>
				</div>

				<div class="form-group">
				  <label for="sel1">Seleccione una referencia:</label>
				  <select class="form-control" name="ref" id="ref" onchange="buscar_articulo()">
				  	<option></option>
				    <?php foreach($articulos as $item):?>
				    <option value="<?php echo $item->referencia?>"><?php echo $item->referencia?></option>
					<?php endforeach?>
				  </select>
				</div>

			</div>

			<div class="col-md-6">
				<div class="form-group">
				  <label for="usr">Fecha de entrega:</label>
				  <input type="date" class="form-control" name="fecha_e" id="fecha_e">
				</div>

				<div class="form-group">
				  <label for="usr">Observaciones del pedido:</label>
				  <textarea class="form-control" rows="5" id="comment" name="comentario" id="commentario"></textarea>
				</div>
			</div>
		</div>

		 <div class="box-body table-responsive spacer-top">
              <table class="table table-hover" id="contenedor">
                <tr>
                  <th>Articulo</th>
                  <th>Descripcion</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  <th>Total</th>
                  <th>X</th>
                </tr>
              </table>
        </div>
		
	</form>

</div>

<script>
	function buscar_articulo()
	{
		
			var articulo = $("#ref").val();
			$.ajax({
				type: 'POST',
				url:'<?php echo base_url("ventas/factura/buscar_articulo")?>',
				dataType: 'json',
				data: {articulo: articulo},
				success: function(data)
				{
					if(data.status == true)
					{
			            var hidd = '<input type="hidden" name="id_articulo[]" value="'+data.result.id_articulo+'">'
									var ref = '<tr id="fr"><td><input class="form-control" id="ref" name="referencia[]" type="text" value="'+data.result.referencia+'" readonly /></td>';
									var descrip ='<td><input class="form-control" id="descrip" name="descrip[]" type="text" value="'+data.result.descripcion+'" readonly /></td>';
									var cant ='<td><input class="form-control cantidad"  name="cantidad[]" id="cantidad"  type="text" value="0" onkeyup="Calcular(this)"/></td>';
									var precio = '<td><input class="form-control" id="precio"  name="precio[]" type="text" value="'+data.result.precio+'" onkeyup="Calcular(this)" /></td>';
			            			var total = '<td><input class="form-control total " id="total" name="total[]" type="text"   readonly /></td>';
									var deleteItem = '<td><a class="delete" href="javascript:void(0)">X</a></td></tr>';
									$("#contenedor").append(ref+descrip+cant+precio+total+hidd+deleteItem);
					}
					else
					{
						alertify.alert(data.result);
					}
				}
			});
	}


	function ingresar_pedido()
	{
		$.ajax({
			url: '<?php echo base_url('pedidos/ingresar_pedido')?>',
			type: "POST",
       		data: $('#form').serialize(),
        	dataType: "JSON",
       		success: function(data)
        	{
        		if(data.status == true)
        		{
        			alertify.alert(data.msj,location.reload());
        		}
        		else
        		{
        			alertify.alert(data.msj);
        		}
        	}
			});
	}


	/*calcular precio*/
	function Calcular(ele)
	{
		var total = 0;
  		var tr = ele.parentNode.parentNode;

  		$(tr).each(function(){
  			total = $(this).find("td:eq(2) > input").val() * $(this).find("td:eq(3) > input").val()
			total = total.toFixed();
			$(this).find("td:eq(4) > input").val(total)
  		})
	}


/*eliminar un campo*/
$("body").on("click", ".delete", function (e) {
	$(this).parents("tr").remove();
	$("#ref").val('');
});


</script>

<?php $this->load->view('layout/footer');?>


