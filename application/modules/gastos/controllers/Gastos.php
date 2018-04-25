<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gastos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('gastos_mdl');
		$this->load->helper('url');
	}

	public function index()
	{
		$data['proveedores'] = $this->gastos_mdl->get_proveedores();
		$this->load->view('gastos/index',$data);
	}


	public function ajax_list()
	{
		$list = $this->gastos_mdl->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $person) {
			$no++;
			$row = array();
			$row[] = $person->id_gasto;
			$row[] = $person->fecha;
			$row[] = $person->nombre;
			$row[] = $person->descripcion;
			$row[] = $person->factura;
			/*tipo de gasto 1: activo de la empresa
			2:coste de produccion
			3: otro concepto*/
			if($person->tipo == 1){$row[] = 'activo de la empresa';}
			elseif($person->tipo == 2){$row[] = 'costo produccion';}
			else{$row[] = 'otro concepto';}
			$row[] = '$'.number_format($person->valor,0,'.',',');
			$row[] = '$'.number_format($person->pagado,0,'.',',');
			$row[] = '$'.number_format($person->pendiente,0,'.',',');
			//$row[] = '';

			//add html for action
			if($person->estado == 0)
			{
				$row[] = ' <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_person('."'".$person->id_gasto."'".')"><i class="glyphicon glyphicon-trash"></i></a><a class="btn btn-sm btn-default" target="_blank" href="'.base_url('gastos/imprimir/'.$person->id_gasto).'" title="Imprimir Recibo" onclick="imprimir('."'".$person->id_gasto."'".')"><span class="glyphicon glyphicon-print"></span></a>';
			}

			else
			{
				$row[] = '<a class="btn btn-sm btn-default" target="_blank" href="'.base_url('gastos/imprimir/'.$person->id_gasto).'" title="Imprimir Recibo"><span class="glyphicon glyphicon-print"></span></a>';
			}
			
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->gastos_mdl->count_all(),
						"recordsFiltered" => $this->gastos_mdl->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'fecha' => $this->input->post('fecha'),
				'id_proveedor' => $this->input->post('proveedor'),
				'descripcion' => $this->input->post('descripcion'),
				'factura' => $this->input->post('factura'),
				'tipo' => $this->input->post('tipo'),
				'valor' => $this->input->post('valor'),
				'pagado' => '0',
				'pendiente' => $this->input->post('valor'),
				'observaciones' => $this->input->post('observaciones')
			);
		$insert = $this->gastos_mdl->save($data);
		echo json_encode(array("status" => TRUE, "msj" => 'Gasto Creado con exito'));
	}

	public function ajax_delete($id)
	{
		if(@$this->gastos_mdl->get_gasto_by_id($id)->pendiente > 0)
		{
			$this->gastos_mdl->delete_by_id($id);
			echo json_encode(array("status" => TRUE, "msj" => 'Gasto Eliminado con exito'));
		}

		else
		{
			echo json_encode(array("status" => false, "msj" => 'no se puede eliminar el gasto porque tiene un pago asociado.'));
		}

		/*$this->gastos_mdl->delete_by_id($id);
			echo json_encode(array("status" => TRUE, "msj" => 'Gasto Eliminado con exito'));*/
		
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('fecha') == '')
		{
			$data['inputerror'][] = 'fecha';
			$data['error_string'][] = 'Ingrese una fecha para el gasto';
			$data['status'] = FALSE;
		}


		if($this->input->post('proveedor') == '')
		{
			$data['inputerror'][] = 'proveedor';
			$data['error_string'][] = 'Ingrese un proveedor';
			$data['status'] = FALSE;
		}

		if($this->input->post('descripcion') == '')
		{
			$data['inputerror'][] = 'descripcion';
			$data['error_string'][] = 'Ingrese una descripcion para el gasto ';
			$data['status'] = FALSE;
		}

		if($this->input->post('factura') == '')
		{
			$data['inputerror'][] = 'factura';
			$data['error_string'][] = 'Ingrese la factura asociada con el gasto ';
			$data['status'] = FALSE;
		}

		

		if($this->input->post('tipo') == '')
		{
			$data['inputerror'][] = 'tipo';
			$data['error_string'][] = 'Ingrese el tipo de gasto';
			$data['status'] = FALSE;
		}

		if($this->input->post('valor') == '')
		{
			$data['inputerror'][] = 'valor';
			$data['error_string'][] = 'Ingrese elvalor del gasto';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}


	public function imprimir($id)
	{
		$this->load->library('pdf');

		/*datos del gasto*/
		$gasto = $this->gastos_mdl->get_gasto_by_id($id);

		/*creacion del PDF*/
		ob_start();
		/*Se crea un objeto de la clase Pdf, recuerda que la clase Pdf  heredó todos las variables y métodos de fpdf*/
    	$this->pdf = new Pdf();
   		 // Agregamos una página
   		 $this->pdf->AddPage();
    	// Define el alias para el número de página que se imprimirá en el pie
    	$this->pdf->AliasNbPages();


    	/* Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado*/
    	$this->pdf->SetTitle("Recibo pago para   ".$gasto->nombre);
    	$this->pdf->SetLeftMargin(10);
    	$this->pdf->SetRightMargin(10);
    	$this->pdf->SetFillColor(200,200,200);


    	$this->pdf->SetTextColor(0,0,0);
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->Cell(40,0,utf8_decode('LuluIris s.a.s'),0,0);

        $this->pdf->SetFont('Arial','B',12);
        $this->pdf->SetTextColor(255,0,0);
        $this->pdf->Cell(150,0,utf8_decode('Comprobante de gasto') ,0,1,'R');

        $this->pdf->SetTextColor(0,0,0);
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->Cell(40,10,utf8_decode('calle 49 Nº 51-43 Edificio colon Local 406'),0,1);
        $this->pdf->Cell(40,0,utf8_decode('Régimen simplificado'),0,1);
        $this->pdf->Cell(40,10,utf8_decode('Medellín Colombia'),0,1);
        $this->pdf->Cell(40,0,'Telf: 2514295',0,1);
    	$this->pdf->Ln(10);

    	$this->pdf->Cell(40,0,'Proveedor: '.$gasto->nombre,0,1);
    	$this->pdf->Cell(40,10,utf8_decode('Factura proveedor N°: '.$gasto->factura),0,1);
    	$this->pdf->Cell(40,10,utf8_decode('Detalles del gasto:'),0,1);
    	$this->pdf->ln(10);

    	$this->pdf->SetFont('Arial', 'B', 10);
    	$this->pdf->Cell(15,7,'Fecha Gasto','B',0,'C','0');
    	$this->pdf->Cell(100,7,'Descripcion','B',0,'C','0');
    	$this->pdf->Cell(40,7,'Tipo de gasto','B',0,'C','0');
    	$this->pdf->Cell(40,7,'Valor del gasto','B',0,'C','0');
    	$this->pdf->ln(10);

    	$this->pdf->SetFont('Arial', '', 10);
    	$this->pdf->SetDrawColor(231,232,232);

    	$this->pdf->Cell(15,5,$gasto->fecha,'B',0,'C');
    		$this->pdf->Cell(100,5,$gasto->descripcion,'B',0,'C');
    		/*tipo de gasto 1: activo, 2: costo produccion 3: otro*/
    		if($gasto->tipo == 1)
    		{
    			$this->pdf->Cell(40,5,'Activo para la empresa','B',0,'C');
    		}

    		elseif($gasto->tipo == 2)
    		{
    			$this->pdf->Cell(40,5,utf8_decode('Costo producción'),'B',0,'C');
    		}

    		else
    		{
    			$this->pdf->Cell(40,5,'Otro concepto','B',0,'C');
    		}
    		
    		$this->pdf->Cell(40,5,'$'.number_format($gasto->valor,0,'.','.'),'B',0,'C');

    		$this->pdf->ln(15);
    		$this->pdf->SetFont('Arial', 'B', 10);

    		$this->pdf->Cell(155,0,'Pagado',0,0,'R');
    		$this->pdf->Cell(20,0,'$'.number_format($gasto->pagado),0,1,'R');
    		$this->pdf->Cell(155,15,'Pendiente',0,0,'R');
    		$this->pdf->Cell(20,15,'$'.number_format($gasto->pendiente),0,1,'R');

    		$this->pdf->ln();
    		$this->pdf->SetFont('Arial', '', 10);
    		$this->pdf->Cell(40,0,'Observaciones:',0,1);

    		$this->pdf->ln(5);

    		$this->pdf->MultiCell(0,5,utf8_decode($gasto->observaciones),0,1);



    	$this->pdf->Output("Factura.pdf", 'I');
    	ob_end_flush();
	}

}
