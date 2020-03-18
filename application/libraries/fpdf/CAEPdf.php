<?php
class CAEPdf extends FPDF {
	const ESTADO_CAE = 4;
	const LETRA_RESPONSABLE = 'A';
	const LETRA_PRESUPUESTO = 'X';
	const CBTE_TIPO_A = '1';
	const CBTE_TIPO_B = '6';

	private $copy = [
		'No valido como factura',
		'ORIGINAL',
		'DUPLICADO',
		'TRIPLICADO',
	];

	private $condicionPago = [
		'1' => 'Contado',
		'2' => 'Cuenta Corriente',
		'3' => 'Tarjeta',
	];

	private $separation = 2;
	private $dataSize = 6;

	private $_presupuesto;
	private $_renglon;
	private $_cliente;
	private $_empresa;
	private $_factura;
	private $_vendedores;
	private $letra;

	function setValues($presupuesto, $rengloPresupuesto, $cliente, $empresa, $factura, $vendedores){
		$this->_presupuesto = $presupuesto;
		$this->_renglon = $rengloPresupuesto;
		$this->_cliente = $cliente;
		$this->_empresa = $empresa;
		$this->_factura = $factura;
		$this->_vendedores = $vendedores;
	}

	// Cabecera de página
	function Header() {
		if ($this->_presupuesto->estado == self::ESTADO_CAE) {
			$this->letra = ($this->_factura->cbte_tipo == self::CBTE_TIPO_A) ? 'A' : 'B';
			$comprobante = 'FACTURA';
			$copy = $this->copy[1];
		} else {
			$this->letra = self::LETRA_PRESUPUESTO;
			$comprobante = 'PRESUPUESTO';
			$copy = $this->copy[0];
		}

    // Copia
    $this->SetFont('Arial','B',15);
    $this->Cell(190,10,$copy,1,0,'C');
		$this->Ln(10);
		// Letra
		$this->SetFont('Arial','B',10);
		$this->Cell(87,15,"",0,0,'C');
		$this->SetFont('Arial','B',20);
		$this->Cell(16,16, $this->letra,1,0,'C');
		$this->SetFont('Arial','B',15);
		$this->Cell(87,16,$comprobante,0,0,'C');
		$this->Ln(16);
		// Empresa
		$this->SetFont('Arial','',10);
		$this->Cell(89,$this->dataSize,"  Razon Social: ".$this->_empresa->empresa,0,0,'L');
		$this->Cell(20,$this->dataSize,'',0,0,'C');
		$cadena = ($this->letra != self::LETRA_PRESUPUESTO) ? 'Punto de venta: '.$this->_factura->pto_vta.' Comp Nro: '.str_pad($this->_factura->cbte_desde, 8, "0", STR_PAD_LEFT) : 'NRO: '.str_pad($this->_presupuesto->id_presupuesto, 11, "0", STR_PAD_LEFT) ;
		$this->Cell(89,$this->dataSize, $cadena,0,0,'L');
		$this->Ln($this->dataSize);

		$this->Cell(89,$this->dataSize,"",0,0,'L');
		$this->Cell(20,$this->dataSize,'',0,0,'C');
		$fecha = ($this->letra != self::LETRA_PRESUPUESTO) ? $this->_factura->cbte_fch : $this->_presupuesto->fecha;
		$fecha = 'Fecha emision: '.date("d-m-Y", strtotime($fecha));
		$this->Cell(89,$this->dataSize, ($this->letra != self::LETRA_PRESUPUESTO) ? $fecha : '',0,0,'L');
		$this->Ln($this->dataSize);

		$this->Cell(89,$this->dataSize,"  Domicilio Comercial: ".$this->_empresa->domicilio,0,0,'L');
		$this->Cell(20,$this->dataSize,'',0,0,'C');
		$cuit = ($this->letra != self::LETRA_PRESUPUESTO) ? 'CUIT: '.$this->_empresa->cuit : '';
		$this->Cell(89,$this->dataSize, ($this->letra != self::LETRA_PRESUPUESTO) ? $cuit : $fecha,0,0,'L');
		$this->Ln($this->dataSize);

		$this->Cell(89,$this->dataSize,"",0,0,'L');
		$this->Cell(20,$this->dataSize,'',0,0,'C');
		if ($this->letra != self::LETRA_PRESUPUESTO) {
			$dataChar = 'Ingresos brutos: '.$this->_empresa->ingreso_brutos;
		} else {
			$dataChar = ($this->_presupuesto->descuento > 0) ? 'Descuento: '.$this->_presupuesto->descuento.'%' : '';
		};
		$this->Cell(89,$this->dataSize, $dataChar,0,0,'L');
		$this->Ln($this->dataSize);

		$this->Cell(89,$this->dataSize,"  Condicion frente el Iva: ".$this->_empresa->iva,0,0,'L');
		$this->Cell(20,$this->dataSize,'',0,0,'C');
		if ($this->letra != self::LETRA_PRESUPUESTO) {
			$dataChar = 'Inicio de actividades: '.date("d-m-Y", strtotime($this->_empresa->inicio_actividad));
		} else {
			$dataChar = '';
			foreach ($this->_vendedores as $vendedor) {
				if($vendedor->id_vendedor == $this->_presupuesto->id_vendedor){
					$dataChar = 'Vendedor: '.$vendedor->vendedor;
				}
			}

		};
		$this->Cell(89,$this->dataSize, $dataChar,0,0,'L');
		$this->Ln($this->dataSize +7);

			$txt = "CUIT: ".($this->_cliente->cuil ? $this->_cliente->cuil : '-')."  APELLIDO Y NOMBRE: ".$this->_cliente->nombre.' '.$this->_cliente->apellido;
			$txt .= "\nCONDICION FRENTE AL IVA: ".($this->_cliente->descripcion ? $this->_cliente->descripcion : '-')." DOMICILIO: ".($this->_cliente->direccion ? $this->_cliente->direccion : '-');
			$txt .= "\nCONDICION VENTA: ".$this->condicionPago[$this->_presupuesto->tipo];
			$this->MultiCell(190,7,$txt, 1);

			$this->Line(10, 10 , 10, 71);
			$this->Line(200, 10 , 200, 71);
			$this->Line(105, 36 , 105, 71);
			$this->Line(10, 71 , 200, 71);
	}

