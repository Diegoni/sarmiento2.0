<?php
class MY_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}


	public function crudView($output) {
		if($this->session->userdata('logged_in')){
			$this->load->view('head.php', $output);
			$this->load->view('menu.php');
			$this->load->view('crud.php');
			$this->load->view('footer.php');
		}else{
			redirect('/','refresh');
		}
	}


	public function setView($view, $db = null) {
		if($this->session->userdata('logged_in')){

			$this->load->view('head.php', $db);
			$this->load->view('menu.php');

			if(is_array($view)){
				foreach ($view as $itemView) {
						$this->load->view($itemView);
				}
			}else{
				$this->load->view($view);
			}
			$this->load->view('footer.php');
		}else{
			redirect('/','refresh');
		}
	}

/**********************************************************************************
 **********************************************************************************
 *
 * 				Funciones logs
 *
 * ********************************************************************************
 **********************************************************************************/


	function insert_log($datos, $id){
		$session_data = $this->session->userdata('logged_in');

	    $registro = array(
	        "tabla"		=> $_COOKIE['tabla'],
	        "id_tabla"	=> $id,
	        "id_accion"	=> 1,
	        "fecha"		=> date('Y-m-d H:i:s'),
	        "id_usuario"=> $session_data['id_usuario']
	    );

	    $this->db->insert('logs',$registro);

		$registro =  array(
			"id_estado"=>1
		);

		$this->db->update($_COOKIE['tabla'], $registro, array($_COOKIE['id'] => $id));

	    return true;
	}


	function update_log($datos, $id){
		$session_data = $this->session->userdata('logged_in');

    	$registro = array(
	        "tabla"		=> $_COOKIE['tabla'],
	        "id_tabla"	=> $id,
	        "id_accion"	=> 2,
	        "fecha"		=> date('Y-m-d H:i:s'),
	        "id_usuario"=> $session_data['id_usuario']
	    );

    	$this->db->insert('logs',$registro);

    	return true;
	}


	public function delete_log($id){
    	$session_data = $this->session->userdata('logged_in');

		$registro = array(
	        "tabla"		=> $_COOKIE['tabla'],
	        "id_tabla"	=> $id,
	        "id_accion"	=> 3,
	        "fecha"		=> date('Y-m-d H:i:s'),
	        "id_usuario"=> $session_data['id_usuario']
	    );

    	$this->db->insert('logs',$registro);

    	return $this->db->update($_COOKIE['tabla'], array('id_estado' => 2), array($_COOKIE['id'] => $id));
	}

}
