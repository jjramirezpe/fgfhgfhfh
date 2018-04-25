<?php
class Factura extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('factura_mdl');
		$this->load->helper('url');
	}

	public function buscar_articulo()
	{
		if($this->input->is_ajax_request() && isset($_POST['articulo']))
			{
				$articulo = $this->input->post('articulo');
				
				$query = $this->factura_mdl->get_by_code($articulo);
				if($query != false)
				{
					$userdata = $query;
					$data['status']	= true;	
					$data['result'] = $userdata;
					
				}
				else
				{
					$data['status']	= false;	
					$data['result'] = '<span class="glyphicon glyphicon-remove text-danger"></span> La referencia que intenta ingresar  no existe en el inventario';
				}
				echo json_encode($data);
			}
	}

	public function buscar_cliente()
	{
		if($this->input->is_ajax_request() && isset($_POST['nit']))
		{
			$cliente = $this->input->post('nit');
			

			$query = $this->factura_mdl->get_by_cliente($cliente);
			
			if($query != false)
			{
				$id_cliente = $this->factura_mdl->get_by_cliente($cliente)->id_cliente;/*id del cliente*/
				$deuda = $this->factura_mdl->get_total_amount_cliente($id_cliente);/*deuda del cliente*/
				
				$data['status'] = true;
				$data['result'] = $query;
				$data['deuda'] = $deuda;
			}

			else
			{
				$data['status']	= false;	
				$data['result'] = 'el cliente no existe';
			}
			echo json_encode($data);
		}
	}

	public function save_factura()
	{
		/*obtener tatos del form para la factura*/
		$numero_factura = $this->input->post('numero_factura');
		$id_cliente = $this->input->post('id_cliente');
		$fecha_factura = $this->input->post('fecha');
		$condiciones_factura = $this->input->post('condiciones');
		$fecha_vencimiento = $this->input->post('fechav');
		$notas = $this->input->post('notas');
		$vendedor = $this->input->post('vendedor');
		$subtotales = $this->input->post('subtotales');
		$ivas = $this->input->post('ivas');
		$totales = $this->input->post('totales');
		$terminos = $this->input->post('terminos');
		$saldo = $this->input->post('monto');

		/*almacenar los articulos para la factura en un array*/
		$id_articulo = $this->input->post('id_articulo');
		$referencia = $this->input->post('referencia');
		$descripcion = $this->input->post('descrip');
		$cantidad = $this->input->post('cantidad');
		$precio = $this->input->post('precio');
		$subtotal = $this->input->post('subtotal');
		$tasa_impuesto = $this->input->post('tasa_impuesto');
		$impuesto = $this->input->post('impuesto');
		$total = $this->input->post('total');


		/*validaciones datos para la factura*/
		if($this->factura_mdl->comprobar_cliente($id_cliente) == false)
		{
			$msj = '<span class="glyphicon glyphicon-remove text-danger"></span> No existe un cliente. No se puede crear la factura';
			echo json_encode(array("status" => false, "msj" => $msj));
		}
		/*verificar numero de factura no exista*/
		elseif($this->factura_mdl->comprobar_factura($numero_factura) == true)
		{
			$msj = '<span class="glyphicon glyphicon-remove text-danger"></span> el numero de factura ya existe. No se puede crear la factura';
			echo json_encode(array("status" => false, "msj" => $msj));
		}

		/*verificar si existen articulos en la factura*/
		elseif($this->input->post('referencia')== '')
		{
			$msj = '<span class="glyphicon glyphicon-remove text-danger"></span> No existen articulos. No se puede crear la factura';
				echo json_encode(array("status" => false, "msj" => $msj));
		}

		elseif($totales > $this->input->post('monto') and $this->input->post('monto') != 0)
		{
			$msj = '<span class="glyphicon glyphicon-remove text-danger"></span> El total de la factura es mayor al credito del cliente. No se puede crear la factura';
			echo json_encode(array("status" => false, "msj" => $msj));
		}


		/*almacenar los datos para la db en un array*/
		else
		{
			$data = array(
			'numero_factura' => $numero_factura,
			'id_cliente' => $id_cliente,
			'fecha_factura' => $fecha_factura,
			'condiciones_factura' => $condiciones_factura,
			'fecha_vencimiento' => $fecha_vencimiento,
			'notas' => $notas,
			'vendedor' => $vendedor,
			'subtotal' => $subtotales,
			'iva' => $ivas,
			'total' => $totales,
			'terminos' => $terminos
			);


			/*enviar los datos al modelo para ser ingresados a la db*/
			$query = $this->factura_mdl->guardar_factura($data);
			
			if($query == true)
			{
				/*contador para los articulos*/
				$count = count($this->input->post('referencia'));

				/*obtener el ultimo id de la factura y los articulos a agregar*/
				$id_factura = $this->db->insert_id();

				/*preparo los datos para enviar a la base de datos*/
				
				for($i=0; $i<$count; $i++)
				{
					$items[$i] = array(
					'id_articulo' =>$id_articulo[$i],
					'id_factura' => $id_factura,
					'id_cliente' => $id_cliente,
					'referencia' => $referencia[$i],
					'descripcion' => $descripcion[$i],
					'cantidad' => $cantidad[$i],
					'precio' => $precio[$i],
					'subtotal' => $subtotal[$i],
					'tasa_impuesto' => $tasa_impuesto[$i],
					'impuesto' => $impuesto[$i],
					'total' => $total[$i]
					);
				}
				
				$query = $this->factura_mdl->save_items_fact($items);
				if($query == true )
				{
					$id_factura = $this->factura_mdl->select_last_id()->id_factura;
					$msj = 'Factura Creada con exito';
					echo json_encode(array("status" => TRUE, "msj" => $msj, "id" => $id_factura));
				}
			}
			
		}

	}


	public function estado_cuenta($id)
	{
		$data['estado_cuenta'] = $this->factura_mdl->get_estado_cueta($id);
		$data['nombre_cliente'] = $this->factura_mdl->get_nombre_cliente($id);
		$data['total_cliente'] = $this->factura_mdl->get_total_amount_cliente($id);
		$this->load->view('ventas/estado-cuenta',$data);
	}


	public function actualizar_factura($id)
	{

		/*triggers asociados
			trigger actualizar_articulo: actualiza los articulos de la factura
			trigger cantidad_actualizada: actualiza cantidad del inventario despues de una 
			actualizacion en la cantidad de la factura.
		*/


		$id_factura = $id;
		$id_cliente = $this->input->post('id_cliente');

		/*datos para la factura*/
		$fecha_factura = $this->input->post('fecha'); 
		$condiciones_factura = $this->input->post('condiciones');
		$fecha_vencimiento = $this->input->post('fechav');
		$notas = $this->input->post('notas');
		$subtotales = $this->input->post('subtotales');
		$ivas = $this->input->post('ivas');
		$totales = $this->input->post('totales');
		$terminos = $this->input->post('terminos');

		/*datos para los articulos*/
		$id_articulo = $this->input->post('id_articulo');
		$referencia = $this->input->post('referencia');
		$descripcion = $this->input->post('descrip');
		$cantidad = $this->input->post('cantidad');
		$precio = $this->input->post('precio');
		$subtotal = $this->input->post('subtotal');
		$tasa_impuesto = $this->input->post('tasa_impuesto');
		$impuesto = $this->input->post('impuesto');
		$total = $this->input->post('total');

		/*actualizar datos facturas*/
		$data_factura = array(
			'fecha_factura' => $fecha_factura,
			'condiciones_factura' => $condiciones_factura,
			'fecha_vencimiento' => $fecha_vencimiento,
			'notas' => $notas,
			'subtotal' => $subtotales,
			'iva' => $ivas,
			'total' => $totales,
			'terminos' => $terminos
		);


		if($totales > $this->input->post('monto') and $this->input->post('monto') != 0)
		{
			$msj = '<span class="glyphicon glyphicon-remove text-danger"></span> El total de la factura es mayor al credito del cliente. No se puede editar  la factura';
			echo json_encode(array("status" => false, "msj" => $msj));
		}

		else
		{
			$update = $this->factura_mdl->actualizar_factura($id_factura, $data_factura);

		if($update == true)
		{
			
			/*guardo los articulos en la tabla articulos_factura_actualizada*/
			/*contador para los articulos*/
				$count = count($this->input->post('referencia'));


				/*preparo los datos para enviar a la base de datos*/
				
				for($i=0; $i<$count; $i++)
				{
					$items[$i] = array(
					'id_articulo' =>$id_articulo[$i],
					'id_factura' => $id_factura,
					'id_cliente' => $id_cliente,
					'referencia' => $referencia[$i],
					'descripcion' => $descripcion[$i],
					'cantidad' => $cantidad[$i],
					'precio' => $precio[$i],
					'subtotal' => $subtotal[$i],
					'tasa_impuesto' => $tasa_impuesto[$i],
					'impuesto' => $impuesto[$i],
					'total' => $total[$i]
					);
				}
				/*guardo los datos a actualizar en una nueva tabla articulos_factura_actualizados*/
				$query = $this->factura_mdl->save_items_fact_actualizados($items);
				if($query == true)
				{
					//borramos los articulos que ya se actualizaron mediane un trigger 
					$this->db->truncate('articulos_factura_actualizados');
					echo json_encode(array('status' => true, 'msj' => 'articulos actualizados'));
				}
				else
				{
					echo json_encode(array('status' => false, 'msj' => 'articulos no actualizados'));
				}

		}
		else
		{
			echo json_encode(array('status' => false, 'msj' => 'No se puede acrualizar la factura en este momento. Intentelo de nuevo mas tarde.'));
		}
		}


		

	}

	
}

