<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pedidos_mdl');
		$this->load->helper('url');
	}

	/*muestra menu de banco y transacciones realizadas*/
	public function index()
	{
		$this->load->view('pedidos/index');
	}


	

	function ingresar_pedido()
	{
		/*datos para el pedido*/
		$cliente = $this->input->post('id_cliente');
		//$referencia = $this->input->post('referencia');
		$fecha_e = $this->input->post('fecha_e');
		$comentario = $this->input->post('comentario');

		$id_articulo = $this->input->post('id_articulo');
		$referencia = $this->input->post('referencia');
		$descripcion = $this->input->post('descrip');
		$cantidad = $this->input->post('cantidad');
		$precio = $this->input->post('precio');
		$total = $this->input->post('total');

		$id_pedido = $this->pedidos_mdl->id_pedido()->id_pedido + 1;

		if($cliente == '')
		{
			$msj = 'ingrese un cliente';
			echo json_encode(array('status'=>false,'msj'=>$msj));
		}

		else if($fecha_e == ''){
			$msj = 'ingrese una fecha de entrega';
			echo json_encode(array('status'=>false,'msj'=>$msj));
		}

		else if($referencia == ''){
			$msj = 'ingrese minimo un articulo para el pedido';
			echo json_encode(array('status'=>false,'msj'=>$msj));
		}
		else
		{
			$data = array(
				'id_cliente'=>$cliente,
				'fecha_e'=>$fecha_e,
				'comentario'=>$comentario
			);

			$insert = $this->pedidos_mdl->ingresar_pedido($data);
			if($insert == true)
			{
				/*ingresar articulos del pedido*/
				

				$count = count($this->input->post('referencia'));

				for($i=0; $i<$count; $i++)
				{
					$items[$i] = array(
						'id_pedido'=>$id_pedido,
						'id_articulo'=>$id_articulo[$i],
						'referencia'=>$referencia[$i],
						'descripcion'=>$descripcion[$i],
						'cantidad'=>$cantidad[$i],
						'precio'=>$precio[$i],
						'total'=>$total[$i],
					);
				}

				$query = $this->pedidos_mdl->save_items_pedido($items);


				echo json_encode(array('status'=>true,'msj'=>'Pedido agregado con exito'));
			}
			else
			{
				echo json_encode(array('status'=>false,'msj'=>'no fue posible crear el pedido'));
			}	
		}
	}

	public function nuevo_pedido()
	{
		$data = array(
			'cliente'=>$this->pedidos_mdl->buscar_cliente(),
			'articulos' =>$this->pedidos_mdl->buscar_articulo(),
			'numero_pedido' =>$this->pedidos_mdl->id_pedido()->id_pedido + 1,
		);
		$this->load->view('pedidos/pendientes',$data);
	}

}