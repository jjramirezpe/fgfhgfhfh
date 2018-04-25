<?php $this->load->view('layout/header');?>

<div class="container-fluid">
	 
	 <div class="row">
		<div class="col-md-6">
			<h3 class="text-info">Pedidos pendientes</h3>
		</div>

		<div class="col-md-6 text-right">
			
		</div>
	</div>


	<ul class="nav nav-tabs">
	  <li class="active"><a href="<?php echo base_url('pedidos')?>">Pedidos pendientes</a></li>
	  <li><a href="<?php echo base_url('pedidos/nuevo_pedido')?>">Nuevo pedido</a></li>
	</ul>
	
	<hr>


	<div class="msj"></div>
    <div class="table-responsive spacer-top">
   		<table id="table" class="table table-hover" width="100%">
                <thead>
                <tr>
                  <th>N° pedido</th>
                  <th>Fecha pedido</th>
                  <th>Fecha despacho</th>
                  <th>cliente</th>
                  <th>Acciones</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
                
       </table>

	</div>



<script>
  
  /*funciones javascript para el CRUD del cliente */

var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({  

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('pedidos/mostrar_pedidos/ajax_list')?>",
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

</script>
<?php $this->load->view('layout/footer');?>