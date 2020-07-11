<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class ESTADO_PRESUPUESTO {
	const FALTA_PAGO = 1;
	const CANCELADA = 2;
	const ANULADA = 3;
	const FACTURADA = 4;
}

abstract class FORMAS_PAGO {
	const CONTADO = 1;
	const CTA_CTE = 2;
	const TARJETA = 3;
}

abstract class COMPROBANTES {
	const MANUAL = 1;
	const PRESUPUESTO = 2;
	const DEVOLUCION = 3;
	const ANULACION = 4;
}
