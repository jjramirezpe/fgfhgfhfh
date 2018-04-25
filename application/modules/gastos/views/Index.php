<?php  
$this->load->view('layout/header'); 
$url = $this->uri->segment(1);
?>

<div class="container-fluid">
	

<button onclick="add_person()"  type="button" class="btn btn-danger spacer-top spacer-bottom">Nuevo Gasto</button>
  <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Recargar</button>

<div class="msj"></div>
    <div class="table-responsive spacer-top">
   		<table id="table" class="table table-hover" width="100%">
                <thead>
                <tr>
                  <th>N°</th>
                  <th>Fecha</th>
                  <th>Proveedor</th>
                  <th>Descripción</th>
                  <th>Factura</th>
                  <th>tipo</th>
                  <th>Valor gasto</th>
                  <th>Valor pagado</th>
                  <th>Pendiente</th>
                  <th>Acciones</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
                
       </table>

	</div>


	<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Nuevo Gasto</h4>
      </div>
      <div class="modal-body">
        <p>Añadir un gasto al sistema</p>
        <form action="#" id="form">
          <input type="hidden" value="" name="id_gasto"/> 
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="email">*Fecha</label>
              <input type="date" class="form-control" name="fecha">
              <span class="help-block"></span>
            </div>

            <div class="form-group">
              <label for="email">*Proveedor</label>
              <select class="form-control" name="proveedor">
              	<?php foreach ($proveedores as $item): ?>
              	 
              	   <option value="<?php echo $item->id_proveedor?>"><?php echo $item->nombre?></option>  

              	<?php endforeach; ?>
			   
			  </select>
              <span class="help-block"></span>
            </div>

            <div class="form-group">
              <label for="comment">*Descripción del gasto</label>
              <textarea class="form-control" rows="5" name="descripcion"></textarea>
              <span class="help-block"></span>
            </div>

            <div class="form-group">
              <label for="telefono">N° Factura</label>
              <input type="text" class="form-control" name="factura">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="sel1">Tipo de gasto</label>
			  <select class="form-control" id="sel1" name="tipo">
			    <option value="1">Activo de la empresa</option>
			    <option value="2">Coste producción</option>
			    <option value="2">Otro Concepto</option>
			  </select>
              <span class="help-block"></span>
            </div>


            <div class="form-group">
              <label for="tel">*Valor</label>
              <input type="tel" class="form-control" name="valor">
              <span class="help-block"></span>
            </div>
			
			<div class="form-group">
              <label for="comment">*Observaciones</label>
              <textarea class="form-control" rows="5" name="observaciones"></textarea>
              <span class="help-block"></span>
            </div>
           
          </div>
        </div>
      </div>

       <div class="modal-footer">
        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
        <button type="reset" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
            "url": "<?php echo site_url('gastos/ajax_list')?>",
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
            "sLengthMenu":     "Mostrar _MENU_  ",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún Gasto disponible en esta tabla",
            "sInfo":           "Total gastos registrados  _TOTAL_ ",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar gasto:",
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
    $('.modal-title').text('Agregar Gasto'); // Set Title to Bootstrap modal title

    $('#nit').prop( "disabled", false );
}

function edit_person(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#nit').prop( "disabled", true );


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('gastos/ajax_edit/')?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_gasto"]').val(data.id_gasto);
            $('[name="fecha"]').val(data.fecha);
            $('[name="proveedor"]').val(data.proveedor);
            $('[name="descripcion"]').val(data.descripcion);
            $('[name="factura"]').val(data.factura);
            $('[name="tipo"]').val(data.tipo);
            $('[name="valor"]').val(data.valor);
            $('[name="observaciones"]').val(data.observaciones);
       
            $('#myModal').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Gasto'); // Set title to Bootstrap modal title

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
        url = "<?php echo site_url('gastos/ajax_add')?>";
    } else {
        url = "<?php echo site_url('gastos/ajax_update')?>";
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
    if(confirm('Esta seguro de Eliminar este Gasto?. No podra recuperarlo de nuevo!'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('gastos/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                if(data.status == true)
                {
                	$('#myModal').modal('hide');
                	reload_table();

                	alertify.success(data.msj);
                }

                else
                {
                	$('#myModal').modal('hide');
                	reload_table();

                	alertify.alert(data.msj);
                }
                

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }


        });

    }
}

</script>

<?php  $this->load->view('layout/footer') ?>