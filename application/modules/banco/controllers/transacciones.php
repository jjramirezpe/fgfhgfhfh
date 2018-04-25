<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transacciones extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('transacciones_mdl');
		$this->load->helper('url');
	}

	public function ajax_list()
	{
			$list = $this->transacciones_mdl->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $person) {
				$no++;
				$row = array();
				$row[] = $person->id_transaccion;
				$row[] = $person->descripcion;
				$row[] = $person->fecha;
				$row[] = $person->cuenta;
				if($person->tipo == 0)
				{
					$row[] = '<span class="text-danger">$ - '.number_format($person->importe,0,'.',',').'</span>';
				}
				else
				{
					$row[] = '<span class="text-success">$'.number_format($person->importe,0,'.',',').'</span>';
				}
				
				
				
				
				$data[] = $row;
			}

			$output = array(
							"draw" => $_POST['draw'],
							"recordsTotal" => $this->transacciones_mdl->count_all(),
							"recordsFiltered" => $this->transacciones_mdl->count_filtered(),
							"data" => $data,
					);
			//output to json format
			echo json_encode($output);

	}
}
