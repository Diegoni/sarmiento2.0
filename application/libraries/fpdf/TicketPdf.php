<?php
class TicketPdf extends FPDF {
	protected $w = 2;
	protected $textypos = 2;
	protected $nextLineSize = 4;
	protected $setX = 2;
	protected $addNextLine = 0;

	public function __construct() {
		parent::__construct(
			'P','mm', [48, 210]
		);
	}

	function setValues($presupuesto, $rengloPresupuesto, $cliente, $empresa, $factura, $vendedores){
		$vendedor = 'Vendedor: ';
		if($vendedores){
			foreach ($vendedores as $rowVendedor) {
				if($rowVendedor->id_vendedor == $presupuesto->id_vendedor){
					$vendedor .= $rowVendedor->vendedor;
				}
			}
		}

		$descuento = ($presupuesto->descuento > 0) ? 'Descuento: '.$presupuesto->descuento.'%' : '';
		$divLine = '--------------------------------------------------';

		$datosEmpresa = [
			'',
			'C.U.I.T.',
			'ingresos brutos',
			'direccion telefono',
			'C.P. 5600 - SAN RAFAEL - MENDOZA',
			'DEFENSA AL CONSUMIDOR: 0800-222-6678',
			'Inicio de actividades',
			'IVA RESPONSABLE ocinewdescriptor',
			$divLine
		];

		$datosFactura = [
			'ORIGINAL',
			'TIQUET FACTURA "A" NRO 0008-000000005212',
			'Fecha 21/05/20 Hora 11:50:04',
			$divLine
		];

		$datosCliente = [
			'Vendedor ',
			'Descuento ',
			$divLine
		];

		$datosExtra = [
			'Rodriguez Ricardo',
			'C.U.I.T.:',
			'IVA RESPONSABLE INSCRIPTO',
			'ZAMARBIDE 1305 - ENTRE LAS CALLES LAS VIRGE',
			$divLine
		];

		$datosCabecera = [
			'CANT/PRECIO        IVA',
			'DESCRIPCION                            PRECIO NETO',
		];



		$this->AddPage();
		$this->SetFont('Arial','B',8);
		$this->setY(2);
		$this->setX($this->setX);
		$this->Cell($this->w, $this->textypos, $empresa->empresa);
		$this->SetFont('Courier','',4);
		$this->addLineData($datosEmpresa);
		$this->addLineData($datosFactura);
		$this->addLineData($datosCliente);
		$this->addLineData($datosExtra);
		$this->addLineData($datosCabecera);

		$total =0;
		if($rengloPresupuesto){
			foreach ($rengloPresupuesto as $renglon) {
				$precio = number_format( round($renglon->precio / $renglon->cantidad, 2),2,",",".");
				$precioNeto = number_format($renglon->precio ,2,",",".");
				$descripcion = strtoupper(substr($renglon->descripcion, 0,12));
				$cantidad = $renglon->cantidad;

				$datosDetalle = [
					'    '.$cantidad.' / '.$precio.'        (21.00)',
					$descripcion.'                            '.$precioNeto,
				];
				$this->addLineData($datosDetalle);
			}
		}
		// $textypos=$off;
		//
		// $this->setX(2);
		// $this->Cell(5,$textypos, $divLine);
		// $textypos+=$nextLineSize;
		// $this->Cell(5,$textypos,"TOTAL: " );
		// $this->setX(38);
		// $this->Cell(5,$textypos,"$ ".number_format($presupuesto->monto,2,",","."),0,0,"R");
		//
		// $this->setX(2);
		// $this->SetFont('Arial','',5);
		// $textypos+=$nextLineSize;
		// $this->Cell(5,$textypos,'GRACIAS POR TU COMPRA ');

		$this->output();
	}

	function addLineData($arrayDatos){
		foreach ($arrayDatos as $dato) {
			$this->textypos += $this->nextLineSize + $this->addNextLine;
			$this->setX($this->setX);
			$this->Cell($this->w, $this->textypos, $dato);
		}
	}
}
?>
