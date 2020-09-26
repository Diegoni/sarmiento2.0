<?php
class Articulos_model extends MY_Model {

	public function __construct(){
		parent::construct(
			'articulo',
			'id_articulo',
			'id_articulo', //ver si esto esta bien
			'id_articulo'
		);
	}

	function getArticulos_variacion($datos){
		$sql = "
		SELECT
			articulo.id_articulo,
			articulo.cod_proveedor,
			articulo.descripcion as descripcion,
			articulo.precio_costo,
			articulo.precio_venta_iva,
			articulo.precio_venta_sin_iva,
			articulo.iva as iva,
			proveedor.descripcion as proveedor,
			proveedor.descuento as descuento,
			proveedor.descuento2 as descuento2,
			proveedor.margen as margen,
			proveedor.impuesto as impuesto,
			grupo.descripcion as grupo,
			categoria.descripcion as categoria,
			subcategoria.descripcion as subcategoria
		FROM
			`articulo`
		INNER JOIN
			proveedor ON(articulo.id_proveedor=proveedor.id_proveedor)
		INNER JOIN
			grupo ON(articulo.id_grupo=grupo.id_grupo)
		INNER JOIN
			categoria ON(articulo.id_categoria=categoria.id_categoria)
		INNER JOIN
			subcategoria ON(articulo.id_subcategoria=subcategoria.id_subcategoria)
		WHERE
			proveedor.descripcion like '%$datos[proveedor]%' AND
			grupo.descripcion like '%$datos[grupo]%' AND
			categoria.descripcion like '%$datos[categoria]%' AND
			subcategoria.descripcion like '%$datos[subcategoria]%' AND
			articulo.id_estado = 1
		ORDER BY
			articulo.descripcion ";

		return $this->getQuery($sql);
	}

	public function getArticulo($data){
		$sql = "
			SELECT
				cod_proveedor,
				precio_costo,
				iva,
				precio_venta_iva,
				articulo.descripcion as value,
				id_articulo,
				precio_venta_iva,
				stock,
				stock_minimo,
				stock_deseado,
				llevar_stock,
				articulo.margen as margen,
				articulo.impuesto as impuesto,
				proveedor.descripcion as proveedor,
				proveedor.descuento as descuento,
				proveedor.descuento2 as descuento2
			FROM
				`articulo`
			INNER JOIN
				proveedor ON(articulo.id_proveedor=proveedor.id_proveedor)
			WHERE
				articulo.descripcion LIKE '%".$data."%' OR
				cod_proveedor LIKE '%".$data."%'
			LIMIT
				20";
			return $this->getQuery($sql);
	}

	public function updateByProvider($id_proveedor){
		$articulo = $this->getArticulos(['proveedor.id_proveedor' => $id_proveedor]);
		$this->updatePrecios($articulo, $variacion);
	}

	public function updateWhitPrice($id, $variacion){
		$articulo = $this->getArticulos(['articulo.id_articulo' => $id]);
		$this->updatePrecios($articulo, $variacion);
	}

	public function updateByCosto($id, $costo){
		$articulo = $this->getArticulos(['articulo.id_articulo' => $id]);
		$articulo[0]->precio_costo = $costo;
		$this->updatePrecios($articulo, 0);
	}

	function updatePrecios($articulos, $datos) {
		$variacion = (is_array($datos)) ? $datos['variacion'] : $datos;

		if($articulos){
			foreach ($articulos as $articulo) {
				$articulo_update = $this->getRowUpdate($articulo, $variacion);
				$this->db->update('articulo', $articulo_update, array('id_articulo' => $articulo->id_articulo));
			}
		}
	}

	public function getRowUpdate($articulo, $variacion){
		$decimales = 4;
		$precio_viejo	= $articulo->precio_costo;// solo para depurar
		$precio_costo	= $articulo->precio_costo + ($articulo->precio_costo * ($variacion / 100));// FUNCIONA PARA AUMENTOS Y DECREMENTOS POR LA MULTIP(+ * + = +     Y    + * -  = - )
		$costo_descuento1	= $precio_costo - ($precio_costo * ($articulo->descuento / 100));
		$costo_descuento	= $costo_descuento1 - ($costo_descuento1 * ($articulo->descuento2 / 100)); // APLICACION DE 2DO DESC ESCALONADO

		//02 - Precio con ganancia
		$precio_venta_sin_iva = $costo_descuento + ($costo_descuento * ($articulo->margen / 100));

		//2.5 - Precio con IMPUESTO 6%
		$precio_venta_sin_iva_con_imp = $precio_venta_sin_iva + ($precio_venta_sin_iva * ($articulo->impuesto / 100));

		//03 - Precio con iva
		$precio_venta_sin_iva_sin_imp	= $precio_venta_sin_iva;
		$precio_venta_con_iva_con_imp	= $precio_venta_sin_iva_con_imp + ($precio_venta_sin_iva_sin_imp * ($articulo->iva / 100));// precio c/dto1 c/dto2 s/iva s/imp c/margen +  %iva + %imp(p)

		$articulo_update = array(
			'precio_costo'									=> round($precio_costo, $decimales),
			'costo_descuento'								=> round($costo_descuento, $decimales),
			'precio_venta_sin_iva' 					=> round($precio_venta_sin_iva, $decimales),
			'precio_venta_sin_iva_con_imp'	=> round($precio_venta_sin_iva_con_imp, $decimales),
			'precio_venta_iva'							=> round($precio_venta_con_iva_con_imp, $decimales),
			'margen'												=> round($articulo->margen, $decimales),
			'impuesto'											=> round($articulo->impuesto, $decimales),
		);

		return $articulo_update;
	}

	public function getArticulos($filtros){
		$sql = "
			SELECT
					articulo.id_articulo,
					articulo.cod_proveedor,
					articulo.descripcion as descripcion,
					articulo.precio_costo,
					articulo.precio_venta_iva,
					articulo.precio_venta_sin_iva,
					articulo.iva as iva,
					articulo.margen as margen,
					articulo.impuesto as impuesto,
					articulo.stock,
					articulo.stock_minimo,
					articulo.stock_deseado,
					proveedor.descripcion as proveedor,
					proveedor.descuento as descuento,
					proveedor.descuento2 as descuento2
			FROM
				`articulo`
			INNER JOIN
				proveedor ON(articulo.id_proveedor=proveedor.id_proveedor)
			WHERE
				articulo.id_estado = 1 AND ";

			foreach ($filtros as $field => $value) {
				$sql .= $field." = ".$value." AND ";
			}

			$sql = substr($sql, 0, -4);
			$sql .= ' ORDER BY articulo.descripcion';

			return $this->getQuery($sql);
	}
}
?>
