<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('configuracion_mdl');
		$this->load->helper('url');
	}

	/*muestra menu de banco y transacciones realizadas*/
	public function index()
	{
		$data['empresa'] = $this->configuracion_mdl->datos_empresa();
		$this->load->view('configuracion/index',$data);
	}

	public function guardar_empresa()
	{
		/*verifico que numero de factura no exista*/
		$numero_fact = $this->configuracion_mdl->get_num_factura($this->input->post('numero'));

		if($numero_fact == true)
		{
			echo json_encode(array("status"=>false,"msj"=>"Ya existe una factura con el numero ".$numero_fact.", ingrese otro numero para continuar"));
		}

		else
		{
			$nombre = $this->input->post('empresa');
			$nit = $this->input->post('nit');
			$direccion = $this->input->post('direccion');
			$telefono = $this->input->post('telefono');
			$email = $this->input->post('email');
			$numero = $this->input->post('numero');
			$tipo = $this->input->post('tipo');
			$resolucion = $this->input->post('resolucion');

			$data = array(

				'nombre'=>$nombre,
				'nit'=>$nit,
				'direccion'=>$direccion,
				'telefono'=>$telefono,
				'email'=>$email,
				'numero'=>$numero,
				'tipo'=>$tipo,
				'resolucion'=>$resolucion
			);

			$insert = $this->configuracion_mdl->guardar_datos_empresa($data);

			if($insert == false)
			{
				echo json_encode(array("status"=>false,"msj"=>"No se pueden guardar los datos en este momento, intentelo de nuevo mas tarde"));
			}
			else
			{
				echo json_encode(array("status"=>true,"msj"=>"Datos Guardados con exito"));
			}
		}

		

	}

}