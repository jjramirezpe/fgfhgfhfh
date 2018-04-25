<?php $this->load->view('layout/header');?>
<div class="container-fluid">
	<h4>Facturas</h4>
	<hr>
	<ul class="nav nav-tabs">
	  <li class=""><a href="<?php echo base_url('ventas');?>">Todas</a></li>
	  <li class="active"><a href="<?php echo base_url('ventas/pagadas');?>">Pagadas</a></li>
	  <li><a href="<?php echo base_url('ventas/vencidas');?>">Vencidas</a></li>
	</ul>
	<a href="<?php echo base_url('ventas/nueva_factura');?>" class="btn btn-danger spacer-top">Nueva Factura</a>
	<button class="btn btn-default spacer-top" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Recargar</button>




	<div class="table-responsive spacer-top">
   		<table id="table" class="table table-hover" width="100%">
                <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Numero</th>
                  <th>Fecha</th>
                  <th>Vencimiento</th>
                  <th>Vendedor</th>
                  <th>Total</th>
                  <th>Estado</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
                
       </table>

	</div>


</div>

<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

<script>
	
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({  

        
        dom: 'Bfrtip',
        buttons: [
            'copy',
            {
                extend: 'excel',
                messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
            },
            {
                extend: 'pdf',
                messageTop: 'Relación facturas pendientes de pago a la fecha  <?php echo date('Y/m/d');?>',
                messageBottom:'Nombre de la empresa - dirección - telefono',
                title: 'Estado de cuenta'
            },
            {
                extend: 'print',
                messageTop: 'cualquier cosa',
                messageBottom: null
            }
        ],











        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('ventas/ver_facturas_pagadas')?>",
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
            "sInfo":           "Total Facturas  _TOTAL_ ",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar Factura:",
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

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

</script>

<?php $this->load->view('layout/footer');?>