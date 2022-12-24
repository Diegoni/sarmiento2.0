<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class AfipFactuaElectronica extends My_Controller {
		private $empresa;
		private $afipConfig;
		private $factData;
		private $cliente;

		const ESTADO_CONTADO = 1;
		const ESTADO_FALTA_PAGO = 2;

		const COND_IVA_MONOTRIBUTISTA = 1;
		const COND_IVA_RESP_INSC = 2;
		const COND_EXENTO_IVA = 3;

		const CONFIG_A = 1;
		const CONFIG_B = 2;

		public function __construct() {
			parent::__construct();

			$this->load->model('afip_model');
			$this->load->model('clientes_model');
			$this->load->model('facturas_model');
			$this->load->model('empresas_model');
			$this->load->model('presupuestos_model');

			$empresa = $this->empresas_model->getRegistros(1);
			$this->empresa = $empresa[0];
		}

		/**
		 * Web services para generar el Cae
		 *
		 * @param type $idPresupuesto id del presupuesto.
		 * @return string Cae o error obtenido.
		 */
		public function getCAE($id_presupuesto = NULL) {
			$presupuesto = ($id_presupuesto != NULL) ? $this->presupuestos_model->getBusqueda(['id_presupuesto' => $id_presupuesto] ) : json_decode($_POST['presupuesto']);
			$cliente = $this->clientes_model->getCliente($presupuesto[0]->id_cliente);
			$this->cliente = $cliente[0];

			$configAfipFactura = ($cliente[0]->id_condicion_iva == self::COND_IVA_RESP_INSC) ? self::CONFIG_A : self::CONFIG_B;
			$afip = $this->afip_model->getRegistro($configAfipFactura);
			$this->afipConfig = $afip[0];

			if ($presupuesto[0]->estado == self::ESTADO_CONTADO || $presupuesto[0]->estado == self::ESTADO_FALTA_PAGO){
				$caeData = $this->setSoap($presupuesto, $cliente[0]);
				if ($caeData) {
						$this->setCae($caeData, $presupuesto[0]->id_presupuesto);
						echo json_encode($caeData);
				}
			} else {
				echo json_encode(['ERROR' =>  'El presupuesto no puede ser reportado']);
			}
		}

		/**
		 * Web services con la afip para la obtencion del Cae
		 *
		 * @param array $presupuesto datos del presupuesto.
		 * @return array Datos obtenidos en el web services.
		 */
		private function setSoap($presupuesto) {
			$afip = $this->getAfipObjet();

			$ImpTotal = round($presupuesto[0]->monto, 2);
			$ImpNeto = round($ImpTotal / 1.21, 2);
			$ImpIVA = $ImpTotal - $ImpNeto;

			$data = array(
				'CantReg' 		=> 1, // Cantidad de comprobantes a registrar
				'PtoVta' 			=> $this->empresa->punto_venta, // Punto de venta
				'CbteTipo' 		=> $this->afipConfig->tipo_comprobante, // Tipo de comprobante (ver tipos disponibles)
				'Concepto' 		=> $this->afipConfig->concepto, // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
				'DocTipo' 		=> 80, // Tipo de documento del comprador (ver tipos disponibles)
				'DocNro' 			=> $this->cliente->cuil, // Numero de documento del comprador
				'CbteDesde' 	=> $this->afipConfig->cbte_desde, // Numero de comprobante o numero del primer comprobante en caso de ser mas de uno
				'CbteHasta' 	=> $this->afipConfig->cbte_hasta, // Numero de comprobante o numero del ultimo comprobante en caso de ser mas de uno
				'CbteFch' 		=> intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo

				'ImpTotal' 		=> $ImpTotal, // Importe total del comprobante ImpTotConc + ImpNeto + ImpOpEx + ImpTrib + ImpIVA.
				'ImpTotConc' 	=> 0, // Importe neto no gravado
				'ImpNeto' 		=> $ImpNeto, // Importe neto gravado
				'ImpOpEx' 		=> 0, // Importe exento de IVA
				'ImpIVA' 			=> $ImpIVA, //Importe total de IVA
				'ImpTrib' 		=> 0, //Importe total de tributos

				'FchServDesde'=> NULL, // (Opcional) Fecha de inicio del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
				'FchServHasta'=> NULL, // (Opcional) Fecha de fin del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
				'FchVtoPago' 	=> NULL, // (Opcional) Fecha de vencimiento del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
				'MonId' 			=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos)
				'MonCotiz' 		=> $this->empresa->moneda, // Cotización de la moneda usada (1 para pesos argentinos)
				'Iva' 				=> array( // (Opcional) Alícuotas asociadas al comprobante
					array(
						'Id' 		=> 5, // Id del tipo de IVA (ver tipos disponibles)
						'BaseImp' 	=> $ImpNeto, // Base imponible
						'Importe' 	=> $ImpIVA // Importe
					)
				),
			);

			try{
				$caeData = $afip->ElectronicBilling->CreateVoucher($data);
				$this->factData = $data;
			} catch (Exception $e){
				$caeData = false;
				log_message ('ERROR', $e);
				echo  $e;
				throw new \Exception("Error Intento de crear CAE", 2);
			}

			return $caeData;
		}

		/**
		 * Guarda los datos obtenidos en el Web Services.
		 *
		 * @param type $caeData Datos obtenidos en el web services.
		 * @param type $idPresupuesto id del presupuesto.
		 * @return void Description.
		 */
		private function setCae($caeData, $idPresupuesto){
			$updateAfip = [
				'cbte_desde' =>  $this->afipConfig->cbte_desde + 1,
				'cbte_hasta' =>  $this->afipConfig->cbte_hasta + 1,
			];
			$this->afip_model->update($updateAfip,  $this->afipConfig->id_afip);

			$updatePresupuesto = [
				'facturado' => 1,
			];
			$this->presupuestos_model->update($updatePresupuesto, $idPresupuesto);

			$insertFactura = [
				'id_presupuesto' => $idPresupuesto,
				'pto_vta' => $this->factData['PtoVta'],
				'cbte_tipo' => $this->factData['CbteTipo'],
				'concepto' => $this->factData['Concepto'],
				'doc_tipo' => $this->factData['DocTipo'],
				'doc_nro' => $this->factData['DocNro'],
				'cbte_desde' => $this->factData['CbteDesde'],
				'cbte_hasta' => $this->factData['CbteHasta'],
				'cbte_fch' => $this->factData['CbteFch'],
				'imp_total' => $this->factData['ImpTotal'],
				'imp_neto' => $this->factData['ImpNeto'],
				'imp_iva' => $this->factData['ImpIVA'],
				'imp_tot_conc' => $this->factData['ImpTotConc'],
				'imp_op_ex' => $this->factData['ImpOpEx'],
				'imp_trib' => $this->factData['ImpTrib'],
				'mon_id' => $this->factData['MonId'],
				'mon_cotiz' => $this->factData['MonCotiz'],
				'iva_id' => 5,
				'resultado' => 'A',
				'emision_tipo' =>  'CAE',
				'fecha_proceso'=> intval(date('YmdHis')),
				'cae' => $caeData["CAE"],
				'fecha_vencimiento' => $caeData["CAEFchVto"],
			];

			$this->facturas_model->insert($insertFactura);
		}

		private function getAfipObjet(){
			$dataEmpresa = [	
					'cert' => 'sarmientows_95a79f9008421d7.crt',
					'key' => 'privada.key',
					'CUIT' => 30672163494,
					'production' => TRUE
				];

				$afip = $this->load->library('/afip/Afip', $dataEmpresa);
				$afip = new Afip( $dataEmpresa );
				return $afip;
		}

		public function synchronizeVoucherNumbering() {
			$afip = $this->getAfipObjet();
			$afipConfig = $this->afip_model->getRegistros();
			foreach ($afipConfig as $config) {
				$lastVoucher = $afip->ElectronicBilling->GetLastVoucher($this->empresa->punto_venta, $config->tipo_comprobante);
				$lastVoucher ++;
				if($lastVoucher != $config->cbte_hasta){
					$update = [
						'cbte_desde' => $lastVoucher,
						'cbte_hasta' => $lastVoucher,
					];

					$this->afip_model->update($update, $config->id_afip);
					echo 'Se actualizo numeración del comprobante tipo='.$config->tipo_comprobante.'<br>';
				} else {
					echo 'Numeración ok tipo='.$config->tipo_comprobante.'<br>';
				}
			}
		}
}
?>
