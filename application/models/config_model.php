<?php
class Config_model extends MY_Model {

	public function __construct(){
		parent::construct(
			'config',
			'id_config',
			'id_config', //ver si esto esta bien
			'id_config'
		);
	}

	public function getConfig($campo = null){
		$sql = $this->db->query("SELECT dias_pago FROM config WHERE id_config = 1 ");
		if($campo == null){
				return $this->getQuery($sql);
		} else {
			$rows = $this->getQuery($sql);

			foreach ($rows as $row) {
				return $row->{$campo};
			}

		}

	}
}
?>
