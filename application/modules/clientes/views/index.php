<?php 
$this->load->view('layout/header');
$url = $this->uri->segment(1);
?>

<div class="container-fluid">
	
<ul  class="nav nav-tabs">
  <?php if($url == 'clientes'): ?>
  <li class="active"><a href="<?php echo base_url('clientes')?>">Clientes</a></li>
  <li><a href="<?php echo base_url('proveedores')?>">Proveedores</a></li>
  <li><a href="<?php echo base_url('usuarios')?>">Usuarios</a></li>
<?php endif;?>

<?php if($url == 'usuarios'): ?>
  <li><a href="#">Clientes</a></li>
  <li><a href="#">Proveedores</a></li>
  <li class="active"><a href="#">Usuarios</a></li>
<?php endif;?>
</ul>


	<button onclick="add_person()"  type="button" class="btn btn-danger spacer-top spacer-bottom">Nuevo Cliente</button>
  <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Recargar</button>


	

   <div class="msj"></div>
    <div class="table-responsive spacer-top">
   <table id="table" class="table table-hover" width="100%">
                <thead>
                <tr>
                  <th>N°</th>
                  <th>Nombre</th>
                  <th>Nit</th>
                  <th>Direccion</th>
                  <th>Telefono</th>
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
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Nuevo Cliente</h4>
      </div>
      <div class="modal-body">
        <p>Añade nuevos clientes al sistema</p>
        <form action="#" id="form">
          <input type="hidden" value="" name="id_cliente"/> 
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="email">*Nombre</label>
              <input type="text" class="form-control" name="nombre">
              <span class="help-block"></span>
            </div>

            <div class="form-group">
              <label for="email">*Nit</label>
              <input type="text" class="form-control" name="nit" id="nit">
              <span class="help-block"></span>
            </div>

            <div class="form-group">
              <label for="comment">*Dirección</label>
              <textarea class="form-control" rows="5" name="direccion"></textarea>
              <span class="help-block"></span>
            </div>

            <div class="form-group">
              <label for="email">Ciudad</label>
              <input type="text" class="form-control" name="ciudad">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" name="email">
              <span class="help-block"></span>
            </div>

            <div class="form-group">
              <label for="tel">*Télefono</label>
              <input type="tel" class="form-control" name="telefono">
              <span class="help-block"></span>
            </div>

            <div class="form-group">
              <label for="sel1">*Condiciones de pago </label>
              <select class="form-control" name="condiciones">
                <option value="0">Contado</option>
                <option value="30">30 días</option>
                <option value="60">60 días</option> 
                <option value="1">Definir Manual</option>
              </select>
              <span class="help-block"></span>
            </div>

            <div class="form-group">
              <label for="tel">Monto Crédito</label>
              <input type="text" class="form-control" name="monto">
              <span class="help-block"></span>
            </div>




          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Crear Cliente</button>
        <button type="reset" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </form>
    </div>

  </div>
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
            "url": "<?php echo site_url('clientes/ajax_list')?>",
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
            "sLengthMenu":     "Mostrar _MENU_ Clentes ",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Clientes registrados  _TOTAL_ ",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar Cliente:",
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
    $('.modal-title').text('Agregar Cliente'); // Set Title to Bootstrap modal title

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
        url : "<?php echo site_url('clientes/ajax_edit/')?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_cliente"]').val(data.id_cliente);
            $('[name="nombre"]').val(data.nombre);
            $('[name="nit"]').val(data.nit);
            $('[name="direccion"]').val(data.direccion);
            $('[name="ciudad"]').val(data.ciudad);
            $('[name="email"]').val(data.email);
            $('[name="telefono"]').val(data.telefono);
            $('[name="condiciones"]').val(data.condiciones);
            $('[name="monto"]').val(data.monto);
            $('#myModal').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Cliente'); // Set title to Bootstrap modal title

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
        url = "<?php echo site_url('clientes/ajax_add')?>";
    } else {
        url = "<?php echo site_url('clientes/ajax_update')?>";
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

                //mensaje de actualizacion
                //$('.msj').html('<div class="alert alert-success"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> '+data.msj+'</div>');
                //aparece mensaje
                 //setTimeout(function() {
                //$(".msj").fadeIn(3000)
            //});
            

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
            url : "<?php echo site_url('clientes/ajax_delete')?>/"+id,
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


function ver_person(id){
    $.ajax({
        url : "<?php echo site_url('clientes/ajax_edit/')?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            var nombre =val(data.nombre);
            var nit = val(data.nit);
            var direccion = val(data.direccion);
            var ciudad = val(data.ciudad);
            var email = val(data.email);
            var telefono = val(data.telefono);
            var condiciones =  val(data.condiciones);
            var monto = val(data.monto);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error al cargar los datos');
        }
    });
}


</script>



<?php $this->load->view('layout/footer')?>