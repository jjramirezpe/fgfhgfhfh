<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas_mdl extends CI_Model {

	var $table = 'lista_facturas';
	var $column_order = array('nombre','numero_factura','fecha_factura','fecha_vencimiento',null); //set column field database for datatable orderable
	var $column_search = array('nombre', 'numero_factura'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('numero_factura' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
	}

	private function _get_datatables_query($estado)
	{
		
		$this->db->from($this->table);

		if($estado == 'pagadas')
		{
			$this->db->where('estado',1);
		}

		if($estado == 'vencidas')
		{
			
			$this->db->where('estado',0);
			$this->db->where('fecha_vencimiento < ', date('m/d/Y'));
		}



		

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
	/*todas*/
	function get_datatables()
	{
		$estado = '0';
		$this->_get_datatables_query($estado);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query('0');
		$query = $this->db->get();
		return $query->num_rows();
	}
	/*******todas**********/

	/*pagadas*/
	function get_datatables_pagadas()
	{
		$estado = 'pagadas';
		$this->_get_datatables_query($estado);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_pagadas()
	{
		$this->_get_datatables_query('pagadas');
		$query = $this->db->get();
		return $query->num_rows();
	}
	/*******pagadas********/

	/*vencidas*/
	function get_datatables_vencidas()
	{
		$estado = 'vencidas';
		$this->_get_datatables_query($estado);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_vencidas()
	{
		$this->_get_datatables_query('vencidas');
		$query = $this->db->get();
		return $query->num_rows();
	}
	/*******vencidas********/

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}



	/*vendedor para la factura - muestra los datos*/
	public function get_vendedor()
	{
		$query = $this->db->get_where('usuarios', array('rol' => 2));
		return $query->result();
	}
	/*condiciones para la factura - muestra los datos*/
	public function get_conciones_factura()
	{
		$query = $this->db->get('datos_empresa');
		return $query->row();
	}

	/*carga los terminos de la factura*/
	function get_terminos()
	{
		$this->db->select('resolucion');
		$query = $this->db->get('datos_empresa');
		return $query->row();
	}

}