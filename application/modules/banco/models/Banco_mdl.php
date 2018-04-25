<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banco_mdl extends CI_Model {


	var $table = 'cuentas_bancarias';
	var $column_order = array('nombre','codigo','saldo',null); //set column field database for datatable orderable
	var $column_search = array('nombre','codigo','saldo'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('id_cuenta' => 'desc'); // default order 


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


		private function _get_datatables_query()
	{
		
		$this->db->from($this->table);

		$i = 0;
	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}


	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	/*GUARDA UNA CUENTA EN LA DB*/
	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_cuenta',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	/*elimina una cuenta del sistema*/
	public function delete_by_id($id)
	{
		$this->db->where('id_cuenta', $id);
		$this->db->delete($this->table);
	}


	/*consultar cuentas del sistema*/
	public function get_cuentas()
	{
		$query = $this->db->get('cuentas_bancarias');
		return $query->result();
	}

	/*consultar cuentas por id*/
	public function get_cuentas_by_id($id)
	{
		$query = $this->db->get_where('cuentas_bancarias',array('id_cuenta'=>$id));
		return $query->row();
	}

	/*facturas pendientes de pago*/
	public function get_facturas_pendientes()
	{
		$query = $this->db->get_where('lista_facturas',array('estado'=>0));
		return $query->result();
	}
	/*gastos pendientes de pago*/
	public function get_gastos_pendientes()
	{
		$query = $this->db->get_where('lista_gastos',array('estado'=>0));
		return $query->result();
	}

	/*facturas pendientes de pago por id*/
	public function get_factura_by_id($id)
	{
		$tabla = 'lista_facturas';

		$query = $this->db->get_where($tabla, array('id_factura' => $id));
		return $query->row();
	}
	/*gastos pendientes de pago por id*/
	public function get_gasto_by_id($id)
	{
		$tabla = 'lista_gastos';

		$query = $this->db->get_where($tabla, array('id_gasto' => $id));
		return $query->row();
	}

	/*actualizar estado de factura a pagado : 1*/
	public function update_estado_factura($id_factura)
	{
		$this->db->where('id_factura', $id_factura);
		$this->db->update('facturas', array('estado'=>1));
		if($this->db->affected_rows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	/*actualizar estado de gasto a pagado : 1*/
	public function update_estado_gasto($id_gasto,$valor)
	{
		$this->db->where('id_gasto', $id_gasto);
		$this->db->update('gastos', array('estado'=>1, 'pagado'=>$valor,'pendiente'=>0));
		if($this->db->affected_rows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/*actualizar el saldo de una cuenta bancaria despues de cobrar una factura*/

	public function update_saldo_cuenta_cobro($id_cuenta,$saldo)
	{
		$this->db->where('id_cuenta', $id_cuenta);
		$this->db->update('cuentas_bancarias', array('saldo'=>$saldo));
		if($this->db->affected_rows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/*ingresar una nueva transaccion*/
	public function crear_transaccion($data)
	{
		$this->db->insert('transacciones',$data);
	}

}