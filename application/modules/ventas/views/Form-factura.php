<?php $this->load->view('layout/header');?>
<div class="container-fluid">
	<form id="form">
	<ul class="breadcrumb">
	  <li><a href="<?php echo base_url('login')?>">Inicio</a></li>
	  <li><a href="<?php echo base_url('ventas')?>">ventas</a></li>
	  <li>Nueva Factura</li>
	</ul>

	<div class="row">
		<div class="col-md-4"><h3>Nueva Factura</h3></div>
		<div class="col-md-8 text-right">
			<button onclick="crearFactura()" type="button" class="btn btn-success">Crear Factura</button>
			<button type="button" class="btn btn-danger">Cancelar</button>
		</div>
	</div>
	<hr>

	<div class="row border-border">
		<div class="col-md-4">
			<h4>Cliente para la factura</h4>
			<div class="form-group spacer-top">
			  <input type="text" class="form-control" id="cliente" name="cliente" autocomplete="off" autofocus="true" placeholder="Ingrese el NIT del cliente">
			</div>
			<div class="well" id="data-cliente"></div>
			<div class="form-group">
					  Vendedor
					   <select class="form-control" id="vendedor" name="vendedor">
					   	<?php foreach($vendedor as $vendedores) : ?>
					    <option value="<?php echo $vendedores->id_usuario?>"><?php echo $vendedores->nombre?></option>
					    <?php endforeach ?>
					  </select>
			</div>
		</div>

		<div class="col-md-4 col-md-offset-4 text-right">
			<h4>Fecha</h4>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
					  Fecha
					  <input type="text" class="form-control" id="datepicker" name="fecha" value="">
					</div>
					<div class="form-group">
					  Condiciones de pago
					   <select class="form-control" id="condiciones" name="condiciones">
					    <option value="0">Contado</option>
					    <option value="30">30 Días</option>
					    <option value="60">60 Días</option>
					    <option value="1">Manual</option>
					  </select>
					</div>
				
			</div>
			<div class="col-md-6">
					<div class="form-group">
					  N° de factura
					  <input type="text" class="form-control" id="numero_factura" name="numero_factura" value="<?php echo $datos_factura->numero?>">
					</div>
					<div class="form-group">
					  Fecha de vencimiento
					  <input type="text" class="form-control" id="fechav" name="fechav">
					</div>
				</div>
		</div>
		<div class="form-group">
			  Notas para la factura
			  <textarea class="form-control" rows="5" id="comment" name="notas"></textarea>
			</div>
		<div class="form-group form-inline text-danger">
			<strong>Credito Actual</strong>
			<input type="text" name="monto" id="monto">
		</div>
	</div>
</div>


<div class="row border-border spacer-top">
	<div class="col-md-12">
		<h4>Articulos para la factura</h4>
		<div class="row">
                <div class="col-xs-4">
                 <input type="text" name="codigo" id="codigo" autocomplete="off" class="form-control"  autofocus="false" placeholder="Ingrese el codigo del producto...">
                </div>
                <div class="col-xs-4">
                  
                </div>
                <div class="col-xs-4">
                  
                </div>
        </div>

        <div class="box-body table-responsive spacer-top">
              <table class="table table-hover" id="contenedor">
                <tr>
                  <th>Articulo</th>
                  <th>Descripcion</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  <th>Subtotal</th>
                  <th>Tasa de impuesto</th>
                  <th>Impuesto</th>
                  <th>Total</th>
                  <th>X</th>
                </tr>
              </table>
            </div>

	</div>


</div>

<div class="row border-border spacer-top">
	<div class="row">
		<div class="col-md-6">
			<h4>Totales para la factura</h4>
		</div>
		
		<div class="col-md-6 text-right">
			<p>
				<div class="form-group   form-inline">
					<label for="subtotal">Sub total </label>
					 <input type="text" class="form-control" id="subtotales" name="subtotales" value="0.00">
				</div>
			</p>
			<p>
				<div class="form-group  form-inline">
					<label for="iva">IVA </label>
					<input type="text" class="form-control" id="ivas" name="ivas" value="0.00">
				</div>
			</p>
			<p>
				<div class="form-group  form-inline">
					<label for="total">Total </label>
					 <input type="text" class="form-control" id="totales" name="totales" value="0.00">
				</div>
			</p>
		</div>
	</div>
</div>

