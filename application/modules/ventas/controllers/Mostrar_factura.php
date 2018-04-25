<?php
class Mostrar_factura extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('mostrar_factura_mdl');
		$this->load->model('clientes/clientes_mdl');
		$this->load->helper('url');
		$this->load->library('pdf');
	}

	public function detalle($id)
	{
		$id_cliente = $this->mostrar_factura_mdl->get_datos_factura($id)->id_cliente;
		$data['saldo_cliente'] = $this->clientes_mdl->get_balance($id_cliente);
		$data['datos_factura'] = $this->mostrar_factura_mdl->get_datos_factura($id);
		$data['datos_cliente'] = $this->mostrar_factura_mdl->get_datos_cliente($id_cliente);
		$data['articulos_factura'] = $this->mostrar_factura_mdl->detalle_articulo($id_cliente, $id);
		$this->load->view('ventas/Mostrar-factura',$data);
	}

	public function imprimir($id)
	{
		$id_cliente = $this->mostrar_factura_mdl->get_datos_factura($id)->id_cliente;
		$id_vendedor = $this->mostrar_factura_mdl->get_datos_factura($id)->vendedor;
		$factura = $this->mostrar_factura_mdl->get_datos_factura($id);
		$articulos = $this->mostrar_factura_mdl->detalle_articulo($id_cliente, $id);
		$cliente = $this->mostrar_factura_mdl->get_datos_cliente($id_cliente);
		$vendedor = $this->mostrar_factura_mdl->get_datos_vendedor($id_vendedor);
        $empresa = $this->mostrar_factura_mdl->datos_empresa();


		/*creacion del PDF*/
		ob_start();
		/*Se crea un objeto de la clase Pdf, recuerda que la clase Pdf  heredó todos las variables y métodos de fpdf*/
    	$this->pdf = new Pdf();
   		 // Agregamos una página
   		 $this->pdf->AddPage();
    	// Define el alias para el número de página que se imprimirá en el pie
    	$this->pdf->AliasNbPages();

    	/* Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado*/
    	$this->pdf->SetTitle("Factura numero ".$factura->numero_factura);
    	$this->pdf->SetLeftMargin(10);
    	$this->pdf->SetRightMargin(10);
    	$this->pdf->SetFillColor(200,200,200);


    	

        //datos del almacen
        $this->pdf->SetTextColor(0,0,0);
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->Cell(40,0,utf8_decode($empresa->nombre),0,0);

        $this->pdf->SetFont('Arial','B',12);
        $this->pdf->SetTextColor(255,0,0);
        $this->pdf->Cell(150,0,'Factura '.$factura->numero_factura ,0,1,'R');

        $this->pdf->SetTextColor(0,0,0);
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->Cell(40,10,utf8_decode($empresa->direccion),0,1);

        if($empresa->tipo == 1)
        {
            $this->pdf->Cell(40,0,utf8_decode('Régimen Común'),0,1);
        }
        else
        {
            $this->pdf->Cell(40,0,utf8_decode('Régimen simplificado'),0,1);
        }
        
        $this->pdf->Cell(40,10,utf8_decode('Medellín Colombia'),0,1);
        $this->pdf->Cell(40,0,'Telf: '.$empresa->telefono,0,1);
         $this->pdf->SetFont('Arial', 'B', 7);
         $this->pdf->MultiCell(0,4,utf8_decode($empresa->resolucion),0,0);
    	$this->pdf->Ln(10);

    	//datos del cliente y fecha
         $this->pdf->SetFont('Arial', '', 10);
    	 $this->pdf->Cell(40,0,'Cliente',0,1);
    	 $this->pdf->Cell(40,10,utf8_decode($cliente->nombre),0,0);
        
         $this->pdf->SetFont('Arial', '', 10);
    	 $this->pdf->Cell(150,10,utf8_decode('Fecha Factura: '.$factura->fecha_factura),0,1,'R');
    	 $this->pdf->Cell(40,0,utf8_decode('Nit: '.$cliente->nit),0,0);
    	 $this->pdf->Cell(150,0,utf8_decode('Vencimiento: '.$factura->fecha_vencimiento),0,1,'R');
    	 $this->pdf->Cell(40,10,utf8_decode($cliente->direccion),0,0);
         if($factura->estado == 1)
         {
            $this->pdf->Cell(150,10,utf8_decode('Cantidad Adeudada: $ 0'),0,1,'R');
         }
         else
         {
             $this->pdf->Cell(150,10,utf8_decode('Cantidad Adeudada: $'.number_format($factura->total,0,'.','.')),0,1,'R');
         }
    	 $this->pdf->Cell(40,0,utf8_decode($cliente->telefono),0,0);

    	 $this->pdf->Cell(150,0,utf8_decode('Vendedor: '.$vendedor->nombre),0,0,'R');
    	 $this->pdf->Ln(10);

    	//articulos para la factura
    	 $this->pdf->SetFont('Arial', 'B', 10);
    	 $this->pdf->Cell(15,7,'Articulo','B',0,'C','0');
    	 $this->pdf->Cell(90,7,'Descripcion','B',0,'C','0');
    	 $this->pdf->Cell(25,7,'Cant','B',0,'C','0');
    	 $this->pdf->Cell(30,7,'Precio','B',0,'C','0');
    	 $this->pdf->Cell(30,7,'Total','B',0,'C','0');
    	 $this->pdf->ln(10);
    	 
    	 $this->pdf->SetFont('Arial', '', 10);
    	  $this->pdf->SetDrawColor(231,232,232);
    	$x = 1;
    	foreach ($articulos as $item)
    	{
    		$this->pdf->Cell(15,5,$item->referencia,'B',0,'C');
    		$this->pdf->Cell(90,5,$item->descripcion,'B',0,'C');
    		$this->pdf->Cell(25,5,$item->cantidad,'B',0,'C');
    		$this->pdf->Cell(30,5,number_format($item->precio,0,'.','.'),'B',0,'C');
    		$this->pdf->Cell(30,5,number_format($item->total,0,'.','.'),'B',0,'C');
    		$this->pdf->ln(6);
    	}

    	//totales para la factura
    	$this->pdf->SetFont('Arial', 'B', 10);
    	$this->pdf->ln(8);
    	
    	if($factura->iva > 0)
    	{
    		$this->pdf->Cell(155,0,'Subtotal',0,0,'R');
    		$this->pdf->Cell(20,0,'$'.number_format($factura->subtotal),0,1,'R');
    		$this->pdf->Cell(155,15,'IVA',0,0,'R');
    		$this->pdf->Cell(20,15,'$'.number_format($factura->iva),0,1,'R');
    		$this->pdf->Cell(155,0,'Total',0,0,'R');
    		$this->pdf->Cell(20,0,'$'.number_format($factura->total),0,1,'R');
    	}
    	else
    	{
    		$this->pdf->Cell(155,0,'Subtotal',0,0,'R');
    		$this->pdf->Cell(20,0,'$'.number_format($factura->subtotal),0,1,'R');
    		$this->pdf->Cell(155,15,'Total',0,0,'R');
    		$this->pdf->Cell(20,15,'$'.number_format($factura->total),0,1,'R');
    	}

    	/*Observaciones*/
    	$this->pdf->ln(2);
    	$this->pdf->SetFont('Arial', '', 10);
    	$this->pdf->Cell(40,0,'Observaciones:',0,0);
    	$this->pdf->ln(3);
    	$this->pdf->MultiCell(0,5,utf8_decode($factura->notas),0,1);


    	




    	$this->pdf->Output("Factura.pdf", 'I');
    	ob_end_flush();

	}

	
}

