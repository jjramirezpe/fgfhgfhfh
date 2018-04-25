<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventario extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('inventario_mdl');
		$this->load->helper('url');
	}

	public function index()
	{
		$this->load->view('inventario/index');
	}

	public function ajax_list()
	{
		$list = $this->inventario_mdl->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $person) {
			$no++;
			$row = array();
			$row[] = $person->id_articulo;
			$row[] = $person->referencia;
			$row[] = $person->descripcion;
			$row[] = $person->cantidad;
			$row[] = $person->precio;
			$row[] = $person->costo;
			//$row[] = '';

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("inventario/detalle_articulo/").$person->id_articulo.'" title="Ver"><i class="glyphicon glyphicon-eye-open"></i></a>
			<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Editar" onclick="edit_person('."'".$person->id_articulo."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_person('."'".$person->id_articulo."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->inventario_mdl->count_all(),
						"recordsFiltered" => $this->inventario_mdl->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_edit($id)
	{
		$data = $this->inventario_mdl->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'referencia' => $this->input->post('referencia'),
				'descripcion' => $this->input->post('descripcion'),
				'cantidad' => $this->input->post('cantidad'),
				'precio' => $this->input->post('precio'),
				'costo' => $this->input->post('costo')
			);
		$insert = $this->inventario_mdl->save($data);
		echo json_encode(array("status" => TRUE, "msj" => 'Registro Creado con exito'));
	}


	public function ajax_update()
	{
		$this->_validate_update();
		$data = array(
				//'referencia' => $this->input->post('referencia'),
				'descripcion' => $this->input->post('descripcion'),
				//'cantidad' => $this->input->post('cantidad'),
				'precio' => $this->input->post('precio'),
				'costo' => $this->input->post('costo')
				
			);
		$this->inventario_mdl->update(array('id_articulo' => $this->input->post('id_articulo')), $data);
		echo json_encode(array("status" => TRUE, "msj" => 'Registro actualizado con exito'));
	}


	public function ajax_delete($id)
	{
		$this->inventario_mdl->delete_by_id($id);
		echo json_encode(array("status" => TRUE, "msj" => 'Registro Eliminado con exito'));
	}


		/*valida el ingreso de un cliente*/
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('referencia') == '')
		{
			$data['inputerror'][] = 'referencia';
			$data['error_string'][] = 'Ingrese una referencia para el articulo';
			$data['status'] = FALSE;
		}

		if($this->inventario_mdl->get_by_ref($this->input->post('referencia')) == TRUE)
		{
			$data['inputerror'][] = 'referencia';
			$data['error_string'][] = 'La referencia ya existe en el inventario.';
			$data['status'] = FALSE;
		}

		if($this->input->post('descripcion') == '')
		{
			$data['inputerror'][] = 'descripcion';
			$data['error_string'][] = 'Ingrese una descripción para el articulo ';
			$data['status'] = FALSE;
		}

		if($this->input->post('cantidad') == '')
		{
			$data['inputerror'][] = 'cantidad';
			$data['error_string'][] = 'Ingrese una cantidad valida ';
			$data['status'] = FALSE;
		}

		if($this->input->post('cantidad') == 0)
		{
			$data['inputerror'][] = 'cantidad';
			$data['error_string'][] = 'Ingrese una cantidad valida ';
			$data['status'] = FALSE;
		}

		/*if($this->input->post('Email') == '')
		{
			$data['inputerror'][] = 'email';
			$data['error_string'][] = 'Ingrese un Email ';
			$data['status'] = FALSE;
		}*/

		if($this->input->post('precio') == '')
		{
			$data['inputerror'][] = 'precio';
			$data['error_string'][] = 'Ingrese un precio de venta para el articulo';
			$data['status'] = FALSE;
		}

		if($this->input->post('costo') == '')
		{
			$data['inputerror'][] = 'costo';
			$data['error_string'][] = 'Ingrese un costo para el articulo';
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

		/*if($this->input->post('referemcia') == '')
		{
			$data['inputerror'][] = 'referencia';
			$data['error_string'][] = 'Ingrese una referencia para el articulo';
			$data['status'] = FALSE;
		}

		if($this->clientes_mdl->get_by_ref($this->input->post('referencia')) == TRUE)
		{
			$data['inputerror'][] = 'referencia';
			$data['error_string'][] = 'La referencia ya existe en el inventario.';
			$data['status'] = FALSE;
		}*/

		if($this->input->post('descripcion') == '')
		{
			$data['inputerror'][] = 'descripcion';
			$data['error_string'][] = 'Ingrese una descripción para el articulo ';
			$data['status'] = FALSE;
		}

		/*if($this->input->post('cantidad') == '')
		{
			$data['inputerror'][] = 'cantidad';
			$data['error_string'][] = 'Ingrese una cantidad valida ';
			$data['status'] = FALSE;
		}

		if($this->input->post('cantidad') == 0)
		{
			$data['inputerror'][] = 'cantidad';
			$data['error_string'][] = 'Ingrese una cantidad valida ';
			$data['status'] = FALSE;
		}*/

		/*if($this->input->post('Email') == '')
		{
			$data['inputerror'][] = 'email';
			$data['error_string'][] = 'Ingrese un Email ';
			$data['status'] = FALSE;
		}*/

		if($this->input->post('precio') == '')
		{
			$data['inputerror'][] = 'precio';
			$data['error_string'][] = 'Ingrese un precio de venta para el articulo';
			$data['status'] = FALSE;
		}

		if($this->input->post('precio') == 0)
		{
			$data['inputerror'][] = 'precio';
			$data['error_string'][] = 'Ingrese un precio de venta valido para el articulo';
			$data['status'] = FALSE;
		}

		if($this->input->post('costo') == '')
		{
			$data['inputerror'][] = 'costo';
			$data['error_string'][] = 'Ingrese un costo para el articulo';
			$data['status'] = FALSE;
		}

		if($this->input->post('costo') == 0)
		{
			$data['inputerror'][] = 'costo';
			$data['error_string'][] = 'Ingrese un costo valido para el articulo';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function detalle_articulo($id)
	{	$data['articulo'] = $this->inventario_mdl->get_by_id($id);
		$data['detalle'] = $this->inventario_mdl->detalle_update($id);
		$this->load->view('inventario/detalle-articulo',$data);
	}


	public function actualizar_stock($id)
	{
		if($this->input->post('transaccion') == 1)
		{
			$cantidad = $this->inventario_mdl->get_by_id($id)->cantidad - $this->input->post('cantidad');
		}

		else if($this->input->post('transaccion') == 2)
		{
			$cantidad = $this->inventario_mdl->get_by_id($id)->cantidad + $this->input->post('cantidad');
		}

		else
		{
			$cantidad = 0;
		}
		$data_stock = array('cantidad'=>$cantidad);
		$result = $this->inventario_mdl->update_stock($id, $data_stock);
		
		if($result > 0)
		{
			$data_detalle_stock = array(
				'id_articulo' =>  $this->inventario_mdl->get_by_id($id)->id_articulo,
				'transaccion' => $this->input->post('transaccion'),
				'cantidad' => $this->input->post('cantidad'),
				'comentario' => $this->input->post('comentario'),
				'usuario' => 'userdemo'
			);

			$insert = $this->inventario_mdl->insert_detalle_stock($data_detalle_stock);
			if($insert > 0)
			{
				echo '<script>alertify.alert("La cantidad del articulo se actualizo con exito")</script>';
				redirect('/inventario/detalle_articulo/'.$this->inventario_mdl->get_by_id($id)->id_articulo, 'refresh');
			}

			if($insert == 0)
			{
				echo '<script>alert("No fue posible Actualizar la cantidad del articulo")</script>';
				redirect('/inventario/detalle_articulo/'.$this->inventario_mdl->get_by_id($id)->id_articulo, 'refresh');
			}
		}
	}


}