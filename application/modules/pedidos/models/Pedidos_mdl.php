<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos_mdl extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function buscar_cliente()
	{
		$this->db->select('id_cliente, nombre');
		$query = $this->db->get('clientes');
		return $query->result();
	}

	function buscar_articulo()
	{
		$this->db->select('id_articulo, referencia');
		$query = $this->db->get('articulos');
		return $query->result();
	}

	function ingresar_pedido($data)
	{
		$query = $this->db->insert('pedidos',$data);
		if($this->db->affected_rows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function id_pedido()
	{
		$this->db->select('id_pedido');
		$this->db->order_by('id_pedido','desc');
		$this->db->limit(1);
		$query = $this->db->get('pedidos');
		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return 0;
		}
	}

	//inserta los items correspondientes a una factura en db.
	function save_items_pedido($data)
	{
		$this->db->insert_batch('articulos_pedidos', $data);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
	}

}