<?php $this->load->view('layout/header');?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<h3 class="spacer-top text-primary">Ajustes de la empresa y facturación</h3>
		</div>

		<div class="col-md-6 text-right">
			<button class="spacer-top btn btn-primary" type="button" onclick="ingresarDatos()">Guardar Datos</button>
		</div>
	</div>
	
	<hr>

	<div class="panel panel-default">
		<div class="panel-body">
			<form id="form">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
						    <label for="nombre empresa">Nombre/Razón social de la empresa:</label>
						    <input type="text" class="form-control" name="empresa" value="<?php echo $empresa->nombre;?>">
						</div>

						<div class="form-group">
						    <label for="nombre empresa">NIT de la empresa:</label>
						    <input type="text" class="form-control" name="nit" value="<?php echo $empresa->nit;?>">
						</div>

						<div class="form-group">
						    <label for="direccion">Dirección:</label>
						    <input type="text" class="form-control" name="direccion" value="<?php echo $empresa->direccion;?>">
						</div>

						<div class="form-group">
						    <label for="nombre empresa">Teléfono:</label>
						    <input type="text" class="form-control" name="telefono" value="<?php echo $empresa->telefono;?>">
						</div>

						<div class="form-group">
						    <label for="nombre empresa">Dirección de correo:</label>
						    <input type="email" class="form-control" name="email" value="<?php echo $empresa->email;?>">
						</div>
					</div>
					<div class="col-md-6 regimen">
						<div class="form-group">
						    <label for="nombre empresa">Proximo número de factura:</label>
						    <input type="number" class="form-control" name="numero" value="<?php echo $empresa->numero;?>">
						</div>
						<?php if($empresa->tipo == 1):?>
						<div class="form-group">
						    <label for="nombre empresa">Tipo de Facturación:</label>
						    	<select class="form-control" id="tipo" name="tipo" onchange="datosRegimenComun()">
						    		<option value="1">Regimen Común</option>
						    		<option></option>
								    <option value="2">Regimen simplificado</option>
								    <option value="3">Otro</option>
							</select>
						</div>

						<div class="form-group"><label for="nombre empresa">Resolución Dian:</label><textarea class="form-control" rows="5" id="comment" name="resolucion"><?php echo $empresa->resolucion;?></textarea>

					<?php elseif($empresa->tipo == 2):?>
						<div class="form-group">
						    <label for="nombre empresa">Tipo de Facturación:</label>
						    	<select class="form-control" id="tipo" name="tipo" onchange="datosRegimenComun()">
						    		<option value="2">Regimen simplificado</option>
						    		<option></option>
								    <option value="1">Regimen Común</option>
								    <option value="3">Otro</option>
							</select>
						</div>

					<?php elseif($empresa->tipo == 3):?>
						<div class="form-group">
						    <label for="nombre empresa">Tipo de Facturación:</label>
						    	<select class="form-control" id="tipo" name="tipo" onchange="datosRegimenComun()">
						    		<option value="3">Otro</option>
						    		<option></option>
						    		 <option value="1">Regimen Común</option>
						    		<option value="2">Regimen simplificado</option>
							</select>
						</div>
					<?php endif?>

					</div>
				</div>
				
			</form>
		</div>
	</div>


</div>

<script>
	function datosRegimenComun()
	{
		if($("#tipo").val() == 1)
		{
			var resolucion = '<div class="form-group"><label for="nombre empresa">Resolución Dian:</label><textarea class="form-control" rows="5" id="comment" name="resolucion"></textarea>';
			
			$(".regimen").append(resolucion);

			if($(".regimen textarea").length > 1)
			{
				$(".regimen textarea").eq(1).remove();
				$(".regimen label").eq(2).remove();
			}
		}
		else
		{
			$(".regimen textarea").remove();
			$(".regimen label").eq(1).remove();
		}
	}



	function ingresarDatos()
	{
		$.ajax({

			 url : '<?php echo base_url("configuracion/guardar_empresa")?>',
	         type: "POST",
	         data: $('#form').serialize(),
	         dataType: "JSON",
	         success: function(data)
	         {
	         	if(data.status == true)
	         	{
	         		alertify.alert(data.msj);
	         		//location.reload();
	         	}
	         	else
	         	{
	         		alertify.alert(data.msj);
	         	}
	         }
		})
	}
</script>

<?php $this->load->view('layout/footer');?>