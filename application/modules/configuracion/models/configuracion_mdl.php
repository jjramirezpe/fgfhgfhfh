<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion_mdl extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/*comprobar numero de factura*/
	function get_num_factura($data)
	{
		$this->db->select('numero_factura');
		$this->db->where('numero_factura',$data);
		$query = $this->db->get('facturas');
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/*Guardar los datos de la empresa*/
	function guardar_datos_empresa($data)
	{
	
		$this->db->replace('datos_empresa',$data);

		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function datos_empresa()
	{
		$query = $this->db->get('datos_empresa');
		return $query->row();
	}

}