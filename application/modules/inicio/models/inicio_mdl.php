<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio_mdl extends CI_Model {


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		date_default_timezone_set('America/Bogota');
	}


	function ver_nombre_empresa()
	{
		$this->db->select('nombre');
		$query = $this->db->get('datos_empresa');
		return $query->row();
	}


	function ver_fact_vencidas()
	{
		$this->db->select_sum('total','valor');
		$this->db->where('fecha_vencimiento < ',date('m/d,Y'));
		$this->db->where('estado', '0');
		$query = $this->db->get('facturas');
		return $query->row();
	}

	function ver_fact_pendientes()
	{
		$this->db->select_sum('total','valor');
		$this->db->where('fecha_vencimiento > ',date('m/d,Y'));
		$this->db->where('estado', '0');
		$query = $this->db->get('facturas');
		return $query->row();
	}

	function total_articulos_inventario()
	{
		$this->db->select_sum('cantidad','valor');
		$query = $this->db->get('articulos');
		return $query->row();
	}

	function total_inventario()
	{
		$query = $this->db->query('SELECT sum(`cantidad` * `costo`) AS `valor` FROM articulos');
		
		return $query->row();
	}

	


	/*reportes para el inicio del sistema */
	/*ventas*/

	/*ventas dia*/
	function sales_by_day()
	{
		
		$this->db->select_sum('total','valor');
		$this->db->where('fecha_factura',date('m/d/Y'));
		$query = $this->db->get('facturas');
		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
		
	}

	function sales_by_day_credit()
	{
		$this->db->select_sum('total','valor');
		$this->db->where('fecha_factura',date('m/d/Y'));
		$this->db->where('estado',0);
		$query = $this->db->get('facturas');
		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
		
	}

	function sales_by_day_contado()
	{
		$this->db->select_sum('total','valor');
		$this->db->where('fecha_factura',date('m/d/Y'));
		$this->db->where('estado',1);
		$query = $this->db->get('facturas');
		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
		
	}

	/*ventas mes*/
	function sales_by_mes()
	{
		$this->db->select_sum('total','valor');
		$this->db->like('fecha_factura',substr(date('m/d/Y'),0,3),'after');
		$query = $this->db->get('facturas');
		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
		
	}

	/*ventas mes credito*/
	function sales_by_mes_credit()
	{
		$this->db->select_sum('total','valor');
		$this->db->where('estado',0);
		$this->db->like('fecha_factura',substr(date('m/d/Y'),0,3),'after');
		$query = $this->db->get('facturas');
		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
		
	}


	/*ventas mes contado*/
	function sales_by_mes_contado()
	{
		$this->db->select_sum('total','valor');
		$this->db->where('estado',1);
		$this->db->like('fecha_factura',substr(date('m/d/Y'),0,3),'after');
		$query = $this->db->get('facturas');
		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
		
	}






	/*ventas año*/
	function sales_by_anio()
	{
		$this->db->select_sum('total','valor');
		$this->db->like('fecha_factura',substr(date('m/d/Y'),6));
		$query = $this->db->get('facturas');
		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
		
	}

	/*ventas año credito*/
	function sales_by_anio_credit()
	{
		$this->db->select_sum('total','valor');
		$this->db->where('estado',0);
		$this->db->like('fecha_factura',substr(date('m/d/Y'),6));
		$query = $this->db->get('facturas');
		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
		
	}


	/*ventas año contado*/
	function sales_by_anio_contado()
	{
		$this->db->select_sum('total','valor');
		$this->db->where('estado',1);
		$this->db->like('fecha_factura',substr(date('m/d/Y'),6));
		$query = $this->db->get('facturas');
		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
		
	}


	/*reportes banco*/

	/*banco por dia*/
	function ver_ingresos_dia()
	{
		$this->db->select_sum('importe', 'valor');
		$this->db->where('tipo',1);
		$this->db->like('fecha',substr(date('Y-m-d'),0,10),'after');
		$query = $this->db->get('transacciones');

		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}

	}

	function ver_ingresos_mes()
	{
		$this->db->select_sum('importe', 'valor');
		$this->db->where('tipo',1);
		$this->db->like('fecha',substr(date('Y-m-d'),0,7),'after');
		$query = $this->db->get('transacciones');

		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}

	}

	function ver_ingresos_anio()
	{
		$this->db->select_sum('importe', 'valor');
		$this->db->where('tipo',1);
		$this->db->like('fecha',substr(date('Y-m-d'),0,4),'after');
		$query = $this->db->get('transacciones');

		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}

	}


	function ver_egresos_dia()
	{
		$this->db->select_sum('importe', 'valor');
		$this->db->where('tipo',0);
		$this->db->like('fecha',substr(date('Y-m-d'),0,10),'after');
		$query = $this->db->get('transacciones');

		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}

	}

	function ver_egresos_mes()
	{
		$this->db->select_sum('importe', 'valor');
		$this->db->where('tipo',0);
		$this->db->like('fecha',substr(date('Y-m-d'),0,7),'after');
		$query = $this->db->get('transacciones');

		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}

	}

	function ver_egresos_anio()
	{
		$this->db->select_sum('importe', 'valor');
		$this->db->where('tipo',0);
		$this->db->like('fecha',substr(date('Y-m-d'),0,7),'after');
		$query = $this->db->get('transacciones');

		if($this->db->affected_rows()>0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}

	}
}