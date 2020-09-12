<?php
class TicketPdf extends FPDF {
	const LETRA_RESPONSABLE = 'A';
	const LETRA_PRESUPUESTO = 'X';
	const CBTE_TIPO_A = '1';
	const CBTE_TIPO_B = '6';

	protected $w = 4;
	protected $textypos = 4;
	protected $nextLineSize = 6;
	protected $setX = 2;
	protected $addNextLine = 0;
	protected $longLine = 40;
	protected $longTotal = 12;
	protected $fontSize = 8;

	private $condicionPago = [
		'1' => 'Contado',
		'2' => 'Cuenta Corriente',
		'3' => 'Tarjeta',
	];

	public function __construct() {
		parent::__construct(
			'P','mm', [80, 400]
		);
	}

	function setValues($presupuesto, $rengloPresupuesto, $cliente, $empresa, $factura, $vendedor){
		$descuento = ($presupuesto->descuento > 0) ? 'Descuento: '.$presupuesto->descuento.'%' : '';
		$divLine = '';
		for ($i=0; $i < $this->longLine; $i++) {
			$divLine .= '-';
		}

		// DATOS de la empresa
		$datosEmpresa[] = '';

		if ($presupuesto->facturado == 1) {
			$datosEmpresa[] = 'C.U.I.T.: '.$empresa->cuit;
			$datosEmpresa[] = 'INGRESOS BRUTOS: '.$empresa->ingreso_brutos;
			$datosEmpresa[] = 'DEFENSA AL CONSUMIDOR: 0800-222-6678';
			$datosEmpresa[] = 'INICIO DE ACTIVIDADES: '.date("d-m-Y", strtotime($empresa->inicio_actividad));
			$datosEmpresa[] = 'IVA: '.$empresa->iva;
		}

		$datosEmpresa[] = 'TELEFONO: '.$empresa->telefono;
		$datosEmpresa[] = 'DIRECCION: '.$empresa->domicilio;
		$datosEmpresa[] = 'C.P.: '.$empresa->codigo_postal.' - '.$empresa->departamento.' - '.$empresa->provincia;
		$datosEmpresa[] = $divLine;

		// DATOS de la factura
		if ($presupuesto->facturado == 1) {
			$letra = ($factura->cbte_tipo == self::CBTE_TIPO_A) ? 'A' : 'B';
			$nroFactura = str_pad($factura->pto_vta, 4, "0", STR_PAD_LEFT).'-'.str_pad($factura->cbte_desde, 8, "0", STR_PAD_LEFT);
			$datosFactura = [
				'ORIGINAL',
				'TIQUET FACTURA "'.$letra.'" NRO '.$nroFactura,
				'FECHA: '.date("d-m-Y", strtotime($factura->cbte_fch)),
				$divLine
			];
		}

		$datosCliente = [
			'CLIENTE: '.strtoupper($cliente->alias),
		];

		if ($presupuesto->facturado == 1) {
			$datosCliente[] = 'C.U.I.T.: '.$cliente->cuil;
			$datosCliente[] = 'IVA: '.($cliente->descripcion ? $cliente->descripcion : '-');
			$datosCliente[] = 'DIRECCION: '.($cliente->direccion ? $cliente->direccion : '-');
			$datosCliente[] = $divLine;
		}

		if($presupuesto->descuento > 0){
			$datosExtra[] = 'DESCUENTO: '.$presupuesto->descuento.'%';
		}
		$datosExtra[] = 'VENDEDOR: '.$vendedor;
		$datosExtra[] = "CONDICION VENTA: ".$this->condicionPago[$presupuesto->tipo];
		$datosExtra[] = $divLine;

		$charLongPrice = 3 + strlen('CANT/PRECIO') + strlen('IVA');
		$spacePrice = '';
		for ($i=0; $i < $this->longLine - $charLongPrice ; $i++) {
			$spacePrice .= ' ';
		}

		$charLongDesc = strlen('DESCRIPCION') + strlen('PRECIO NETO');
		$spaceDesc = '';
		for ($i=0; $i < $this->longLine - $charLongDesc ; $i++) {
			$spaceDesc .= ' ';
		}
		$datosCabecera = [
			'DESCRIPCION'.$spaceDesc.'PRECIO NETO',
			'   '.'CANT/PRECIO'.$spacePrice.'IVA',
		];

		$this->AddPage();
		$this->SetFont('Arial','B',12);
		$this->setY(4);
		$this->setX($this->setX);
		$this->Cell($this->w, $this->textypos, strtoupper($empresa->empresa), 0,0, 'C');
		$this->SetFont('Courier','',$this->fontSize);
		$this->addLineData($datosEmpresa);
		if($presupuesto->facturado == 1){
			$this->addLineData($datosFactura);
		}
		$this->addLineData($datosCliente);
		$this->addLineData($datosExtra);
		$this->addLineData($datosCabecera);

		$total = 0;
		$countLines = 0;
		if($rengloPresupuesto){
			foreach ($rengloPresupuesto as $renglon) {
				$countLines += 1;

				$precio = number_format( round($renglon->precio / $renglon->cantidad, 2),2,",",".");
				$precioNeto = number_format($renglon->precio ,2,",",".");
				$descripcion = strtoupper($renglon->descripcion);
				$cantidad = $renglon->cantidad;

				if(strlen($descripcion) < ($this->longLine - $this->longTotal)){
					$descripcion = str_pad($descripcion, $this->longLine - strlen($precioNeto) , " ", STR_PAD_RIGHT);
				} else if(strlen($descripcion) > ($this->longLine - $this->longTotal)){
					$precioNeto = '   '.$precioNeto;
					$descripcion = substr($descripcion, 0, $this->longLine - strlen($precioNeto));
				}

				$cantidad = str_pad($cantidad, 6, " ", STR_PAD_LEFT	);
				$precio = str_pad($precio, 20, " ", STR_PAD_RIGHT);

				$datosDetalle = [
					$descripcion.$precioNeto,
					$cantidad.' / '.$precio.'(21.00)',
				];
				$this->addLineData($datosDetalle);

				if( $countLines == 30) {
					$countLines = 0;
					$this->AddPage();
					$this->setY(4);
					$this->textypos = 4;
					$this->addLineData($datosCabecera, 'C');
				}
			}
		}

		$precio = "$ ".number_format($presupuesto->monto,2,",",".");
		$descripcion = str_pad('TOTAL', $this->longLine - strlen($precio), " ", STR_PAD_RIGHT);
		$datosDetalle = [
			'',
			$descripcion.$precio,
			''
		];
		$this->SetFont('Courier','B',$this->fontSize);
		$this->addLineData($datosDetalle);
		$this->SetFont('Arial','',$this->fontSize);
		$this->addLineData(['GRACIAS POR TU COMPRA '], 'C');
		$this->output();
	}

	function addLineData($arrayDatos, $aling = 'L'){
		foreach ($arrayDatos as $dato) {
			$this->textypos += $this->nextLineSize + $this->addNextLine;
			$this->setX($this->setX);
			$this->Cell($this->w,$this->textypos,$dato,0,0, $aling);
		}
	}
}
?>
