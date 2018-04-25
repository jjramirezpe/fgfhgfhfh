<?php $this->load->view('layout/header');
$url = $this->uri->segment(2);
?>


<div class="container-fluid">
	
<ul  class="nav nav-tabs">
  <?php if($url == 'ver_cuentas'): ?>
  <li><a href="<?php echo base_url('banco')?>">Transacciones</a></li>
  <li class=""><a href="<?php echo base_url('banco/pagar_gasto')?>">Pagar gastos</a></li>
  <li><a href="<?php echo base_url('banco/cobrar_factura')?>">cobrar Factura</a></li>
  <li class="active"><a href="<?php echo base_url('banco/ver_cuentas')?>">Cuentas</a></li>
<?php endif;?>

</ul>


<button onclick="add_person()"  type="button" class="btn btn-danger spacer-top spacer-bottom">Agregar nueva cuenta</button>
  <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Recargar</button>

<div class="msj"></div>
    <div class="table-responsive spacer-top">
   		<table id="table" class="table table-hover" width="100%">
                <thead>
                <tr>
                  <th>N°</th>
                  <th>Nombre</th>
                  <th>codigo de la cuenta</th>
                  <th>Saldo</th>
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
        <h4 class="modal-title text-center">Nueva cuenta de Banco</h4>
      </div>
      <div class="modal-body">
        <p>Añadir una nueva cuenta de Banco al sistema</p>
        <form action="#" id="form">
          <input type="hidden" value="" name="id_cuenta"/> 
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="email">*nombre de la cuenta</label>
              <input type="text" class="form-control" name="nombre">
              <span class="help-block"></span>
            </div>

            <div class="form-group">
              <label for="comment">*Codigo de la cuenta</label>
              	<input type="text" class="form-control" name="codigo">
              <span class="help-block"></span>
            </div>

			<?php if($this->uri->segment(2)!='ajax_edit_cuenta'):?>
            <div class="form-group">
              <label for="telefono">Saldo</label>
              <input type="text" class="form-control" name="saldo" id="saldo">
              <span class="help-block"></span>
            </div>
        	<?php endif;?>

          </div>
          <div class="col-md-6">
          	<p>Las cuentas son necesarias para poder gestionar los cobros de facturas y los pagos de los gastos.</p>
          	<p>Por defecto ya cuentas con una caja general; pero puedes agregar mas cuentas.</p>
          </div>
        </div>
      </div>

       <div class="modal-footer">
        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
        <button type="reset" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </form>

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
            "url": "<?php echo site_url('Banco/ajax_list_cuentas')?>",
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
            "sEmptyTable":     "Ningúna cuenta disponible en esta tabla",
            "sInfo":           "Total cuentas registradas  _TOTAL_ ",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar cuenta:",
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
    $('.modal-title').text('Agregar Nueva Cuentade Banco'); // Set Title to Bootstrap modal title

    $('#nit').prop( "disabled", false );
}

function edit_person(id)
{
	if(id == 1)
	{
		alertify.alert('No se puede editar la cuenta principal del sistema');
	}
	else
	{
		save_method = 'update';
	    $('#form')[0].reset(); // reset form on modals
	    $('.form-group').removeClass('has-error'); // clear error class
	    $('.help-block').empty(); // clear error string
	    $('#saldo').remove();

	    //Ajax Load data from ajax
	    $.ajax({
	        url : "<?php echo site_url('banco/ajax_edit_cuenta/')?>/"+id,
	        type: "GET",
	        dataType: "JSON",
	        success: function(data)
	        {

	            $('[name="id_cuenta"]').val(data.id_cuenta);
	            $('[name="nombre"]').val(data.nombre);
	            $('[name="codigo"]').val(data.codigo);
	       
	            $('#myModal').modal('show'); // show bootstrap modal when complete loaded
	            $('.modal-title').text('Editar Cuenta de Banco'); // Set title to Bootstrap modal title

	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	            alert('Error get data from ajax');
	        }
	    });
	}
    
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
        url = "<?php echo site_url('banco/ajax_add_cuenta')?>";
    } else {
        url = "<?php echo site_url('banco/ajax_update_cuenta')?>";
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
            url : "<?php echo site_url('banco/ajax_delete')?>/"+id,
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

</div>

<?php $this->load->view('layout/footer');?>