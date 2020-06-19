<?php
class TicketPdf extends FPDF {
	const LETRA_RESPONSABLE = 'A';
	const LETRA_PRESUPUESTO = 'X';
	const CBTE_TIPO_A = '1';
	const CBTE_TIPO_B = '6';

	protected $w = 2;
	protected $textypos = 2;
	protected $nextLineSize = 4;
	protected $setX = 2;
	protected $addNextLine = 0;
	protected $longLine = 50;
	protected $longTotal = 12;

	public function __construct() {
		parent::__construct(
			'P','mm', [48, 210]
		);
	}

	function setValues($presupuesto, $rengloPresupuesto, $cliente, $empresa, $factura, $vendedor){
		$descuento = ($presupuesto->descuento > 0) ? 'Descuento: '.$presupuesto->descuento.'%' : '';
		$divLine = '';
		for ($i=0; $i < $this->longLine; $i++) {
			$divLine .= '-';
		}

		$datosEmpresa = [
			'',
			'C.U.I.T.: '.$empresa->cuit,
			'INGRESOS BRUTOS: '.$empresa->ingreso_brutos,
			'TELEFONO: '.$empresa->telefono,
			'DIRECCION: '.$empresa->domicilio,
			'C.P.: '.$empresa->codigo_postal.' - '.$empresa->departamento.' - '.$empresa->provincia,
			'DEFENSA AL CONSUMIDOR: 0800-222-6678',
			'INICIO DE ACTIVIDADES: '.date("d-m-Y", strtotime($empresa->inicio_actividad)),
			'IVA: '.$empresa->iva,
			$divLine
		];

		if($presupuesto->facturado == 1){
			$letra = ($factura->cbte_tipo == self::CBTE_TIPO_A) ? 'A' : 'B';
			$nroFactura = str_pad($factura->pto_vta, 4, "0", STR_PAD_LEFT).'-'.str_pad($factura->cbte_desde, 8, "0", STR_PAD_LEFT);
			$datosFactura = [
				'ORIGINAL',
				'TIQUET FACTURA "'.$letra.'" NRO '.$nroFactura,
				'FECHA: '.date("d-m-Y", strtotime($factura->cbte_fch)),
				$divLine
			];
		}

		if($presupuesto->descuento > 0){
			$datosExtra[] = 'DESCUENTO: '.$presupuesto->descuento.'%';
		}
		$datosExtra[] = 'VENDEDOR: '.$vendedor;
		$datosExtra[] = $divLine;

		// TODO: Falta terminar
		$datosCliente = [
			strtoupper($cliente->alias),
			'C.U.I.T.: '.$cliente->cuil,
			'IVA RESPONSABLE INSCRIPTO',
			'ZAMARBIDE 1305 - ENTRE LAS CALLES LAS VIRGE',
			$divLine
		];

		$datosCabecera = [
			'   CANT/PRECIO                 IVA',
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
				$descripcion = strtoupper($renglon->descripcion);
				$cantidad = $renglon->cantidad;

				if(strlen($descripcion) < ($this->longLine - $this->longTotal)){
					$descripcion = str_pad($descripcion, strlen($descripcion) - $this->longLine - $this->longTotal, " ", STR_PAD_RIGHT);
				} else if(strlen($descripcion) > ($this->longLine - $this->longTotal)){
					$descripcion = substr($descripcion, 0, $this->longLine - $this->longTotal);
				}

				$precioNeto = str_pad($precioNeto, $this->longTotal, " ", STR_PAD_LEFT);
				$cantidad = str_pad($cantidad, 6, " ", STR_PAD_LEFT	);
				$precio = str_pad($precio, 20, " ", STR_PAD_RIGHT);

				$datosDetalle = [
					$cantidad.' / '.$precio.'(21.00)',
					$descripcion.$precioNeto,
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
