<?php $this->load->view('layout/header');
$url = $this->uri->segment(1);
?>


<div class="container-fluid">
	
<ul  class="nav nav-tabs">
  <?php if($url == 'banco'): ?>
  <li class="active"><a href="<?php echo base_url('banco')?>">Transacciones</a></li>
  <li class=""><a href="<?php echo base_url('banco/pagar_gasto')?>">Pagar gastos</a></li>
  <li><a href="<?php echo base_url('banco/cobrar_factura')?>">cobrar Factura</a></li>
  <li><a href="<?php echo base_url('banco/ver_cuentas')?>">Cuentas</a></li>
<?php endif;?>

</ul>

<h3 class="spacer-top text-primary">Transacciones realizadas</h3>

<div class="msj"></div>
    <div class="table-responsive spacer-top">
   		<table id="table" class="table table-hover" width="100%">
                <thead>
                <tr>
                  <th>N°</th>
                  <th>Descripcion</th>
                  <th>fecha transaccion</th>
                  <th>cuenta</th>
                  <th>Importe</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
                
       </table>

	</div>

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
            "url": "<?php echo site_url('Banco/transacciones/ajax_list')?>",
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
            "sEmptyTable":     "Ningúna transacción disponible en esta tabla",
            "sInfo":           "Total  transacciones  _TOTAL_ ",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar transaccion:",
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


});

</script>

<?php $this->load->view('layout/footer');?>