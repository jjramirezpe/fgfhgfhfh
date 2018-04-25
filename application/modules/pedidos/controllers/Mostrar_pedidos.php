<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mostrar_pedidos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('mostrar_pedidos_mdl');
		$this->load->helper('url');
	}

	public function ajax_list()
	{
		$list = $this->mostrar_pedidos_mdl->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $person) {
			$no++;
			$row = array();
			$row[] = $person->id_pedido;
			$row[] = $person->facha;
			$row[] = $person->fecha_e;
			$row[] = $person->nombre;
			//$row[] = '';

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("clientes/detalle_usuario/").$person->id_pedido.'" title="Ver"><i class="glyphicon glyphicon-eye-open"></i></a>
			<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Editar" onclick="edit_person('."'".$person->id_pedido."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_person('."'".$person->id_pedido."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->mostrar_pedidos_mdl->count_all(),
						"recordsFiltered" => $this->mostrar_pedidos_mdl->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

}