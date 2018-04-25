<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mostrar_factura_mdl extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}
	/*consulta los datos de la factura segun id_factura*/
	public function get_datos_factura($id)
	{
		$query = $this->db->get_where('facturas',array('id_factura'=>$id));
		return $query->row();
	}

	public function get_datos_cliente($id_cliente)
	{
		$query = $this->db->get_where('clientes',array('id_cliente'=>$id_cliente));
		return $query->row();
	}

	public function detalle_articulo($id_cliente, $id_factura)
	{
		$this->db->select('*');
		$this->db->where('id_factura',$id_factura);
		$this->db->where('id_cliente', $id_cliente);
		$query = $this->db->get('articulos_factura');
		return $query->result();
	}

	public function get_datos_vendedor($id)
	{
		$this->db->select('nombre');
		$this->db->where('id_usuario', $id);
		$result = $this->db->get('usuarios');
		return $result->row();
	}

	/*cargar datos de empresa para la factura*/
	function datos_empresa()
	{
		$query = $this->db->get('datos_empresa');
		return $query->row();
	}

}