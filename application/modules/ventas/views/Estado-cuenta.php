<?php $this->load->view('layout/header');?>

<div class="container-fluid">
	<h3>Estado de cuenta para el cliente <?php echo $nombre_cliente->nombre;?></h3>

    <ul class="breadcrumb">
      <li><a href="<?php echo base_url('clientes') ?>">Contactos</a></li>
      <li><a href="<?php echo base_url('clientes/detalle_usuario').'/'.$nombre_cliente->id_cliente;?>">Detalle Usuario</a></li>
      <li>Estado de cuenta</li>
    </ul>
	<hr>



	<div class="table-responsive spacer-top">
   		<table id="table" class="table table-hover" width="100%">
                <thead>
                <tr>
                  <th>Numero Factura</th>
                  <th>Fecha</th>
                  <th>Vencimiento</th>
                  <th>Valor Factura</th>
                  <th>Abono</th>
                  <th>Pendiente</th>
                </tr>
                
                </thead>
                <tbody>
                  <?php foreach($estado_cuenta as $valor):?>

                    <tr>
                      <td><?php echo $valor->numero_factura;?></td>
                      <td><?php echo $valor->fecha_factura;?></td>
                      <td><?php echo $valor->fecha_vencimiento;?></td>
                      <td>$<?php echo number_format($valor->total, 0, '.',',');?></td>
                      <td>0</td>
                      <td>$<?php echo number_format($valor->total, 0, '.',',');?></td>
                    </tr>

                  <?php endforeach?>
                </tbody>
                
       </table>

       <h4 class="text-danger">Total Adeudado $<?php echo number_format($total_cliente->valor, 0,'.','.');?> </h4>

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
  $(document).ready(function() {
      $('#table').DataTable({
         dom: 'Bfrtip',
       buttons: 
       [
           
            {
                extend: 'pdfHtml5',
                text: '<span class="glyphicon glyphicon-file"></span> Exportar a PDF',
                className: 'btn btn-primary',
                footer: true,
                orientation: 'portrait',
                pageSize: 'LEGAL',
                download: 'open',
                messageTop: 'Relación facturas pendientes de pago a la fecha  <?php echo date('Y/m/d');?> para el cliente <?php echo $nombre_cliente->nombre;?> ',
                messageBottom:'Total Adeudado  $<?php echo number_format($total_cliente->valor, 0,'.','.');?>',
                title: 'Estado de cuenta'

            }
        ]
      });

  });


</script>

<?php $this->load->view('layout/footer');?>