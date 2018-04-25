<?php  
$this->load->view('layout/header'); 
$url = $this->uri->segment(1);
?>

<div class="container-fluid">
	<ul  class="nav nav-tabs">
  <?php if($url == 'usuarios'): ?>
  <li><a href="<?php echo base_url('clientes')?>">Clientes</a></li>
  <li><a href="<?php echo base_url('proveedores')?>">Proveedores</a></li>
  <li class="active"><a href="<?php echo base_url('usuarios')?>">Usuarios</a></li>
<?php endif;?>
</ul>



<button onclick="add_person()"  type="button" class="btn btn-danger spacer-top spacer-bottom">Nuevo Usuario</button>
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
                  <th>Rol</th>
                  <th>Estado</th>
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
        <h4 class="modal-title text-center">Nuevo Usuario</h4>
      </div>
      <div class="modal-body">
        <p>Añade nuevos Usuarios al sistema</p>
        <form action="#" id="form">
          <input type="hidden" value="" name="id_usuario"/> 
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
              <label for="telefono">Telefono</label>
              <input type="text" class="form-control" name="telefono">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="sel1">Rol</label>
			  <select class="form-control" id="sel1" name="rol">
			    <option value="1">Administrador</option>
			    <option value="2">Vendedor</option>
			  </select>
              <span class="help-block"></span>
            </div>

            <div class="form-group">
              <label for="sel1">Estado</label>
			  <select class="form-control" id="sel1" name="estado">
			    <option value="1">Activo</option>
			    <option value="2">Inactivo</option>
			  </select>
              <span class="help-block"></span>
            </div>

            <div class="form-group">
              <label for="tel">*Usuario</label>
              <input type="tel" class="form-control" name="nombre_usuario">
              <span class="help-block"></span>
            </div>

            <div class="form-group">
              <label for="tel">*Clave</label>
              <input type="password" class="form-control" name="clave">
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
            "url": "<?php echo site_url('usuarios/ajax_list')?>",
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
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Usuarios  registrados  _TOTAL_ ",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar Usuario:",
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
    $('.modal-title').text('Agregar Usuario'); // Set Title to Bootstrap modal title

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
        url : "<?php echo site_url('usuarios/ajax_edit/')?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_usuario"]').val(data.id_usuario);
            $('[name="nombre"]').val(data.nombre);
            $('[name="nit"]').val(data.nit);
            $('[name="direccion"]').val(data.direccion);
            $('[name="telefono"]').val(data.telefono);
            $('[name="rol"]').val(data.rol);
            $('[name="estado"]').val(data.estado);
            $('[name="nombre_usuario"]').val(data.nombre_usuario);
            $('[name="clave"]').val(data.clave);
            $('#myModal').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Proveedor'); // Set title to Bootstrap modal title

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
        url = "<?php echo site_url('usuarios/ajax_add')?>";
    } else {
        url = "<?php echo site_url('usuarios/ajax_update')?>";
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
    if(confirm('Esta seguro de Eliminar este proveedor?. No podra recuperarlo de nuevo!'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('usuarios/ajax_delete')?>/"+id,
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

<?php  $this->load->view('layout/footer') ?>