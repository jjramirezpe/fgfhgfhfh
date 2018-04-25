<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedores_mdl extends CI_Model {

	var $table = 'proveedores';
	var $column_order = array('nombre','nit','direccion','telefono',null); //set column field database for datatable orderable
	var $column_search = array('nombre','nit'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('id_proveedor' => 'desc'); // default order 

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

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_proveedor',$id);
		$query = $this->db->get();

		return $query->row();

	}

	public function get_by_nit($nit)
	{
		$this->db->from($this->table);
		$this->db->where('nit',$nit);
		$query = $this->db->get();

		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id_proveedor', $id);
		$this->db->delete($this->table);
	}

	public function save_notes($data)
	{
		$this->db->insert('notas_proveedor', $data);
		return $this->db->insert_id();
	}

	public function get_by_note($id)
	{
		$this->db->from('notas_proveedor');
		$this->db->where('id_proveedor',$id);
		$query = $this->db->get();

		return $query->result();

	}

}
