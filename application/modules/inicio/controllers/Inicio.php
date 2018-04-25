<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('inicio/inicio_mdl');
	}

	public function index()
	{
		$data = array(

			'total_dia'=>$this->inicio_mdl->sales_by_day(),
			'total_dia_credito'=>$this->inicio_mdl->sales_by_day_credit(),
			'total_dia_contado'=>$this->inicio_mdl->sales_by_day_contado(),
			'ingresos_dia'=>$this->inicio_mdl->ver_ingresos_dia(),
			'egresos_dia'=>$this->inicio_mdl->ver_egresos_dia(),
			'empresa'=>$this->inicio_mdl->ver_nombre_empresa(),
			'fact_vencidas'=>$this->inicio_mdl->ver_fact_vencidas()->valor,
			'fact_vigentes'=>$this->inicio_mdl->ver_fact_pendientes()->valor,
			'articulos_inv'=>$this->inicio_mdl->total_articulos_inventario()->valor,
			'valor_inventario'=>$this->inicio_mdl->total_inventario()->valor

		);
		$this->load->view('inicio/inicio',$data);
	}


	/*************************************metodos reporte de venta***************************************/

    /*ventas por dia*/
	public function ventas_por_dia()
	{
		$data = $this->inicio_mdl->sales_by_day();
		if($data != false)
		{
			$result = $this->inicio_mdl->sales_by_day()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}
		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}

	/*ventas dia a credito*/
	public function ventas_dia_credito()
	{
		$data = $this->inicio_mdl->sales_by_day_credit();
		if($data != false)
		{
			$result = $this->inicio_mdl->sales_by_day_credit()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}
		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}

	/*ventas dia al contado*/
	public function ventas_dia_contado()
	{
		$data = $this->inicio_mdl->sales_by_day_contado();
		if($data != false)
		{
			$result = $this->inicio_mdl->sales_by_day_contado()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}
		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}



	/********************************************************/////////////*

	/*ventas por mes*/
	public function ventas_por_mes()
	{
		$data = $this->inicio_mdl->sales_by_mes();
		if($data != false)
		{
			$result = $this->inicio_mdl->sales_by_mes()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}
		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}


	/*ventas mes a credito*/
	public function ventas_mes_credito()
	{
		$data = $this->inicio_mdl->sales_by_mes_credit();
		if($data != false)
		{
			$result = $this->inicio_mdl->sales_by_mes_credit()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}
		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}


	/*ventas mes al contado*/
	public function ventas_mes_contado()
	{
		$data = $this->inicio_mdl->sales_by_mes_contado();
		if($data != false)
		{
			$result = $this->inicio_mdl->sales_by_mes_contado()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}
		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}


	/**********************************************************////////////////////////*/

	/*ventas por mes*/
	public function ventas_por_anio()
	{
		$data = $this->inicio_mdl->sales_by_anio();
		if($data != false)
		{
			$result = $this->inicio_mdl->sales_by_anio()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}
		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}


	/*ventas mes a credito*/
	public function ventas_anio_credito()
	{
		$data = $this->inicio_mdl->sales_by_anio_credit();
		if($data != false)
		{
			$result = $this->inicio_mdl->sales_by_anio_credit()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}
		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}


	/*ventas mes al contado*/
	public function ventas_anio_contado()
	{
		$data = $this->inicio_mdl->sales_by_anio_contado();
		if($data != false)
		{
			$result = $this->inicio_mdl->sales_by_anio_contado()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}
		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}


	/****************************fin metodos reporte de ventas*************************/


	/*****************************metoso reporte de gastos*****************************/

	public function ingresos_dia()
	{
		$data = $this->inicio_mdl->ver_ingresos_dia();
		if($data != false)
		{
			$result = $this->inicio_mdl->ver_ingresos_dia()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}

		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}

	}

	public function ingresos_mes()
	{
		$data = $this->inicio_mdl->ver_ingresos_mes();
		if($data != false)
		{
			$result = $this->inicio_mdl->ver_ingresos_mes()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}

		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}

	public function ingresos_anio()
	{
		$data = $this->inicio_mdl->ver_ingresos_anio();
		if($data != false)
		{
			$result = $this->inicio_mdl->ver_ingresos_anio()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}

		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}

	public function egresos_dia()
	{
		$data = $this->inicio_mdl->ver_egresos_dia();
		if($data != false)
		{
			$result = $this->inicio_mdl->ver_egresos_dia()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}

		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}

	public function egresos_mes()
	{
		$data = $this->inicio_mdl->ver_egresos_mes();
		if($data != false)
		{
			$result = $this->inicio_mdl->ver_egresos_mes()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}

		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}

	public function egresos_anio()
	{
		$data = $this->inicio_mdl->ver_egresos_anio();
		if($data != false)
		{
			$result = $this->inicio_mdl->ver_egresos_anio()->valor;
			echo json_encode(array('status'=>true, 'result'=>$result));
		}

		else
		{
			echo json_encode(array('status'=>false, 'result'=>'no hay datos'));
		}
	}



	function prueba()
	{
		echo substr(date('Y/m/d'),0,4);
		echo $this->inicio_mdl->ver_fact_vencidas()->valor;
	}
}