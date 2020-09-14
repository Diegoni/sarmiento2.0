<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class Estadisticas extends My_Controller {
	function __construct() {
		parent::__construct();

		$this->load->model('presupuestos_model');
		$this->load->model('remitos_model');
		$this->load->model('devoluciones_model');
		$this->load->model('anulaciones_model');
		$this->load->model('vendedores_model');
		$this->load->model('config_model');

		$this->load->library('grocery_CRUD');
	}

	function verMas()
	{
		$cantidad = $this->config_model->getConfig('cantidad');
		$db['articulos']	= $this->presupuestos_model->get_top($this->input->post('inicio'), $this->input->post('fin'), $cantidad);
		$this->setView('estadisticas/get_top_cien.php', $db);
	}

	function anual()
	{
		$db['texto']	= getTexto();

		$ano = ($this->input->post('ano')) ? $this->input->post('ano') :  date('Y');
		$db['ano_actual']	= $ano;

		$inicio	= date('01-01-'.$ano);
		$ano = $ano + 1;
		$final	= date('01-01-'.$ano);

		$db['presupuestos']	= $this->presupuestos_model->suma_presupuesto($inicio, $final);
		$db['remitos']			= $this->remitos_model->suma_remito($inicio, $final);
		$db['devoluciones']	= $this->devoluciones_model->suma_devolucion($inicio, $final);
		$db['anulaciones']	= $this->anulaciones_model->suma_anulacion($inicio, $final);
		$db['articulos']		= $this->presupuestos_model->get_top($inicio, $final);
  	$db['vendedores']   = $this->vendedores_model->getRegistros();

		$this->setView('estadisticas/anual.php', $db);
	}

	function mensual($id_vendedor = NULL)
	{
		$db['texto']	= getTexto();
		$date['year']	= date('Y');
		$date['month']	= date('m');

		if($this->input->post('mes')) {
			$date['year']	= $this->input->post('ano');
			$date['month']	= $this->input->post('mes');
		}

		$inicio 						= date('01-'.$date['month'].'-'.$date['year']);
		$db['mes_actual']		= $date['month'];
		$db['ano_actual']		= $date['year'];
		$date								= $this->setNextMonth($date['month'], $date['year']);
		$final							= date('01-'.$date['month'].'-'.$date['year']);

		$db['remitos']			= $this->remitos_model->suma_remito($inicio, $final);
		$db['devoluciones']	= $this->devoluciones_model->suma_devolucion($inicio, $final);
		$db['anulaciones']	= $this->anulaciones_model->suma_anulacion($inicio, $final);
		$db['articulos']		= $this->presupuestos_model->get_top($inicio, $final);
		$db['inicio']				= $inicio;
		$db['fin']					= $final;

    if ($id_vendedor != NULL) {
        $db['id_vendedor']  = $id_vendedor;
        $db['vendedor']			= $this->vendedores_model->getRegistros();
        $db['presupuestos'] = $this->presupuestos_model->suma_presupuesto($inicio, $final, NULL, $id_vendedor);
    } else {
        $db['id_vendedor']  = FALSE;
        $db['vendedor']     = FALSE;
        $db['presupuestos'] = $this->presupuestos_model->suma_presupuesto($inicio, $final);
    }

		$this->setView('estadisticas/mensual.php', $db);
	}

	function resumen()
	{
		$db['texto']	= getTexto();

		if($this->input->post('inicio')) {
			$inicio					= date('d-m-Y', strtotime($this->input->post('inicio')));
			$final					= date('d-m-Y', strtotime($this->input->post('final')));
			$id_cliente			= $this->input->post('id_cliente');

			$db['inicio']		= date('d-m-Y', strtotime($inicio));
			$db['final']		= date('d-m-Y', strtotime($final));
		} else {
			$date['year']		= date('Y');
			$date['month']	= date('m');
			$id_cliente	= 0;
			$inicio	= date('01-'.$date['month'].'-'.$date['year']);
			$db['mes_actual']	= $date['month'];
			$db['ano_actual']	= $date['year'];
			$date = $this->setNextMonth($date['month'], $date['year']);
			$final	= date('01-'.$date['month'].'-'.$date['year']);
			$db['inicio']		= date('m-Y', strtotime($inicio));
			$db['final']		= date('m-Y', strtotime($final));
		}

		$db['presupuestos']	= $this->presupuestos_model->suma_presupuesto($inicio, $final, $id_cliente);
		$db['remitos']			= $this->remitos_model->suma_remito($inicio, $final, $id_cliente);
		$db['devoluciones']	= $this->devoluciones_model->suma_devolucion($inicio, $final, $id_cliente);
		$db['anulaciones']	= $this->anulaciones_model->suma_anulacion($inicio, $final, $id_cliente);

		$this->setView('estadisticas/resumen.php', $db);
	}

	private function setNextMonth($month, $year) {
		if($month == 12) {
			$date['month'] = 1;
			$date['year'] = $year + 1;
		} else {
			$date['month'] = $month + 1;
			$date['year'] = $year;
		}

		return $date;
	}
}

?>
