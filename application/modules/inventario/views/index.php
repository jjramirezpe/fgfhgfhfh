<?php $this->load->view('layout/header');?>

<div class="container-fluid">
	<h4>Lista de Articulos de inventario</h4>
	<hr>

  <button onclick="add_person()"  type="button" class="btn btn-danger spacer-top spacer-bottom">Nuevo Articulo</button>
  <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Recargar</button>	

  <div class="msj"></div>
    <div class="table-responsive spacer-top">
  		 <table id="table" class="table table-hover" width="100%">
                <thead>
                <tr>
                  <th>N°</th>
                  <th>Referencia</th>
                  <th>Descripcion</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  <th>Costo</th>
                  <th>Acciones</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
                
         </table>

	</div>
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Nuevo producto</h4>
      </div>
      <form action="#" id="form">
      	<input type="hidden" value="" name="id_articulo"/>
      <div class="modal-body">
        <div class="row">

        	<div class="col-md-6">
        		<div class="form-group">
				    *Referencia
				    <input type="text" class="form-control" id="ref" name="referencia">
				    <span class="help-block"></span>
				</div>

				<div class="form-group">
				    *Descripcion
				    <textarea class="form-control" rows="4" name="descripcion"></textarea>
				    <span class="help-block"></span>
				</div>

				<div class="form-group" id="cantidad">
				    *Cantidad
				    <input type="number" class="form-control" id="" name="cantidad">
				    <span class="help-block"></span>
				</div>

        	</div>


        	<div class="col-md-6">
        		<div class="form-group">
				    *Precio Neto (sin IVA)
				    <input type="number" class="form-control" id="" name="precio">
				    <span class="help-block"></span>
				</div>


				<div class="form-group">
				    *Costo Producto
				    <input type="number" class="form-control" id="" name="costo">
				    <span class="help-block"></span>
				</div>
        	</div>

        </div>
      </div>


      <div class="modal-footer">
      	<button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
	</form>

  </div>
</div>


<script>
  
  /*funciones javascript para el CRUD del cliente */

var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({  

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('inventario/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

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


    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});



function add_person()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#myModal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Agregar Articulo'); // Set Title to Bootstrap modal title

    $('#nit').prop( "disabled", false );
}

function edit_person(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#ref').prop( "readonly", true );
    $("#cantidad").remove();

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('inventario/ajax_edit/')?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_articulo"]').val(data.id_articulo);
            $('[name="referencia"]').val(data.referencia);
            $('[name="descripcion"]').val(data.descripcion);
            //$('[name="cantidad"]').val(data.cantidad);
            $('[name="precio"]').val(data.precio);
            $('[name="costo"]').val(data.costo);
            $('#myModal').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Articulo'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('inventario/ajax_add')?>";
    } else {
        url = "<?php echo site_url('inventario/ajax_update')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#myModal').modal('hide');
                reload_table();

               alertify.success(data.msj);
            

            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

            //desaparece mensaje
            setTimeout(function() {
                $(".msj").fadeOut(4000)
            },1500);


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_person(id)
{
    if(confirm('Esta seguro de Eliminar este usuario?. No podra recuperarlo de nuevo!'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('inventario/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#myModal').modal('hide');
                reload_table();

                alertify.success(data.msj);

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }


        });

    }
}

</script>

<?php $this->load->view('layout/footer');?>