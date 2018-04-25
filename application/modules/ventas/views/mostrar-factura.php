<?php $this->load->view('layout/header');?>
<?php $cupo = $datos_cliente->monto - $saldo_cliente->total;?>

<div class="container-fluid">
  <form id="form">
	<ul class="breadcrumb">
		<li><a href="<?php echo base_url('inicio');?>">Inicio</a></li>
		<li><a href="<?php echo base_url('ventas');?>">Ventas</a></li>
		<li>Detalle factura</li>
	</ul>
	<div class="panel panel-default">
  		<div class="panel-heading">
  			<div class="row">
  				<div class="col-md-6 text-danger"><strong>Factura N° <?php echo $datos_factura->numero_factura;?></strong></div>
  				<div class="col-lg-offset-4 col-md-2">
  					<div class="dropdown">
  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Opciones
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li><a target="_blank" href="<?php echo base_url('ventas/mostrar_factura/imprimir/').$datos_factura->id_factura;?>">Imprimir</a></li>
    <li><a href="#" onclick="editarFactura()">Editar</a></li>
  </ul>
</div>
  				</div>
  			</div>
  		</div>
  		<div class="panel-body">
  			<div class="row">
  				<div class="col-md-6 text-left">
  					<h3><a href="<?php echo base_url('clientes/detalle_usuario').'/'.$datos_factura->id_cliente;?>"><?php echo $datos_cliente->nombre;?></a>
  					</h3>
  					<p><?php echo $datos_cliente->direccion;?></p>
  					<p>Teléfono <?php echo $datos_cliente->telefono;?></p>
  				</div>
  				<div class="col-md-6 text-right">
              <h4>Fecha</h4>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            Fecha
            <input type="text" class="form-control" id="datepicker" name="fecha" value="<?php echo $datos_factura->fecha_factura ?>">
          </div>
          <div class="form-group">
            Condiciones de pago
             <select class="form-control" id="condiciones" name="condiciones">
              <?php if($datos_cliente->condiciones == '0' )
              {
                echo '<option value="0">Contado</option>
                      <option value="30">30 Días</option>
                      <option value="60">60 Días</option>
                      <option value="1">Manual</option>';
              }

              elseif($datos_cliente->condiciones == '30' )
              {
                echo '<option value="30">30 Días</option>
                      <option value="0">Contado</option>
                      <option value="60">60 Días</option>
                      <option value="1">Manual</option>';
              }

              elseif($datos_cliente->condiciones == '60' )
              {
                echo '<option value="60">60 Días</option>
                      <option value="0">contado</option>
                      <option value="30">60 Días</option>
                      <option value="1">Manual</option>';
              }

              else
              {
                echo '<option value="0">Contado</option>
                      <option value="30">30 Días</option>
                      <option value="30">60 Días</option>
                      <option value="1">Manual</option>';
              }
              ?>
            </select>
          </div>
        
      </div>
      <div class="col-md-6">
          <div class="form-group">
            Fecha de vencimiento
            <input type="text" class="form-control" id="fechav" name="fechav" value="<?php echo $datos_factura->fecha_vencimiento ?>">
          </div>
        </div>
    </div>
    <div class="form-group">
        Notas para la factura
        <textarea class="form-control" rows="5" id="comment" name="notas"><?php echo $datos_factura->notas ?></textarea>
      </div>
    <div class="form-group form-inline text-danger">
      <strong>Credito Actual</strong>
      <input type="text" name="monto" id="monto" value="<?php echo number_format($cupo,0,'.',',') ?>">
    </div>    
          </div>
  			</div>
  		</div>
	</div>



<div class="row border-border spacer-top">
  <div class="col-md-12">
    <h4>Articulos para la factura</h4>
    <div class="row">
                <div class="col-xs-4">
                 <!--<input type="text" name="codigo" id="codigo" autocomplete="off" class="form-control"  autofocus="false" placeholder="Ingrese el codigo del producto...">-->
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
                <?php foreach($articulos_factura as $items) : ?>
                  <tr>
                    <td>
                      <input class="form-control" id="ref" name="referencia[]" type="text" value="<?php echo $items->referencia ?>" readonly />
                    </td>
                    <td>
                      <input class="form-control" id="descrip" name="descrip[]" type="text" value="<?php echo $items->descripcion ?>" readonly />
                    </td>
                    <td>
                      <input class="form-control cantidad"  name="cantidad[]"  type="text" value="<?php echo $items->cantidad ?>" onkeyup="Calcular(this);CalcularCant(this)"/>
                    </td>
                    <td>
                      <input class="form-control" id="precio"  name="precio[]" type="text" value="<?php echo $items->precio ?>" onkeyup="Calcular(this)" />
                    </td>
                    <td>
                      <input class="form-control subtotal_ln"  name="subtotal[]" type="text" value="<?php echo $items->subtotal ?>"  readonly/>
                    </td>
                    <td>
                      <input class="form-control" id="ref" name="tasa_impuesto[]" type="text" value="<?php echo $items->tasa_impuesto ?>" onkeyup="Calcular(this)"/>
                    </td>
                    <td><input class="form-control iva_ln" id="descrip" name="impuesto[]" type="text" value="<?php echo $items->impuesto ?>" readonly /></td>
                    <td>
                      <input class="form-control total " id="descrip" name="total[]" type="text" value="<?php echo $items->total ?>" readonly />
                    </td>
                    <td>
                      <a class="delete" href="javascript:void(0)">X</a>
                    </td>
                  </tr>
                  <input type="hidden" name="id_articulo[]" value="<?php echo $items->id_articulo ?>">
                <?php endforeach ?>
                <input type="hidden" name="id_cliente" value="<?php echo $datos_cliente->id_cliente;?>">
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
           <input type="text" class="form-control" id="subtotales" name="subtotales" value="<?php echo number_format($datos_factura->subtotal, 0, '.', ',') ?>">
        </div>
      </p>
      <p>
        <div class="form-group  form-inline">
          <label for="iva">IVA </label>
          <input type="text" class="form-control" id="ivas" name="ivas" value="<?php echo number_format($datos_factura->iva,0,'.', ',') ?>">
        </div>
      </p>
      <p>
        <div class="form-group  form-inline">
          <label for="total">Total </label>
           <input type="text" class="form-control" id="totales" name="totales" value="<?php echo number_format($datos_factura->total,0,'.',',') ?>">
        </div>
      </p>
    </div>
  </div>
</div>

<div class="row border-border spacer-top">
  Terminos factura
  <div class="form-group">
    
    <textarea class="form-control" rows="5" id="comment" name="terminos">
      <?php echo $datos_factura->terminos ?>
    </textarea>
  </div>
</div>
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
            var total = '<td><input class="form-control total " id="descrip" name="total[]" type="text" value="'+data.result.precio+'" readonly/><a href="">Add</a></td>';
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



function editarFactura()
{
  $.ajax({
    url: '<?php echo base_url("ventas/factura/actualizar_factura").'/'.$datos_factura->id_factura;?>',
    type: 'POST',
    data: $("#form").serialize(),
    dataType: 'JSON',
    success: function(data)
    {
      if(data.status == true)
      {
        alertify.alert(data.msj,function(e){location.reload();});
      }
      else
      {
        alertify.alert(data.msj,function(e){$('#cliente').focus()});
      }
    },
    error: function (jqXHR, textStatus, errorThrown)
        {
            alert('error: no se puede editar la factura en este momento');
        }
  });
}

</script>

<?php $this->load->view('layout/footer');?>