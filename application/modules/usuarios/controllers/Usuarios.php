<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('usuarios_mdl');
		$this->load->helper('url');
	}

	public function index()
	{
		$this->load->view('usuarios/index');
	}

	public function ajax_list()
	{
		$list = $this->usuarios_mdl->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $person) {
			$no++;
			$row = array();
			$row[] = $person->id_usuario;
			$row[] = $person->nombre;
			$row[] = $person->nit;
			$row[] = $person->direccion;
			$row[] = $person->telefono;
			if($person->rol == 1)
			{
				$row[] = 'Administrador';
			}
			if($person->rol == 2)
			{
				$row[] = 'Vendedor';
			}

			if($person->estado == 1)
			{
				$row[] = 'Activo';
			}
			if($person->estado == 2)
			{
				$row[] = 'Inactivo';
			}
			//$row[] = '';

			//add html for action
			$row[] = '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Editar" onclick="edit_person('."'".$person->id_usuario."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_person('."'".$person->id_usuario."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->usuarios_mdl->count_all(),
						"recordsFiltered" => $this->usuarios_mdl->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->usuarios_mdl->get_by_id($id);
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
				'telefono' => $this->input->post('telefono'),
				'rol' => $this->input->post('rol'),
				'estado' => $this->input->post('estado'),
				'nombre_usuario' => $this->input->post('nombre_usuario'),
				'clave' => $this->input->post('clave'),
			);
		$insert = $this->usuarios_mdl->save($data);
		echo json_encode(array("status" => TRUE, "msj" => 'Registro Creado con exito'));
	}

	public function ajax_update()
	{
		$this->_validate_update();
		$data = array(
				'nombre' => $this->input->post('nombre'),
				//'nit' => $this->input->post('nit'),
				'direccion' => $this->input->post('direccion'),
				'telefono' => $this->input->post('telefono'),
				'rol' => $this->input->post('rol'),
				'estado' => $this->input->post('estado'),
				'nombre_usuario' => $this->input->post('nombre_usuario'),
				'clave' => $this->input->post('clave'),
			);
		$this->usuarios_mdl->update(array('id_usuario' => $this->input->post('id_usuario')), $data);
		echo json_encode(array("status" => TRUE, "msj" => 'Registro actualizado con exito'));
	}

	public function ajax_delete($id)
	{
		$this->usuarios_mdl->delete_by_id($id);
		echo json_encode(array("status" => TRUE, "msj" => 'Registro Eliminado con exito'));
	}


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

		if($this->usuarios_mdl->get_by_nit($this->input->post('nit')) == TRUE)
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

		if($this->input->post('telefono') == '')
		{
			$data['inputerror'][] = 'telefono';
			$data['error_string'][] = 'Ingrese un telefono ';
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

		if($this->input->post('rol') == '')
		{
			$data['inputerror'][] = 'rol';
			$data['error_string'][] = 'Ingrese un rol';
			$data['status'] = FALSE;
		}

		/*if($this->input->post('rol') > 1 || $this->input->post('rol') != '2')
		{
			$data['inputerror'][] = 'rol';
			$data['error_string'][] = 'Ingrese un rol valido';
			$data['status'] = FALSE;
		}*/

		if($this->input->post('estado') == '')
		{
			$data['inputerror'][] = 'estado';
			$data['error_string'][] = 'Ingrese un estado';
			$data['status'] = FALSE;
		}

		/*if($this->input->post('estado') != 1 or $this->input->post('estado') != 2)
		{
			$data['inputerror'][] = 'estado';
			$data['error_string'][] = 'Ingrese un estado valido';
			$data['status'] = FALSE;
		}*/

		if($this->input->post('nombre_usuario') == '')
		{
			$data['inputerror'][] = 'nombre_usuario';
			$data['error_string'][] = 'Ingrese un nombre de usuario';
			$data['status'] = FALSE;
		}

		if($this->input->post('clave') == '')
		{
			$data['inputerror'][] = 'clave';
			$data['error_string'][] = 'Ingrese una clave';
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


		if($this->input->post('rol') == '')
		{
			$data['inputerror'][] = 'rol';
			$data['error_string'][] = 'Ingrese un rol';
			$data['status'] = FALSE;
		}

		/*if($this->input->post('rol') != 0 or $this->input->post('rol') != 1)
		{
			$data['inputerror'][] = 'rol';
			$data['error_string'][] = 'Ingrese un rol valido';
			$data['status'] = FALSE;
		}*/

		if($this->input->post('estado') == '')
		{
			$data['inputerror'][] = 'estado';
			$data['error_string'][] = 'Ingrese un estado';
			$data['status'] = FALSE;
		}

		/*if($this->input->post('estado') != 0 or $this->input->post('estado') != 1)
		{
			$data['inputerror'][] = 'estado';
			$data['error_string'][] = 'Ingrese un estado valido';
			$data['status'] = FALSE;
		}*/

		if($this->input->post('nombre_usuario') == '')
		{
			$data['inputerror'][] = 'nombre_usuario';
			$data['error_string'][] = 'Ingrese un nombre de usuario';
			$data['status'] = FALSE;
		}

		if($this->input->post('clave') == '')
		{
			$data['inputerror'][] = 'clave';
			$data['error_string'][] = 'Ingrese una clave';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}

	}


}