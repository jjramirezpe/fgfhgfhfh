<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*condiciones para factura
  El cliente debe existir
  El articulo debe existir
  El stock debe ser mayor a la cantidad a facturar
*/

class Ventas extends CI_Controller {

	//date_default_timezone_set('Asia/Kolkata');

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ventas_mdl');
		$this->load->helper('url');
	}

	public function index()
	{
		
		$this->load->view('ventas/index');
	}


	public function pagadas()
	{
		$this->load->view('ventas/pagadas');
	}

	public function vencidas()
	{
		$this->load->view('ventas/vencidas');
	}

	public function nueva_factura()
	{
		$data['terminos'] = $this->ventas_mdl->get_terminos();
		$data['vendedor'] = $this->ventas_mdl->get_vendedor();
		$data['datos_factura'] = $this->ventas_mdl->get_conciones_factura();
		$this->load->view('ventas/form-factura', $data);

	}


	/*carga lista de todas las facturas pagadas 1, pendientes 0 y vencidas calculo por fecha*/

	public function ver_facturas()
	{
		
		$list = $this->ventas_mdl->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $person) {
			$no++;
			$row = array();
			$row[] = '<a href="'.base_url('clientes/detalle_usuario').'/'.$person->id_cliente.'">'.$person->nombre.'</a>';
			$row[] = '<a href="'.base_url('ventas/mostrar_factura/detalle').'/'.$person->id_factura.'">'.$person->numero_factura.'</a>';
			$row[] = $person->fecha_factura;
			$hoy = strtotime(date('m/d/Y'));
			$vencimiento = strtotime($person->fecha_vencimiento);
			if($hoy > $vencimiento and $person->estado == 0 )
			{
				$row[] = '<span class="text-danger"><strong>'.$person->fecha_vencimiento.'<strong></span>';
			}
			elseif($person->estado == 1)
			{
				$row[] = '<span class="text-success"><strong>'.$person->fecha_vencimiento.'<strong></span>';
			}	
			else
			{
				$row[] = '<span class="text-primary"><strong>'.$person->fecha_vencimiento.'<strong></span>';
			}	
			$row[] = $person->vendedor;
			$row[] = '$'.number_format($person->total,'0','.','.');

			if($person->estado == 0 and  $hoy > $vencimiento)
			{
				$row[] = '<span class="text-danger"><strong>Vencida<strong></span>';
			}

			elseif($person->estado == 0)
			{
				$row[] = '<span class="text-primary"><strong>Pendiente<strong></span>';
			}


			else
			{
				$row[] = '<span class="text-success"><strong>Pagada<strong></span>';
			}
			

			//$row[] = '';

			//add html for action
			/*$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("clientes/detalle_usuario/").$person->id_cliente.'" title="Ver"><i class="glyphicon glyphicon-eye-open"></i></a>
			<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Editar" onclick="edit_person('."'".$person->id_cliente."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_person('."'".$person->id_cliente."'".')"><i class="glyphicon glyphicon-trash"></i></a>';*/
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->ventas_mdl->count_all(),
						"recordsFiltered" => $this->ventas_mdl->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	/*muestra las facturas pagadas*/
	public function ver_facturas_pagadas()
	{
		
		$list = $this->ventas_mdl->get_datatables_pagadas();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $person) {
			$no++;
			$row = array();
			$row[] = '<a href="'.base_url('clientes/detalle_usuario').'/'.$person->id_cliente.'">'.$person->nombre.'</a>';
			$row[] = '<a href="'.base_url('ventas/mostrar_factura/detalle').'/'.$person->id_factura.'">'.$person->numero_factura.'</a>';
			$row[] = $person->fecha_factura;
			$row[] = '<span class="text-success"><strong>'.$person->fecha_vencimiento.'<strong></span>';
			$row[] = $person->vendedor;
			$row[] = '$'.number_format($person->total,'0','.','.');

			
				$row[] = '<span class="text-success"><strong>Pagada<strong></span>';
			
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->ventas_mdl->count_all(),
						"recordsFiltered" => $this->ventas_mdl->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	/*muestra las facturas vencidad*/
		public function ver_facturas_vencidas()
	{
		
		$list = $this->ventas_mdl->get_datatables_vencidas();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $person) {
			$no++;
			$row = array();
			
				$row[] = '<a href="'.base_url('clientes/detalle_usuario').'/'.$person->id_cliente.'">'.$person->nombre.'</a>';
				$row[] = '<a href="'.base_url('ventas/mostrar_factura/detalle').'/'.$person->id_factura.'">'.$person->numero_factura.'</a>';
				$row[] = $person->fecha_factura;
				$row[] = '<span class="text-danger"><strong>'.$person->fecha_vencimiento.'<strong></span>';
				$row[] = $person->vendedor;
				$row[] = $person->total;
				$row[] = '<span class="text-danger"><strong>Vencida<strong></span>';
			
			

			//$row[] = '';

			//add html for action
			/*$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("clientes/detalle_usuario/").$person->id_cliente.'" title="Ver"><i class="glyphicon glyphicon-eye-open"></i></a>
			<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Editar" onclick="edit_person('."'".$person->id_cliente."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_person('."'".$person->id_cliente."'".')"><i class="glyphicon glyphicon-trash"></i></a>';*/
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->ventas_mdl->count_all(),
						"recordsFiltered" => $this->ventas_mdl->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

}