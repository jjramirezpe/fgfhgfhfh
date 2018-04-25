<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('clientes_mdl');
		$this->load->helper('url');
	}

	public function index()
	{
		$this->load->view('clientes/index');
	}

	public function ajax_list()
	{
		$list = $this->clientes_mdl->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $person) {
			$no++;
			$row = array();
			$row[] = $person->id_cliente;
			$row[] = $person->nombre;
			$row[] = $person->nit;
			$row[] = $person->direccion;
			$row[] = $person->telefono;
			//$row[] = '';

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("clientes/detalle_usuario/").$person->id_cliente.'" title="Ver"><i class="glyphicon glyphicon-eye-open"></i></a>
			<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Editar" onclick="edit_person('."'".$person->id_cliente."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_person('."'".$person->id_cliente."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->clientes_mdl->count_all(),
						"recordsFiltered" => $this->clientes_mdl->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_edit($id)
	{
		$data = $this->clientes_mdl->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'nombre' => $this->input->post('nombre'),
				'nit' => $this->input->post('nit'),
				'direccion' => $this->input->post('direccion'),
				'ciudad' => $this->input->post('ciudad'),
				'email' => $this->input->post('email'),
				'telefono' => $this->input->post('telefono'),
				'condiciones' => $this->input->post('condiciones'),
				'monto' => $this->input->post('monto')
			);
		$insert = $this->clientes_mdl->save($data);
		echo json_encode(array("status" => TRUE, "msj" => 'Registro Creado con exito'));
	}


	public function ajax_update()
	{
		$this->_validate_update();
		$data = array(
				'nombre' => $this->input->post('nombre'),
				//'nit' => $this->input->post('nit'),
				'direccion' => $this->input->post('direccion'),
				'ciudad' => $this->input->post('ciudad'),
				'email' => $this->input->post('email'),
				'telefono' => $this->input->post('telefono'),
				'condiciones' => $this->input->post('condiciones'),
				'monto' => $this->input->post('monto')
				
			);
		$this->clientes_mdl->update(array('id_cliente' => $this->input->post('id_cliente')), $data);
		echo json_encode(array("status" => TRUE, "msj" => 'Registro actualizado con exito'));
	}


	public function ajax_delete($id)
	{
		$this->clientes_mdl->delete_by_id($id);
		echo json_encode(array("status" => TRUE, "msj" => 'Registro Eliminado con exito'));
	}


		/*valida el ingreso de un cliente*/
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('nombre') == '')
		{
			$data['inputerror'][] = 'nombre';
			$data['error_string'][] = 'Ingrese un nombre';
			$data['status'] = FALSE;
		}


		if($this->input->post('nit') == '')
		{
			$data['inputerror'][] = 'nit';
			$data['error_string'][] = 'Ingrese un nit';
			$data['status'] = FALSE;
		}

		if($this->clientes_mdl->get_by_nit($this->input->post('nit')) == TRUE)
		{
			$data['inputerror'][] = 'nit';
			$data['error_string'][] = 'El usuario ya existe en la Base de datos';
			$data['status'] = FALSE;
		}

		if($this->input->post('direccion') == '')
		{
			$data['inputerror'][] = 'direccion';
			$data['error_string'][] = 'Ingrese una direccion ';
			$data['status'] = FALSE;
		}

		if($this->input->post('ciudad') == '')
		{
			$data['inputerror'][] = 'ciudad';
			$data['error_string'][] = 'Ingrese una ciudad ';
			$data['status'] = FALSE;
		}

		/*if($this->input->post('Email') == '')
		{
			$data['inputerror'][] = 'email';
			$data['error_string'][] = 'Ingrese un Email ';
			$data['status'] = FALSE;
		}*/

		if($this->input->post('telefono') == '')
		{
			$data['inputerror'][] = 'telefono';
			$data['error_string'][] = 'Ingrese un telefono';
			$data['status'] = FALSE;
		}

		if($this->input->post('condiciones') == '')
		{
			$data['inputerror'][] = 'condiciones';
			$data['error_string'][] = 'Ingrese una condiciones de pago ';
			$data['status'] = FALSE;
		}

		if($this->input->post('monto') == '')
		{
			$data['inputerror'][] = 'monto';
			$data['error_string'][] = 'Ingrese un monto de credito ';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}


	/*valida la actualizacion de un usuario*/
	private function _validate_update()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('nombre') == '')
		{
			$data['inputerror'][] = 'nombre';
			$data['error_string'][] = 'Ingrese un nombre';
			$data['status'] = FALSE;
		}


		/*if($this->input->post('nit') == '')
		{
			$data['inputerror'][] = 'nit';
			$data['error_string'][] = 'Ingrese un nit';
			$data['status'] = FALSE;
		}

		if($this->clientes_mdl->get_by_nit($this->input->post('nit')) == TRUE)
		{
			$data['inputerror'][] = 'nit';
			$data['error_string'][] = 'El usuario ya existe en la Base de datos';
			$data['status'] = FALSE;
		}*/

		if($this->input->post('direccion') == '')
		{
			$data['inputerror'][] = 'direccion';
			$data['error_string'][] = 'Ingrese una direccion ';
			$data['status'] = FALSE;
		}

		if($this->input->post('ciudad') == '')
		{
			$data['inputerror'][] = 'ciudad';
			$data['error_string'][] = 'Ingrese una ciudad ';
			$data['status'] = FALSE;
		}

		/*if($this->input->post('Email') == '')
		{
			$data['inputerror'][] = 'email';
			$data['error_string'][] = 'Ingrese un Email ';
			$data['status'] = FALSE;
		}*/

		if($this->input->post('telefono') == '')
		{
			$data['inputerror'][] = 'telefono';
			$data['error_string'][] = 'Ingrese un telefono';
			$data['status'] = FALSE;
		}

		if($this->input->post('condiciones') == '')
		{
			$data['inputerror'][] = 'condiciones';
			$data['error_string'][] = 'Ingrese una condiciones de pago ';
			$data['status'] = FALSE;
		}

		if($this->input->post('monto') == '')
		{
			$data['inputerror'][] = 'monto';
			$data['error_string'][] = 'Ingrese un monto de credito ';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function detalle_usuario($id)
	{

		$data['cliente'] = $this->clientes_mdl->get_by_id($id);
		$data['balance'] = $this->clientes_mdl->get_balance($id);
		$data['total_facturado'] = $this->clientes_mdl->get_total_facturado($id);
		$data['notas'] = $this->clientes_mdl->get_by_note($id);
		$data['facturas'] = $this->clientes_mdl->get_vigentes($id);


		$this->load->view('clientes/detalle-usuario', $data);

		/*carga las facturas para un usuario segun su id*/
	}

	public function add_note($id)
	{
		$data = array(

			'id_cliente' => $id,
			'nota' => $this->input->post('nota')
		);

		$insert = $this->clientes_mdl->save_notes($data);
		redirect('clientes/detalle_usuario/'.$id.'','refresh');

	}


}