<div class="row border-border spacer-top">
	Terminos factura
	<div class="form-group">
		 Notas para la factura
		<textarea class="form-control" rows="5" id="comment" name="terminos">
			<?php echo $terminos->resolucion?>
		</textarea>
	</div>
</div>
<input type="hidden" name="id_cliente" id="id_cliente" value="">
</form>

</div>


<script>
	$(document).ready(function() {
		/*buscar los articulos para la factura*/
		$("#codigo").keyup(function(e){

			if(e.keyCode == 13)
			{
				var articulo = $("#codigo").val();
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
						var cant ='<td><input class="form-control cantidad"  name="cantidad[]"  type="text" value="0" onkeyup="Calcular(this);CalcularCant(this)"/></td>';
						var precio = '<td><input class="form-control" id="precio"  name="precio[]" type="text" value="'+data.result.precio+'" onkeyup="Calcular(this)" /></td>';
            var iva = '<td><input class="form-control" id="ref" name="tasa_impuesto[]" type="text" value="0" onkeyup="Calcular(this)"/></td>';
						var subtotal = '<td><input class="form-control subtotal_ln"  name="subtotal[]" type="text" value="'+data.result.precio+'"  readonly/></td>';
            var impuesto = '<td><input class="form-control iva_ln" id="descrip" name="impuesto[]" type="text" value="0" readonly /></td>';
            var total = '<td><input class="form-control total " id="descrip" name="total[]" type="text" value="'+data.result.precio+'" readonly /></td>';
						var deleteItem = '<td><a class="delete" href="javascript:void(0)">X</a></td></tr>';
						$("#contenedor").append(ref+descrip+cant+precio+subtotal+iva+impuesto+total+hidd+deleteItem);
						resetInput()
					}
					else
					{
						alertify.alert(data.result,function(e){resetInput()});
					}
				}
			});
			}

		});


		/*buscar los datos del cliente para la factura*/
		$("#cliente").keyup(function(){
			var nit = $("#cliente").val()
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url("ventas/factura/buscar_cliente")?>',
				dataType: 'json',
				data: {nit : nit},
				success: function(data){
					if(data.status == true)
					{	
						/*datos del cliente*/
						var saldo = data.result.monto - data.deuda.valor;
						var cliente = '<h4>'+ data.result.nombre +'</h4><p> Nit '+ data.result.nit +'<br>Dirección '+data.result.direccion +'<br>'+ data.result.ciudad +'<br>Tel '+ data.result.telefono +'</p>';
						$("#data-cliente").html(cliente);
						$("#condiciones").val(data.result.condiciones);
						$("#id_cliente").val(data.result.id_cliente);
						$("#monto").val(saldo);

						/*fechas inicial y vencimiento dela fatura segun condiciones de factura para el cliente*/
						$( function() {
						    $( "#datepicker" ).datepicker({dayNamesMin: [ "D0", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ]
							});

						     $( "#datepicker" ).datepicker( "setDate", "date" );
						 });


						$( function() {
						    $( "#fechav" ).datepicker({dayNamesMin: [ "D0", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ]
							});
							
							$( "#fechav" ).datepicker( "setDate", data.result.condiciones );
						});
					}
					else
					{
						$("#data-cliente").html('opps');
						$( function() {
						    $( "#datepicker" ).datepicker({dayNamesMin: [ "D0", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ]
							});
						     $( "#datepicker" ).datepicker( "setDate", "date" );
						 });

						
						$( function() {
						    $( "#fechav" ).datepicker({dayNamesMin: [ "D0", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ]
							});
						});
						$("#id_cliente").val("0");
						$("#monto").val("0");
					}
					
				}
			});

		});

	});



$( function() {
		$( "#datepicker" ).datepicker({dayNamesMin: [ "D0", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ]
		});

		$( "#datepicker" ).datepicker( "setDate", "date" );

});


$( function() {
		$( "#fechav" ).datepicker({dayNamesMin: [ "D0", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ]
		});
							
		$( "#fechav" ).datepicker( "setDate", "date" );
});


	//limpia el imput y pone foco
function resetInput(){
	$("#codigo").val('');
	$("#codigo").focus();
}

function resetCliente()
{
	$("#clente").val('');
	$("#clente").focus();
}

