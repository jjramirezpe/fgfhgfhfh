<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banco extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	$this->load->model('banco_mdl');
		$this->load->helper('url');
	}

	/*muestra menu de banco y transacciones realizadas*/
	public function index()
	{
		$this->load->view('banco/index');
	}

/***********gestion de cuentas del sistem**************************************************a*/
	public function ajax_list_cuentas()
	{
			$list = $this->banco_mdl->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $person) {
				$no++;
				$row = array();
				$row[] = $person->id_cuenta;
				$row[] = $person->nombre;
				$row[] = $person->codigo;
				$row[] = number_format($person->saldo,0,'.',',');
				
				$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="editar Cuenta" onclick="edit_person('."'".$person->id_cuenta."'".')"><span class="glyphicon glyphicon-pencil"></span></a>
					  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar Cuenta" onclick="delete_person('."'".$person->id_cuenta."'".')"><i class="glyphicon glyphicon-trash"></i></a><a class="btn btn-sm btn-default" target="_blank" href="'.base_url('banco/editar_saldo/'.$person->id_cuenta).'" title="Editar saldo"><span class="glyphicon glyphicon-edit"></a>';
				
				$data[] = $row;
			}

			$output = array(
							"draw" => $_POST['draw'],
							"recordsTotal" => $this->banco_mdl->count_all(),
							"recordsFiltered" => $this->banco_mdl->count_filtered(),
							"data" => $data,
					);
			//output to json format
			echo json_encode($output);

	}

	/*añadir nueva cuenta*/
	public function ajax_add_cuenta()
	{
		$this->_validate_cuenta();
		$data = array(
				'nombre' => $this->input->post('nombre'),
				'codigo' => $this->input->post('codigo'),
				'saldo' => $this->input->post('saldo'),
			);
		$insert = $this->banco_mdl->save($data);
		echo json_encode(array("status" => TRUE, "msj" => 'Cuenta de banco Creada con exito'));
	}
	/*editar cuenta*/
	public function ajax_edit_cuenta($id)
	{
		$data = $this->banco_mdl->get_by_id($id);
		echo json_encode($data);
	}


	public function ajax_update_cuenta()
	{
		$this->_validate_cuenta_update();
		$data = array(
				'nombre' => $this->input->post('nombre'),
				'codigo' => $this->input->post('codigo')
				
			);
		if($this->input->post('id_cuenta')==1)
		{
			echo json_encode(array('status'=>false,'msj'=>'No se puede editar la caja general del sistema'));
		}
		else
		{
			$this->banco_mdl->update(array('id_cuenta' => $this->input->post('id_cuenta')), $data);
			echo json_encode(array("status" => TRUE, "msj" => 'Cuenta actualizado con exito'));
		}
		
	}


	private function _validate_cuenta()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('nombre') == '')
		{
			$data['inputerror'][] = 'nombre';
			$data['error_string'][] = 'Ingrese unnombre para la cuenta de banco';
			$data['status'] = FALSE;
		}


		if($this->input->post('codigo') == '')
		{
			$data['inputerror'][] = 'codigo';
			$data['error_string'][] = 'Ingrese un codigo para la cuenta; puede ser el número de cuenta';
			$data['status'] = FALSE;
		}

		if($this->input->post('saldo') == '')
		{
			$data['inputerror'][] = 'saldo';
			$data['error_string'][] = 'Ingrese un saldo parala cuenta; si no tiene saldo en la cuenta ingrese 0';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}


	private function _validate_cuenta_update()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('nombre') == '')
		{
			$data['inputerror'][] = 'nombre';
			$data['error_string'][] = 'Ingrese unnombre para la cuenta de banco';
			$data['status'] = FALSE;
		}


		if($this->input->post('codigo') == '')
		{
			$data['inputerror'][] = 'codigo';
			$data['error_string'][] = 'Ingrese un codigo para la cuenta; puede ser el número de cuenta';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	/************************************************************************/

	public function nueva_cuenta()
	{
		$this->load->view('banco/nueva-cuenta');
	}

	public function ver_cuentas()
	{
		$this->load->view('banco/ver-cuentas');
	}

	/*muestra la vista para pagar un gasto*/
	public function pagar_gasto()
	{
		$data['cuentas'] = $this->banco_mdl->get_cuentas();
		$data['facturas'] = $this->banco_mdl->get_gastos_pendientes();
		$this->load->view('banco/pagar-gasto',$data);
	}

	/*muestra vista para cobro de facturas*/
	public function cobrar_factura()
	{
		$data['cuentas'] = $this->banco_mdl->get_cuentas();
		$data['facturas'] = $this->banco_mdl->get_facturas_pendientes();
		$this->load->view('banco/cobrar-factura',$data);
	}

	/*ingresa el pago de una factura en el sistema*/
	public function pago_factura()
	{
		/*poner estado factura en 0*/
		$id_factura = $this->input->post('id');
		$id_cuenta = $this->input->post('cuenta');
		$query = $this->banco_mdl->update_estado_factura($id_factura);
		if($query == true)
		{
			/*sumar valor a cuenta Bancaria*/
			$saldo = $this->banco_mdl->get_cuentas_by_id($id_cuenta)->saldo;
			$cuenta = $this->banco_mdl->get_cuentas_by_id($id_cuenta)->nombre;
			$nuevo_saldo = $saldo + $this->input->post('tot');
			$update_cuenta = $this->banco_mdl->update_saldo_cuenta_cobro($id_cuenta,$nuevo_saldo);

			/*crear transaccion*/
			if($update_cuenta = true)
			{
				$data = array(
					'descripcion' => 'cobro por factura N°'.$this->input->post('num'),
					'cuenta' => $cuenta,
					'importe' => $this->input->post('tot'),
					'tipo' => 1

				);
				$result = $this->banco_mdl->crear_transaccion($data);
			}

			echo json_encode(array('status'=>true, 'msj'=>'transaccion realizada con exito'));
		}
		else
		{
			echo json_encode(array('status'=>false, 'msj'=>'Error al realizar la transacción.'));
		}
	}



	/*busca factura para el cobro*/
	public function buscar_factura()
	{
		if($this->input->is_ajax_request() && isset($_POST['factura']))
		{
			$id_factura = $this->input->post('factura');

			$query = $this->banco_mdl->get_factura_by_id($id_factura);

			if($query != false)
				{
					$userdata = $query;
					$data['status']	= true;	
					$data['result'] = $userdata;
					
				}
				else
				{
					$data['status']	= false;	
					$data['result'] = '<span class="glyphicon glyphicon-remove text-danger"></span> La factura no existe';
				}
				echo json_encode($data);
		}
	}


	/*busca gasto  para el pago*/
	public function buscar_gasto()
	{
		if($this->input->is_ajax_request() && isset($_POST['factura']))
		{
			$id_factura = $this->input->post('factura');

			$query = $this->banco_mdl->get_gasto_by_id($id_factura);

			if($query != false)
				{
					$userdata = $query;
					$data['status']	= true;	
					$data['result'] = $userdata;
					
				}
				else
				{
					$data['status']	= false;	
					$data['result'] = '<span class="glyphicon glyphicon-remove text-danger"></span> La factura no existe';
				}
				echo json_encode($data);
		}
	}


	/*ingresa el pago de un gasto al sistema*/
	public function pago_gasto()
	{
		$id_gasto = $this->input->post('id');
		$id_cuenta = $this->input->post('cuenta');
		$saldo = $this->banco_mdl->get_cuentas_by_id($id_cuenta)->saldo;
		$valor = $this->input->post('tot');

		if($valor > $saldo)
		{
			echo json_encode(array('status'=>false, 'msj'=>'La cuenta seleccionalda no posee fondos suficientes para realizar esta transacción.'));
		}

		else
		{	
			/*poner estado factura en 0*/
			$query = $this->banco_mdl->update_estado_gasto($id_gasto,$valor);
			if($query == true)
			{
				/*restar valor a cuenta Bancaria*/
				
				$cuenta = $this->banco_mdl->get_cuentas_by_id($id_cuenta)->nombre;
				$nuevo_saldo = ($saldo - $this->input->post('tot'));
				$update_cuenta = $this->banco_mdl->update_saldo_cuenta_cobro($id_cuenta,$nuevo_saldo);

				/*crear transaccion*/
				if($update_cuenta = true)
				{
					$data = array(
						'descripcion' => 'Pago gasto a  '.$this->input->post('proveedor').' Factura N°'.$this->input->post('num'),
						'cuenta' => $cuenta,
						'importe' => $this->input->post('tot'),
						'tipo' => 0

					);
					$result = $this->banco_mdl->crear_transaccion($data);
				}

				echo json_encode(array('status'=>true, 'msj'=>'transaccion realizada con exito'));
			}
			else
			{
				echo json_encode(array('status'=>false, 'msj'=>'Error al realizar la transacción.'));
			}
		}

		
	}



}