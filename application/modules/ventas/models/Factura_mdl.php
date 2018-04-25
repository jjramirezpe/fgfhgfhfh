<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Factura_mdl extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_by_code($ref)
	{
		$tabla = 'articulos';

		$query = $this->db->get_where($tabla, array('referencia' => $ref));
		return $query->row();
	}

	public function get_by_cliente($nit)
	{
		$tabla = 'clientes';

		$query = $this->db->get_where($tabla, array('nit' => $nit));
		return $query->row();
	}


	/*guardar datos de una factura en db

	1. comprueba que el numero de factura no exista
	4. comprueba que el cliente exista
	6. guardar datos */

	function comprobar_factura($num)/*solicita numero de factura en form*/
	{
		$query = $this->db->get_where('facturas', array('numero_factura' => $num));
		if($query->num_rows() > 0 )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	public function comprobar_cliente($id)/*solicita id del cliente*/
	{
		
			$query = $this->db->get_where('clientes', array('id_cliente' => $id));
			if($query->num_rows() > 0 )
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		
	}

	public function guardar_factura($data)
	{
		$this->db->insert('facturas', $data);
		if($this->db->affected_rows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	/*comprueba la cantidad en el stock*/
	function cantidad_stock($id)
	{
		$tabla = 'articulos';

		$this->db->select('cantidad');
		$this->db->where('id_articulo', $id);
		$this->db->from($tabla);
		$query = $this->db->get();
		return $query->row();
	}

	//inserta los items correspondientes a una factura en db.
	function save_items_fact($data)
	{
		$this->db->insert_batch('articulos_factura', $data);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
	}


	function get_estado_cueta($id)
	{
		//$this->db->select_sum('total','valor');
		$this->db->select('nombre,numero_factura,fecha_factura,fecha_vencimiento,total,estado');
		$this->db->where('id_cliente',$id);
		$this->db->where('estado', 0);
		$result = $this->db->get('lista_facturas');
		return $result->result();
	}

	/*nombre cliente por id*/
	function get_nombre_cliente($id)
	{
		$this->db->select('id_cliente,nombre');
		$this->db->where('id_cliente',$id);
		$result = $this->db->get('clientes');
		return $result->row();
	}

	/*total adeudado por un cliente por id*/
	function get_total_amount_cliente($id)
	{
		//$this->db->select_sum('total','valor');
		$this->db->select_sum('total','valor');
		$this->db->where('id_cliente',$id);
		$this->db->where('estado', 0);
		$result = $this->db->get('lista_facturas');
		return $result->row();
	}

	/*actualiza los datos de generales de la factura*/
	function actualizar_factura($id_factura, $data_factura)
	{
		$this->db->where('id_factura', $id_factura);
		$query = $this->db->update('facturas', $data_factura);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	//inserta los items que se actualizan en una factura
	function save_items_fact_actualizados($items)
	{
		$this->db->insert_batch('articulos_factura_actualizados', $items);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function check_ref($ref, $id_factura)
	{
		$query = $this->db->get_where('articulos_factura', array('referencia' => $ref, 'id_factura' => $id_factura));
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	/*funcion para mostrar el id de la ultima factura creada*/
	function select_last_id()
	{
		$this->db->select('id_factura');
		$this->db->order_by('id_factura','DESC');
		$this->db->limit(1);
		$query = $this->db->get('facturas');
		return $query->row();
	}

}