/*eliminar un campo*/
$("body").on("click", ".delete", function (e) {
	$(this).parents("tr").remove();

	 /*RECALCULA EL TOTAL DE LA FACTURA*/
	var subtotal_factura = 0;
    $('.subtotal_ln').each(
    	function(index,value)
    	{
    		subtotal_factura = subtotal_factura + eval($(this).val());
    	}
     )
    $('#subtotales').val(subtotal_factura)

    /*recalcula el iva de la factura*/
    var iva_factura = 0;
    $('.iva_ln').each(
    	function(index,value)
    	{
    		iva_factura = iva_factura + eval($(this).val());
    	}
     )
    $('#ivas').val(iva_factura);

    /*recalcula el total de toda la factura*/
     var subtotales = $('#subtotales').val();
     var ivas = $('#ivas').val();
     var totales = parseInt(subtotales) + parseInt(ivas);
     $('#totales').val(totales);
   
});

//calcula el subtotal el iva y el total de un item
function Calcular(ele)
{
  var subtotal = 0, iva = 0, total = 0;
  var tr = ele.parentNode.parentNode;
  
  $(tr).each(function(){

    subtotal = $(this).find("td:eq(3) > input").val() * $(this).find("td:eq(2) > input").val()
    iva = (subtotal * $(this).find("td:eq(5) > input").val())/100;
    total = subtotal + iva;

    subtotal = subtotal.toFixed();//elimina decimales
    iva = iva.toFixed();
    total = total.toFixed();

    
    $(this).find("td:eq(4) > input").val(subtotal)
    $(this).find("td:eq(6) > input").val(iva)
    $(this).find("td:eq(7) > input").val(total)

    /*calcula el subtotal final de toda la factura sin el iva*/
    var subtotal_factura = 0;
    $('.subtotal_ln').each(
    	function(index,value)
    	{
    		subtotal_factura = subtotal_factura + eval($(this).val());
    	}
     )
    $('#subtotales').val(subtotal_factura)

    /*calcula el iva total de toda la factura*/
    var iva_factura = 0;
    $('.iva_ln').each(
    	function(index,value)
    	{
    		iva_factura = iva_factura + eval($(this).val());
    	}
     )
    $('#ivas').val(iva_factura)

    /*calcula el total de toda la factura sumando el subtotal y el iva*/
     var subtotales = $('#subtotales').val();
     var ivas = $('#ivas').val();
     var totales = parseInt(subtotales) + parseInt(ivas);
     $('#totales').val(totales);

  });
}

/*funcion para comparar la cantidad del input y la cantidad del articulo en db*/
function CalcularCant(ele)
{
  
  var tr = ele.parentNode.parentNode;
  
  $(tr).each(function(){
  		var articulo = $(this).find("td:eq(0) > input").val();
  		var cantidad = parseInt($(this).find("td:eq(2) > input").val());
  		$.ajax({
				type: 'POST',
				url:'<?php echo base_url("ventas/factura/buscar_articulo")?>',
				dataType: 'json',
				data: {articulo: articulo},
				success: function(data)
				{
					var valueInput = parseInt(data.result.cantidad);
					if( cantidad > valueInput)
					{
						$(".cantidad").addClass('alert-danger');

						alertify.alert("solo dispone de " + data.result.cantidad + ' unidades para  la referencia '+ data.result.referencia +',  por favor ingrese una cantidad valida');
					}
					if(cantidad < data.result.cantidad)
					{
						$(".cantidad").removeClass('alert-danger');
					}
				}
			});
  });
}


/*dar formato a los numeros*/
$(document).ready(function(){
	$("#monto").number(true, 0);
	$("#subtotales").number(true, 0);
	$("#ivas").number(true, 0);
	$("#totales").number(true, 0);

});	


/*funcion para crear la factura*/
function crearFactura()
{
	$.ajax({
		url: '<?php echo base_url("ventas/factura/save_factura")?>',
		type: 'POST',
		data: $("#form").serialize(),
		dataType: 'JSON',
		success: function(data)
		{
			if(data.status == true)
			{
				alertify.alert(data.msj + data.id,function(e){

					window.location.href = "<?php echo base_url('ventas/mostrar_factura/detalle/')?>"+data.id+""
				});
			}
			else
			{
				alertify.alert(data.msj,function(e){$('#cliente').focus()});
			}
		},
		error: function (jqXHR, textStatus, errorThrown)
        {
            alert('error: no se puede crear la factura en este momento');
        }
	});
}




</script>

<?php $this->load->view('layout/footer');?>