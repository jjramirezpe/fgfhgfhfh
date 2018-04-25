<?php $this->load->view('layout/header')?>


<div class="container-fluid">
	<h3 class="text-primary"><?php echo $empresa->nombre;?></h3>
	<h4 class="text-success">Resumen de sus movimientos</h4>
	<hr>
	 <div class="row">
	 	<div class="col-md-6">
	 		<div class="panel panel-default">
			  <div class="panel-body">
			  	<h4 class="gly-color-green">Resumen de ventas</h4>
			  	
			  		<label class="radio-inline gly-color-yellow"><input type="radio" name="optradio" onclick="ver_hoy()"checked>Hoy</label>
					<label class="radio-inline gly-color-yellow"><input type="radio" name="optradio" onclick="ver_mes()">Este mes</label>
					<label class="radio-inline gly-color-yellow"><input type="radio" name="optradio" onclick="ver_anio()">Este año</label>
			  	
			  	<div class="row">
			  		<div class="col-md-4">
			  			<p>
			  				<div class="alert alert-info ventas-hoy">
			  				  <?php if($total_dia->valor == ''){echo '0';}?>
							  <?php echo number_format($total_dia->valor,0,',',',')?>
							</div>
						</p>
						<p class="gly-color-blue">Vendido</p>
			  		</div>

			  		<div class="col-md-4">
			  			<p>
			  				<div class="alert alert-danger hoy-credito">
			  					<?php if($total_dia_credito->valor == ''){echo '0';}?>
							  <?php echo  number_format($total_dia_credito->valor,0,',',',')?>
							</div>
						</p>
						<p class="gly-color-red">Credito</p>
			  		</div>
			  		<div class="col-md-4">
			  			<p>
			  				<div class="alert alert-success hoy-contado">
			  				   <?php if($total_dia_contado->valor == ''){echo '0';}?>
							   <?php echo number_format($total_dia_contado->valor,0,',',',')?>
							</div>
						</p>
						<p class="gly-color-green">Contado</p>
			  		</div>

			  	</div>
			  </div>
			</div>
	 	</div>

	 	<!----------------------------------------------------fin resumen ventas---------------------------------->

	 	<div class="col-md-6">
	 		<div class="panel panel-default">
			  <div class="panel-body">
			  	<h4 class="gly-color-red">Resumen de Banco</h4>
			  		<label class="radio-inline gly-color-yellow"><input type="radio" name="optradio2" onclick="ver_banco()" checked>Hoy</label>
					<label class="radio-inline gly-color-yellow"><input type="radio" name="optradio2" onclick="ver_banco_mes()">Este mes</label>
					<label class="radio-inline gly-color-yellow"><input type="radio" name="optradio2" onclick="ver_banco_anio()">Este año</label>
			  	<div class="row">
			  		<div class="col-md-4">
			  			<p><div class="alert alert-info ingresos">
			  				<?php if($ingresos_dia->valor == ''){echo '0';}?>
							 <?php echo number_format($ingresos_dia->valor)?>
							</div>
						</p>
						<p class="gly-color-blue">Ingresos</p>
			  		</div>

			  		<div class="col-md-4">
			  			<p><div class="alert alert-danger egresos">
			  				<?php if($egresos_dia->valor == ''){echo '0';}?>
							 <?php echo $egresos_dia->valor;?>
							</div>
						</p>
						<p class="gly-color-red">Egresos</p>
			  		</div>

			  		<div class="col-md-4">
			  			
			  		</div>

			  	</div>
			  </div>
			</div>
	 	</div>
		
	 </div>

	 <!-----------------------------------------------------fin resumen banco -------------------------------------------->

	 <div class="row">
	 	<div class="col-md-6">
	 		<div class="panel panel-default">
			  <div class="panel-body">
			  	<h4 class="gly-color-green">Resumen de Cartera</h4>
			  	
			  	<div class="row">

			  		<div class="col-md-4">
			  			<p><div class="alert alert-danger">
			  				<?php if($fact_vencidas != ''):?>
			  					<?php echo number_format($fact_vencidas)?>
			  				<?php else:?>
			  					0
			  				<?php endif?>	
							</div>
						</p>
						<p class="gly-color-red">Vencido</p>
			  		</div>

			  		<div class="col-md-4">
			  			<p><div class="alert alert-success">
								<?php if($fact_vigentes != ''):?>
			  						<?php echo number_format($fact_vigentes)?>
			  					<?php else:?>
			  						0
			  					<?php endif?>	
							</div>
						</p>
						<p class="gly-color-green">vigente</p>
			  		</div>

			  	</div>
			  </div>
			</div>
	 	</div>
	 	<div class="col-md-6">
	 		<div class="panel panel-default">
			  <div class="panel-body">
			  	<h4 class="gly-color-green">Resumen de Invetario</h4>
			  	
			  	
			  	<div class="row">

			  		<div class="col-md-4">
			  			<p><div class="alert alert-info">
							  <?php if($articulos_inv != ''):?>
			  						<?php echo $articulos_inv?>
			  					<?php else:?>
			  						0
			  					<?php endif?>
							</div>
						</p>
						<p class="text-info">Articulos en inventario</p>
			  		</div>

			  		<div class="col-md-4">
			  			<p><div class="alert alert-danger">
							  <?php if($valor_inventario != ''):?>
			  						<?php echo number_format($valor_inventario)?>
			  					<?php else:?>
			  						0
			  					<?php endif?>
							</div>
						</p>
						<p class="text-danger">Costo del  Inventario </p>
			  		</div>

			  		<div class="col-md-4">
			  			
			  		</div>

			  	</div>
			  </div>
			</div>
	 	</div>
	 </div>

	 
