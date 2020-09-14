<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articulos extends My_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('articulos_model');
		$this->load->model('proveedores_model');
		$this->load->model('grupos_model');
		$this->load->model('categorias_model');
		$this->load->model('subcategorias_model');
		$this->load->model('actualizaciones_precion_model');

		$this->load->library('grocery_CRUD');
	}

	/*
	* CRUD CATEGORIA
	* @return view
	*/
	public function categoria_abm() {
		$crud = new grocery_CRUD();

		$crud->where('categoria.id_estado = 1');
		$crud->set_table('categoria');

		$crud->columns('descripcion');

		$crud->display_as('descripcion','Descripción')
			 ->display_as('id_estado','Estado');

		$crud->set_subject('categoria');

		$crud->fields('descripcion');

		$crud->required_fields('descripcion','id_estado');
		$crud->set_relation('id_estado','estado','estado');

		$_COOKIE['tabla']='categoria';
		$_COOKIE['id']='id_categoria';

		$crud->callback_after_insert(array($this, 'insert_log'));
		$crud->callback_after_update(array($this, 'update_log'));
		$crud->callback_delete(array($this,'delete_log'));

		$this->permisos_model->getPermisos_CRUD('permiso_articulo', $crud);

		$output = $crud->render();

		$this->crudView($output);
	}

	/*
	* CRUD sub - CATEGORIA
	* @return view
	*/
	public function subcategoria_abm() {
			$crud = new grocery_CRUD();

			$crud->where('subcategoria.id_estado = 1');
			$crud->set_table('subcategoria');
			$crud->columns('descripcion', 'id_categoria_padre');
			$crud->display_as('descripcion','Descripción')
				 ->display_as('id_estado','Estado')
				 ->display_as('id_categoria_padre','Categoria padre');
			$crud->set_subject('subcategoria');
			$crud->required_fields('descripcion','id_estado','id_categoria_padre');
			$crud->set_relation('id_estado','estado','estado');
			$crud->set_relation('id_categoria_padre','categoria','descripcion', 'categoria.id_estado = 1');
			$crud->fields('descripcion');

			$_COOKIE['tabla']='subcategoria';
			$_COOKIE['id']='id_subcategoria';

			$crud->callback_after_insert(array($this, 'insert_log'));
			$crud->callback_after_update(array($this, 'update_log'));
			$crud->callback_delete(array($this,'delete_log'));

			$this->permisos_model->getPermisos_CRUD('permiso_articulo', $crud);

			$output = $crud->render();

			$this->crudView($output);
	}

	/*
	* CRUD GRUPO
	* @return view
	*/
	public function grupo_abm() {
			$crud = new grocery_CRUD();

			$crud->where('grupo.id_estado = 1');
			$crud->set_table('grupo');
			$crud->columns('descripcion');
			$crud->display_as('descripcion','Descripción')
				 ->display_as('id_estado','Estado');
			$crud->set_subject('grupo');
			$crud->required_fields('descripcion','id_estado');
			$crud->set_relation('id_estado','estado','estado');

			$crud->fields('descripcion');

			$_COOKIE['tabla']='grupo';
			$_COOKIE['id']='id_grupo';

			$crud->callback_after_insert(array($this, 'insert_log'));
			$crud->callback_after_update(array($this, 'update_log'));
			$crud->callback_delete(array($this,'delete_log'));

			$this->permisos_model->getPermisos_CRUD('permiso_articulo', $crud);

			$output = $crud->render();

			$this->crudView($output);
	}


	/*
	* CRUD ARTICULO|
	* @return view
	*/
	public function articulo_abm() {
		$crud = new grocery_CRUD();

		$crud->where('articulo.id_estado = 1');

		$crud->set_table('articulo');
		$crud->columns('cod_proveedor','descripcion','precio_costo','precio_venta_iva');
		$crud->display_as('descripcion','Descripción')
			 ->display_as('id_proveedor','Proveedor')
			 ->display_as('id_grupo','Grupo')
			 ->display_as('id_proveedor','Proveedor')
			 ->display_as('id_categoria','Categoria')
			 ->display_as('id_subcategoria','Subcategoria')
			 ->display_as('id_estado','Estado');
		$crud->fields(	'cod_proveedor',
			'descripcion',
			'precio_costo',
			'margen',
			'iva',
			'impuesto',
			'id_proveedor',
			'id_grupo',
			'id_categoria',
			'id_subcategoria',
			'stock_minimo',
			'stock_deseado',
			'llevar_stock');
		$crud->required_fields(	'cod_proveedor',
			'descripcion',
			'precio_costo',
			'margen',
			'iva',
			'impuesto',
			'id_proveedor',
			'id_grupo',
			'id_categoria',
			'id_subcategoria');

		$crud->set_subject('articulo');
		$crud->set_relation('id_proveedor','proveedor','{descripcion}', 'proveedor.id_estado = 1');
		$crud->set_relation('id_grupo','grupo','descripcion', 'grupo.id_estado = 1');
		$crud->set_relation('id_categoria','categoria','descripcion', 'categoria.id_estado = 1');
		$crud->set_relation('id_subcategoria','subcategoria','descripcion', 'subcategoria.id_estado = 1');
		$crud->set_relation('id_estado','estado','estado');

		$_COOKIE['tabla']='articulo';
		$_COOKIE['id']='id_articulo';

		$crud->add_action('Stock', '', '','icon-archive', array($this, 'stockArticulo'));

		$crud->callback_after_insert(array($this, 'insert_log'));
		$crud->callback_after_insert(array($this, 'actualizar_precios'));
		$crud->callback_after_update(array($this, 'update_log'));
		$crud->callback_after_update(array($this, 'actualizar_precios'));
		$crud->callback_delete(array($this,'delete_log'));

		$this->permisos_model->getPermisos_CRUD('permiso_articulo', $crud);

		$output = $crud->render();

		$this->crudView($output);
	}

	function stockArticulo($id) {
		return site_url('/stock/stockArticulo').'/'.$id;
	}

	/*
	* Actualizar los precios
	* @param dato
	* @param id
	* @return bool
	*/
	public function actualizar_precios($datos, $id) {
		$this->articulos_model->updateWhitPrice($id, 0);
	  return true;
	}

	/*
	* Actualizar los precios por lote
	* @return view
	*/
	public function actualizar_precios_lote() {
		$db['proveedores']		= $this->proveedores_model->getRegistros();
		$db['grupos']					= $this->grupos_model->getRegistros();
		$db['categorias']			= $this->categorias_model->getRegistros();
		$db['subcategorias']	= $this->subcategorias_model->getRegistros();

		if($this->input->post('buscar')) {
			$datos = [
				'proveedor'		=> $this->input->post('proveedor'),
				'grupo'				=> $this->input->post('grupo'),
				'categoria'		=> $this->input->post('categoria'),
				'subcategoria'=> $this->input->post('subcategoria'),
				'variacion'		=> $this->input->post('variacion'),
				'id_estado'		=> 1,
				'date_upd'		=> date('Y:m:d H:i:s')
			];

			$db['articulos']	= $this->articulos_model->getArticulos_variacion($datos);
			$db['mensaje']		= ($db['articulos']) ? "Cantidad de articulos a actualizar: ".count($db['articulos']) : "No existen articulos";
			$db['class']			= "hide";

			if($this->input->post('confirmar')) {
				$this->actualizaciones_precion_model->insert($datos);
				$this->articulos_model->updatePrecios($db['articulos'], $datos);
				$db['articulos']	= $this->articulos_model->getArticulos_variacion($datos);
				$db['mensaje']		= "Los articulos se han actualizado";
			}
		} else {
			$datos = [
				'proveedor'		=> '',
				'grupo'				=> '',
				'categoria'		=> '',
				'subcategoria'=> '',
				'variacion'		=> '',
			];
			$db['class'] = "show";
			$db['actualizaciones'] = $this->actualizaciones_precion_model->getRegistros();
		}

		$db['datos']			= $datos;
		$this->setView(['actualizar precios_lote', 'calendarios/config_actualizar'], $db);
	}

	/*
	*
	* @return view
	*/
	public function searchArticulo() {
		$articulos= $this->articulos_model->getArticulo($_GET['term']);
		if ($articulos) {
			foreach ($articulos as $rowArticulo) {
				$row['value']	= htmlentities(stripslashes($rowArticulo->value));
				$row['id'] = (int) $rowArticulo->id_articulo;
				$row['stock'] = (int) $rowArticulo->stock;
				$row['stock_minimo'] = (int) $rowArticulo->stock_minimo;
				$row['stock_deseado'] = (int) $rowArticulo->stock_deseado;
				$row['llevar_stock'] = (int) $rowArticulo->llevar_stock;
				$row['cod_proveedor'] = $rowArticulo->cod_proveedor;
				$row['precio_costo'] = (float) $rowArticulo->precio_costo;
				$row['iva'] = (float) $rowArticulo->iva;
				$row['impuesto'] = (float) $rowArticulo->impuesto;
				$row['margen'] = (float) $rowArticulo->margen;
				$row['descuento'] = (float) $rowArticulo->descuento;
				$row['descuento2'] = (float) $rowArticulo->descuento2;
				$row['precio_venta_iva'] = (float) $rowArticulo->precio_venta_iva;
				$row_set[] = $row;
			}
			echo json_encode($row_set);
		} else {
			return FALSE;
		}
	}

	public function calculatePrices() {
		$articulo = new StdClass;
		$articulo->precio_costo = $this->input->post('precio_costo');
		$articulo->descuento = $this->input->post('descuento');
		$articulo->descuento2 = $this->input->post('descuento2');
		$articulo->margen = $this->input->post('margen');
		$articulo->impuesto = $this->input->post('impuesto');
		$articulo->iva = $this->input->post('iva');

		$precio = $this->articulos_model->getRowUpdate($articulo, 0);

		echo json_encode($precio);
	}
}