	function detail(){
			$this->Ln($this->separation);
			$this->SetFont('Arial','',8);
			$this->Cell(25,$this->dataSize,"Codigo",1,0,'C');
			$this->Cell(75,$this->dataSize,"Producto / Servicio",1,0,'C');
			$this->Cell(15,$this->dataSize,"Cantidad",1,0,'C');
			$this->Cell(15,$this->dataSize,"U. Medida",1,0,'C');
			$this->Cell(30,$this->dataSize,"Precio Unit.",1,0,'C');
			// $this->Cell(20,$this->dataSize,"% Bonif.",1,0,'C');
			// $this->Cell(20,$this->dataSize,"Imp. Bonif.",1,0,'C');
			$this->Cell(30,$this->dataSize,"Subtotal",1,0,'C');
			$y = $this->GetY() + 5;
			$x = $this->GetX();
			if($this->_renglon){
				foreach ($this->_renglon as $renglon) {
					$precio_unitario = round($renglon->precio / $renglon->cantidad, 2);

					$this->SetXY( $x, $y  );
					$this->Ln($this->separation);
					$this->SetFont('Arial','',8);
					$cod_proveedor = (strlen($renglon->cod_proveedor) > 12) ? substr($renglon->cod_proveedor, 0, 9).'...' : $renglon->cod_proveedor;
					$descripcion = utf8_decode((strlen($renglon->descripcion) > 50) ? substr($renglon->descripcion, 0, 46).'...' : $renglon->descripcion);
					$this->Cell(25,$this->dataSize, $cod_proveedor,0,0,'C');
					$this->Cell(75,$this->dataSize, $descripcion,0,0,'L');
					$this->Cell(15,$this->dataSize, $renglon->cantidad,0,0,'C');
					$this->Cell(15,$this->dataSize, '',0,0,'C');
					$this->Cell(30,$this->dataSize, $precio_unitario,0,0,'C');
					// $this->Cell(20,$this->dataSize, 0,0,0,'C');
					// $this->Cell(20,$this->dataSize, 0,0,0,'C');
					$this->Cell(30,$this->dataSize, round($renglon->precio, 2),0,0,'C');
					$y += 7;
				}
			}
	}

	// Pie de página
	function Footer() {
		$comentario = ($this->_presupuesto->com_publico == 1 && $this->_presupuesto->comentario) ? 'Comentario = '.$this->_presupuesto->comentario : 'Comentario';
			if($this->letra != self::LETRA_PRESUPUESTO){
				$txt = ($this->letra == self::LETRA_RESPONSABLE) ? "Subtotal: $ ".round($this->_presupuesto->monto / 1.21, 2) : '';
				$txt .= "\nImporte otros tributos: $ 0.00";
				$txt .= "\nImporte total: $ ".round($this->_presupuesto->monto, 2);
			} else {
				$txt = "\nImporte total: $ ".round($this->_presupuesto->monto, 2);
			}
	    // Posición: a 1,5 cm del final
	    $this->SetY(-85);
	    // Arial italic 8
	    $this->SetFont('Arial','B',12);
	   	$this->MultiCell(190,10, $txt,1,0);
			$this->Ln($this->separation);
			$this->SetFont('Arial','I',8);
			$this->Cell(190,10,$comentario,1,0,'C');
			$this->Ln(10 + $this->separation);
			if($this->letra != self::LETRA_PRESUPUESTO){
				$this->Cell(190,10,"Cae: ".$this->_factura->cae,0,0,'R');
				$this->Ln($this->dataSize);
				$this->Cell(190,10,"Fecha vencimiento Cae: ".date("d-m-Y", strtotime($this->_factura->fecha_vencimiento)) ,0,0,'R');
			}
	}
}
?>