</div>

<script>
	
function ver_hoy()
{
	$.ajax({
		url: '<?php echo base_url('inicio/ventas_por_dia')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".ventas-hoy").html(data.result).number(true,0);
			}
		}
	})

	$.ajax({
		url: '<?php echo base_url('inicio/ventas_dia_credito')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".hoy-credito").html(data.result).number(true,0);
			}
		}
	})

	$.ajax({
		url: '<?php echo base_url('inicio/ventas_dia_contado')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".hoy-contado").html(data.result).number(true,0);
			}
		}
	})
}


function ver_mes()
{
	$.ajax({
		url: '<?php echo base_url('inicio/ventas_por_mes')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".ventas-hoy").html(data.result).number(true,0);
			}
		}
	})

	$.ajax({
		url: '<?php echo base_url('inicio/ventas_mes_credito')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".hoy-credito").html(data.result).number(true,0);
			}
		}
	})

	$.ajax({
		url: '<?php echo base_url('inicio/ventas_mes_contado')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".hoy-contado").html(data.result).number(true,0);
			}
		}
	})
}


function ver_anio()
{
	$.ajax({
		url: '<?php echo base_url('inicio/ventas_por_anio')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".ventas-hoy").html(data.result).number(true,0);
			}
		}
	})

	$.ajax({
		url: '<?php echo base_url('inicio/ventas_anio_credito')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".hoy-credito").html(data.result).number(true,0);
			}
		}
	})

	$.ajax({
		url: '<?php echo base_url('inicio/ventas_anio_contado')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".hoy-contado").html(data.result).number(true,0);
			}
		}
	})
}



function ver_banco()
{
	$.ajax({
		url: '<?php echo base_url('inicio/ingresos_dia')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".ingresos").html(data.result).number(true,0);
			}
			else
			{
				$(".ingresos").html('No hay Ingresos')
			}
		}
	})

	$.ajax({
		url: '<?php echo base_url('inicio/egresos_dia')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".egresos").html(data.result).number(true,0);
			}
			else
			{
				$(".egresos").html('No hay Egresos')
			}
		}
	})
}


function ver_banco_mes()
{
	$.ajax({
		url: '<?php echo base_url('inicio/ingresos_mes')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".ingresos").html(data.result).number(true,0);
			}
		}
	})

	$.ajax({
		url: '<?php echo base_url('inicio/egresos_mes')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".egresos").html(data.result).number(true,0);
			}
		}
	})
}


function ver_banco_anio()
{
	$.ajax({
		url: '<?php echo base_url('inicio/ingresos_anio')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".ingresos").html(data.result).number(true,0);
			}
		}
	})

	$.ajax({
		url: '<?php echo base_url('inicio/egresos_anio')?>',
		type: 'GET',
		dataType:'json',
		success: function(data){
			if(data.status == true)
			{
				$(".egresos").html(data.result).number(true,0);
			}
		}
	})
}




</script>




<?php $this->load->view('layout/footer')?>