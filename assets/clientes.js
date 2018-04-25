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
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
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
            $('[name="ciudad"]').val(data.condiciones);
            $('[name="ciudad"]').val(data.monto);
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

                //mensaje de actualizacion
                $('.msj').html('<div class="alert alert-success"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> '+data.msj+'</div>');
                //aparece mensaje
                 setTimeout(function() {
                $(".msj").fadeIn(3000)
            });
            

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

                //mensaje de actualizacion
                $('.msj').html('<div class="alert alert-success"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> '+data.msj+'</div>');
                //aparece mensaje
                 setTimeout(function() {
                $(".msj").fadeIn(3000)
            });

                 //desaparece mensaje
            setTimeout(function() {
                $(".msj").fadeOut(4000)
            },1500);

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }


        });

    }
}

