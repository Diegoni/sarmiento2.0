<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mails extends MY_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('clientes_model');
		$this->load->model('presupuestos_model');
		$this->load->model('renglon_presupuesto_model');
		$this->load->model('facturas_model');
		$this->load->model('empresas_model');
		$this->load->model('vendedores_model');

		$this->load->library('grocery_CRUD');
	}

	public function enviar($id_presupuesto = null) {

		if($id_presupuesto != null){

			$fpdf = $this->load->library('/fpdf/fpdf');
			$caePdf = $this->load->library('/fpdf/CAEPdf');

			$presupuesto = $this->presupuestos_model->getRegistro($id_presupuesto);
			$presupuesto = $presupuesto[0];
			$rengloPresupuesto = $this->renglon_presupuesto_model->getDetalle($id_presupuesto);

			if($presupuesto->facturado == 1){
				$factura = $this->facturas_model->getBusqueda(['id_presupuesto' => $id_presupuesto]);
				$factura = $factura[0];
				$vendedores = false;
			} else {
				$factura = false;
				$vendedores = $this->vendedores_model->getRegistros();
			}
			$cliente = $this->clientes_model->getCliente($presupuesto->id_cliente);
			$cliente = $cliente[0];

			$empresa = $this->empresas_model->getRegistros(1);
			$empresa = $empresa[0];
			
			$pdf = new FPDF('P', 'pt', array(500,233));
			$pdf = new CAEPdf();
			$pdf->setValues($presupuesto, $rengloPresupuesto, $cliente, $empresa, $factura, $vendedores);
			$pdf->AddPage();
			$pdf->detail();
			
			// email stuff (change data below)
			$from = "bulonessarmiento@bulonessarmiento.com"; 
			$to = $cliente->mail; 
			$subject = "Bulones Sarmiento comprobante de compra"; 
			//$message = "<p>Please see the attachment.</p>";

			if (filter_var($to, FILTER_VALIDATE_EMAIL)){
				// a random hash will be necessary to send mixed content
				$separator = md5(time());

				// carriage return type (we use a PHP end of line constant)
				$eol = PHP_EOL;

				// attachment name
				$filename = "Comprobante.pdf";

				// encode data (puts attachment in proper format)
				$pdfdoc = $pdf->Output("", "S");
				$attachment = chunk_split(base64_encode($pdfdoc));

				// main header
				$headers  = "From: ".$from.$eol;
				$headers .= "MIME-Version: 1.0".$eol; 
				$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

				// no more headers after this, we start the body! //

				$body = "--".$separator.$eol;
				$body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
				$body .= "Hola, ".$cliente->nombre." ".$cliente->apellido.$eol;
				$body .= "Te enviamos adjunta el comprobante de tu compra. ".$eol;

				// message
				$body .= "--".$separator.$eol;
				$body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
				$body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
				//$body .= $message.$eol;

				// attachment
				$body .= "--".$separator.$eol;
				$body .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol; 
				$body .= "Content-Transfer-Encoding: base64".$eol;
				$body .= "Content-Disposition: attachment".$eol.$eol;
				$body .= $attachment.$eol;
				$body .= "--".$separator."--";

				// send message
				mail($to, $subject, $body, $headers);

				echo 'Se envio mail a '-$to;
			} else {
				echo 'No se envio';
			}
		}
	}
}
