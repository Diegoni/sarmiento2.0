/* Alter table in target */
ALTER TABLE `actualizacion_precio` 
	CHANGE `proveedor` `proveedor` varchar(128)  COLLATE utf8_spanish_ci NULL after `id_actualizacion` , 
	CHANGE `grupo` `grupo` varchar(128)  COLLATE utf8_spanish_ci NULL after `proveedor` , 
	CHANGE `categoria` `categoria` varchar(128)  COLLATE utf8_spanish_ci NULL after `grupo` , 
	CHANGE `subcategoria` `subcategoria` varchar(128)  COLLATE utf8_spanish_ci NULL after `categoria` , 
	CHANGE `variacion` `variacion` float   NULL after `subcategoria` , 
	CHANGE `id_usuario` `id_usuario` int(11)   NULL after `variacion` , 
	CHANGE `id_estado` `id_estado` tinyint(4)   NULL after `id_usuario` , 
	CHANGE `date_upd` `date_upd` datetime   NULL after `id_estado` , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `afip` 
	CHANGE `id_afip` `id_afip` int(11)   NOT NULL auto_increment first ;

/* Alter table in target */
ALTER TABLE `anulacion` 
	CHANGE `id_anulacion` `id_anulacion` int(11)   NOT NULL auto_increment first , 
	CHANGE `id_presupuesto` `id_presupuesto` int(11)   NULL after `id_anulacion` , 
	CHANGE `monto` `monto` float   NULL after `id_presupuesto` , 
	CHANGE `fecha` `fecha` datetime   NULL after `monto` , 
	CHANGE `nota` `nota` text  COLLATE utf8_spanish_ci NULL after `fecha` , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `articulo` 
	CHANGE `cod_proveedor` `cod_proveedor` varchar(50)  COLLATE utf8_spanish_ci NULL after `id_articulo` , 
	CHANGE `descripcion` `descripcion` varchar(80)  COLLATE utf8_spanish_ci NULL after `cod_proveedor` , 
	CHANGE `precio_costo` `precio_costo` float   NULL after `descripcion` , 
	CHANGE `costo_descuento` `costo_descuento` float   NULL after `precio_costo` , 
	CHANGE `iva` `iva` float   NULL after `costo_descuento` , 
	CHANGE `impuesto` `impuesto` float   NULL after `iva` , 
	CHANGE `margen` `margen` float   NULL after `impuesto` , 
	CHANGE `precio_venta_iva` `precio_venta_iva` float   NULL after `margen` , 
	CHANGE `precio_venta_sin_iva_con_imp` `precio_venta_sin_iva_con_imp` float   NULL after `precio_venta_iva` , 
	CHANGE `precio_venta_sin_iva` `precio_venta_sin_iva` float   NULL after `precio_venta_sin_iva_con_imp` , 
	CHANGE `id_proveedor` `id_proveedor` int(11)   NULL after `precio_venta_sin_iva` , 
	CHANGE `id_grupo` `id_grupo` int(11)   NULL after `id_proveedor` , 
	CHANGE `id_categoria` `id_categoria` int(11)   NULL after `id_grupo` , 
	CHANGE `id_subcategoria` `id_subcategoria` int(11)   NULL after `id_categoria` , 
	ADD COLUMN `stock` int(11)   NOT NULL after `id_subcategoria` , 
	ADD COLUMN `stock_minimo` int(11)   NOT NULL after `stock` , 
	ADD COLUMN `stock_deseado` int(11)   NOT NULL after `stock_minimo` , 
	ADD COLUMN `llevar_stock` tinyint(1)   NOT NULL after `stock_deseado` , 
	CHANGE `id_estado` `id_estado` int(11)   NULL after `llevar_stock` , 
	DROP KEY `grupo` , 
	DROP KEY `grupo_2` ;

/* Alter table in target */
ALTER TABLE `calendario` 
	CHANGE `title` `title` varchar(128)  COLLATE utf8_spanish_ci NULL after `id_calendario` , 
	CHANGE `start` `start` datetime   NULL after `title` , 
	CHANGE `end` `end` datetime   NULL after `start` , 
	CHANGE `id_color` `id_color` int(11)   NULL after `end` , 
	CHANGE `id_estado` `id_estado` tinyint(4)   NULL after `id_color` , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `categoria` 
	CHANGE `descripcion` `descripcion` varchar(40)  COLLATE utf8_spanish_ci NULL after `id_categoria` , 
	CHANGE `id_estado` `id_estado` int(11)   NULL after `descripcion` , 
	CHANGE `id_nota` `id_nota` int(11)   NULL after `id_estado` ;

/* Alter table in target */
ALTER TABLE `cliente` 
	CHANGE `nombre` `nombre` varchar(64)  COLLATE utf8_spanish_ci NULL after `id_cliente` , 
	CHANGE `apellido` `apellido` varchar(64)  COLLATE utf8_spanish_ci NULL after `nombre` , 
	CHANGE `alias` `alias` varchar(128)  COLLATE utf8_spanish_ci NULL after `apellido` , 
	CHANGE `direccion` `direccion` varchar(64)  COLLATE utf8_spanish_ci NULL after `alias` , 
	CHANGE `telefono` `telefono` varchar(32)  COLLATE utf8_spanish_ci NULL after `direccion` , 
	CHANGE `celular` `celular` varchar(32)  COLLATE utf8_spanish_ci NULL after `telefono` , 
	CHANGE `nextel` `nextel` varchar(32)  COLLATE utf8_spanish_ci NULL after `celular` , 
	CHANGE `cuil` `cuil` varchar(32)  COLLATE utf8_spanish_ci NULL after `nextel` , 
	CHANGE `id_condicion_iva` `id_condicion_iva` int(11)   NULL after `cuil` , 
	CHANGE `id_tipo` `id_tipo` int(11)   NULL after `id_condicion_iva` , 
	CHANGE `comentario` `comentario` text  COLLATE utf8_spanish_ci NULL after `id_tipo` , 
	CHANGE `id_estado` `id_estado` int(11)   NULL after `comentario` , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `color` 
	CHANGE `color` `color` varchar(128)  COLLATE utf8_spanish_ci NULL after `id_color` , 
	CHANGE `backgroundColor` `backgroundColor` varchar(16)  COLLATE utf8_spanish_ci NULL after `color` , 
	CHANGE `borderColor` `borderColor` varchar(16)  COLLATE utf8_spanish_ci NULL after `backgroundColor` , 
	ADD PRIMARY KEY(`id_color`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Create table in target */
CREATE TABLE `comprobantes`(
	`id_comprobante` int(11) NOT NULL  auto_increment , 
	`comprobante` varchar(64) COLLATE utf8mb4_general_ci NOT NULL  , 
	PRIMARY KEY (`id_comprobante`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8mb4' COLLATE='utf8mb4_general_ci';


/* Alter table in target */
ALTER TABLE `condicion_iva` 
	CHANGE `id_condicion_iva` `id_condicion_iva` int(11)   NOT NULL auto_increment first , 
	CHANGE `descripcion` `descripcion` varchar(32)  COLLATE utf8_spanish_ci NULL after `id_condicion_iva` , 
	ADD PRIMARY KEY(`id_condicion_iva`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `config` 
	CHANGE `dias_pago` `dias_pago` int(11)   NULL after `id_config` , 
	CHANGE `cantidad` `cantidad` int(11)   NULL after `dias_pago` , 
	CHANGE `cantidad_inicial` `cantidad_inicial` float   NULL after `cantidad` , 
	ADD PRIMARY KEY(`id_config`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `config_backup` 
	CHANGE `directorio` `directorio` varchar(128)  COLLATE utf8_spanish_ci NULL after `id_config` , 
	CHANGE `formato_fecha` `formato_fecha` varchar(16)  COLLATE utf8_spanish_ci NULL after `directorio` , 
	ADD PRIMARY KEY(`id_config`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `config_impresion` 
	CHANGE `impresion` `impresion` varchar(32)  COLLATE utf8_spanish_ci NULL after `id_config` , 
	CHANGE `cabecera` `cabecera` text  COLLATE utf8_spanish_ci NULL after `impresion` , 
	CHANGE `pie` `pie` text  COLLATE utf8_spanish_ci NULL after `cabecera` , 
	CHANGE `impresion_automatica` `impresion_automatica` tinyint(4)   NULL after `pie` , 
	ADD PRIMARY KEY(`id_config`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `devolucion` 
	CHANGE `id_devolucion` `id_devolucion` int(11)   NOT NULL auto_increment first , 
	CHANGE `id_presupuesto` `id_presupuesto` int(11)   NULL after `id_devolucion` , 
	CHANGE `fecha` `fecha` datetime   NULL after `id_presupuesto` , 
	CHANGE `monto` `monto` float   NULL after `fecha` , 
	CHANGE `a_cuenta` `a_cuenta` float   NULL after `monto` , 
	CHANGE `id_usuario` `id_usuario` int(11)   NULL after `a_cuenta` , 
	CHANGE `nota` `nota` text  COLLATE utf8_spanish_ci NULL after `id_usuario` , 
	CHANGE `id_estado` `id_estado` int(11)   NULL after `nota` , 
	ADD PRIMARY KEY(`id_devolucion`) , ENGINE=MyISAM COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `devolucion_detalle` 
	CHANGE `id_detalle` `id_detalle` int(11)   NOT NULL auto_increment first , 
	CHANGE `id_devolucion` `id_devolucion` int(11)   NULL after `id_detalle` , 
	CHANGE `id_articulo` `id_articulo` int(11)   NULL after `id_devolucion` , 
	CHANGE `cantidad` `cantidad` int(11)   NULL after `id_articulo` , 
	CHANGE `monto` `monto` float   NULL after `cantidad` , 
	ADD PRIMARY KEY(`id_detalle`) , ENGINE=MyISAM COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `empresa` 
	CHANGE `id_empresa` `id_empresa` int(11)   NOT NULL auto_increment first , 
	ADD COLUMN `codigo_postal` varchar(64)  COLLATE utf8mb4_general_ci NOT NULL after `domicilio` , 
	ADD COLUMN `departamento` varchar(64)  COLLATE utf8mb4_general_ci NOT NULL after `codigo_postal` , 
	ADD COLUMN `provincia` varchar(64)  COLLATE utf8mb4_general_ci NOT NULL after `departamento` , 
	CHANGE `telefono` `telefono` varchar(64)  COLLATE utf8mb4_general_ci NOT NULL after `provincia` , 
	CHANGE `iva` `iva` varchar(64)  COLLATE utf8mb4_general_ci NOT NULL after `telefono` , 
	CHANGE `punto_venta` `punto_venta` int(11)   NOT NULL after `iva` , 
	CHANGE `moneda` `moneda` int(11)   NOT NULL after `punto_venta` , 
	CHANGE `ingreso_brutos` `ingreso_brutos` varchar(32)  COLLATE utf8mb4_general_ci NOT NULL after `moneda` , 

	CHANGE `id_estado` `id_estado` tinyint(4)   NOT NULL after `inicio_actividad` , 
	ADD PRIMARY KEY(`id_empresa`) ;

/* Alter table in target */
ALTER TABLE `estado` 
	CHANGE `estado` `estado` varchar(32)  COLLATE utf8_spanish_ci NULL after `id_estado` , 
	ADD PRIMARY KEY(`id_estado`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `estado_devolucion` 
	CHANGE `estado` `estado` varchar(64)  COLLATE utf8_spanish_ci NULL after `id_estado` , 
	ADD PRIMARY KEY(`id_estado`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `estado_presupuesto` 
	CHANGE `estado` `estado` varchar(65)  COLLATE utf8_spanish_ci NULL after `id_estado` , 
	ADD PRIMARY KEY(`id_estado`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `factura` 
	CHANGE `id_factura` `id_factura` int(11)   NOT NULL auto_increment first , 
	CHANGE `pto_vta` `pto_vta` int(11)   NULL after `id_presupuesto` , 
	CHANGE `cbte_tipo` `cbte_tipo` int(11)   NULL after `pto_vta` , 
	CHANGE `concepto` `concepto` int(11)   NULL after `cbte_tipo` , 
	CHANGE `doc_tipo` `doc_tipo` int(11)   NULL after `concepto` , 
	CHANGE `doc_nro` `doc_nro` varchar(64)  COLLATE utf8mb4_general_ci NULL after `doc_tipo` , 
	CHANGE `cbte_desde` `cbte_desde` int(11)   NULL after `doc_nro` , 
	CHANGE `cbte_hasta` `cbte_hasta` int(11)   NULL after `cbte_desde` , 
	CHANGE `cbte_fch` `cbte_fch` date   NULL after `cbte_hasta` , 
	CHANGE `imp_total` `imp_total` double   NULL after `cbte_fch` , 
	CHANGE `imp_neto` `imp_neto` double   NULL after `imp_total` , 
	CHANGE `imp_iva` `imp_iva` double   NULL after `imp_neto` , 
	CHANGE `imp_tot_conc` `imp_tot_conc` double   NULL after `imp_iva` , 
	CHANGE `imp_op_ex` `imp_op_ex` double   NULL after `imp_tot_conc` , 
	CHANGE `imp_trib` `imp_trib` double   NULL after `imp_op_ex` , 
	CHANGE `mon_id` `mon_id` varchar(11)  COLLATE utf8mb4_general_ci NULL after `imp_trib` , 
	CHANGE `mon_cotiz` `mon_cotiz` int(11)   NULL after `mon_id` , 
	CHANGE `iva_id` `iva_id` int(11)   NULL after `mon_cotiz` , 
	CHANGE `resultado` `resultado` int(11)   NULL after `iva_id` , 
	CHANGE `cae` `cae` varchar(64)  COLLATE utf8mb4_general_ci NULL after `resultado` , 
	CHANGE `emision_tipo` `emision_tipo` varchar(11)  COLLATE utf8mb4_general_ci NULL after `cae` , 
	CHANGE `fecha_vencimiento` `fecha_vencimiento` date   NULL after `emision_tipo` , 
	CHANGE `fecha_proceso` `fecha_proceso` datetime   NULL after `fecha_vencimiento` , 
	ADD PRIMARY KEY(`id_factura`) ;

/* Alter table in target */
ALTER TABLE `familias` 
	CHANGE `descripcion` `descripcion` varchar(30)  COLLATE utf8_spanish_ci NULL after `codfamilia` , 
	ADD PRIMARY KEY(`codfamilia`) ;

/* Alter table in target */
ALTER TABLE `grupo` 
	CHANGE `descripcion` `descripcion` varchar(30)  COLLATE utf8_spanish_ci NULL after `id_grupo` , 
	CHANGE `id_estado` `id_estado` int(11)   NULL after `descripcion` , 
	CHANGE `id_nota` `id_nota` int(11)   NULL after `id_estado` , 
	ADD PRIMARY KEY(`id_grupo`) ;

/* Alter table in target */
ALTER TABLE `interes` 
	CHANGE `id_interes` `id_interes` int(11)   NULL first , 
	CHANGE `id_presupuesto` `id_presupuesto` int(11)   NULL after `id_interes` , 
	CHANGE `id_tipo` `id_tipo` int(11)   NULL after `id_presupuesto` , 
	CHANGE `monto` `monto` float   NULL after `id_tipo` , 
	CHANGE `descripcion` `descripcion` varchar(128)  COLLATE utf8_spanish_ci NULL after `monto` , 
	CHANGE `fecha` `fecha` datetime   NULL after `descripcion` , 
	CHANGE `id_usuario` `id_usuario` int(11)   NULL after `fecha` , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `log_presupuesto` 
	CHANGE `id_presupuesto` `id_presupuesto` int(20)   NULL first , 
	CHANGE `old_fecha` `old_fecha` datetime   NULL after `id_presupuesto` , 
	CHANGE `new_fecha` `new_fecha` datetime   NULL after `old_fecha` , 
	CHANGE `monto` `monto` float   NULL after `new_fecha` , 
	CHANGE `new_monto` `new_monto` float   NULL after `monto` , 
	CHANGE `id_cliente` `id_cliente` int(11)   NULL after `new_monto` , 
	CHANGE `new_id_cliente` `new_id_cliente` int(11)   NULL after `id_cliente` , 
	CHANGE `descuento` `descuento` float   NULL after `new_id_cliente` , 
	CHANGE `new_descuento` `new_descuento` float   NULL after `descuento` , 
	CHANGE `tipo` `tipo` int(11)   NULL after `new_descuento` , 
	CHANGE `new_tipo` `new_tipo` int(11)   NULL after `tipo` , 
	CHANGE `a_cuenta` `a_cuenta` float   NULL after `new_tipo` , 
	CHANGE `new_a_cuenta` `new_a_cuenta` float   NULL after `a_cuenta` , 
	CHANGE `estado` `estado` int(11)   NULL after `new_a_cuenta` , 
	CHANGE `new_estado` `new_estado` int(11)   NULL after `estado` , 
	CHANGE `fecha` `fecha` datetime   NULL after `new_estado` , ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `log_reglon_presupuesto` 
	CHANGE `id_renglon` `id_renglon` int(11)   NULL first , 
	CHANGE `id_presupuesto` `id_presupuesto` int(11)   NULL after `id_renglon` , 
	CHANGE `id_articulo` `id_articulo` int(11)   NULL after `id_presupuesto` , 
	CHANGE `old_cantidad` `old_cantidad` float   NULL after `id_articulo` , 
	CHANGE `new_cantidad` `new_cantidad` float   NULL after `old_cantidad` , 
	CHANGE `old_precio` `old_precio` float   NULL after `new_cantidad` , 
	CHANGE `new_precio` `new_precio` float   NULL after `old_precio` , 
	CHANGE `estado` `estado` int(11)   NULL after `new_precio` , 
	CHANGE `fecha` `fecha` datetime   NULL after `estado` , ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `logs` 
	CHANGE `id_log` `id_log` int(11)   NOT NULL auto_increment first , 
	CHANGE `tabla` `tabla` varchar(32)  COLLATE utf8_spanish_ci NULL after `id_log` , 
	CHANGE `id_tabla` `id_tabla` int(11)   NULL after `tabla` , 
	CHANGE `id_accion` `id_accion` int(11)   NULL after `id_tabla` , 
	CHANGE `fecha` `fecha` datetime   NULL after `id_accion` , 
	CHANGE `id_usuario` `id_usuario` int(11)   NULL after `fecha` , 
	ADD PRIMARY KEY(`id_log`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `nota` 
	CHANGE `nota` `nota` varchar(255)  COLLATE utf8_spanish_ci NULL after `id_nota` , 
	ADD PRIMARY KEY(`id_nota`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `permiso` 
	CHANGE `descripcion` `descripcion` varchar(32)  COLLATE utf8_spanish_ci NULL after `id_permiso` , 
	ADD PRIMARY KEY(`id_permiso`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `presupuesto` 
	CHANGE `id_presupuesto` `id_presupuesto` int(20)   NOT NULL auto_increment first , 
	CHANGE `fecha` `fecha` datetime   NULL after `id_presupuesto` , 
	CHANGE `monto` `monto` float   NULL after `fecha` , 
	CHANGE `id_cliente` `id_cliente` int(11)   NULL after `monto` , 
	CHANGE `descuento` `descuento` float   NULL after `id_cliente` , 
	CHANGE `tipo` `tipo` int(11)   NULL after `descuento` , 
	CHANGE `a_cuenta` `a_cuenta` float   NULL after `tipo` , 
	CHANGE `id_vendedor` `id_vendedor` int(11)   NULL after `com_publico` , 
	CHANGE `estado` `estado` int(11)   NULL after `id_vendedor` , 
	CHANGE `facturado` `facturado` tinytext  COLLATE utf8_spanish_ci NOT NULL after `estado` , 
	ADD PRIMARY KEY(`id_presupuesto`) , ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `proveedor` 
	CHANGE `descripcion` `descripcion` varchar(40)  COLLATE utf8_spanish_ci NULL after `id_proveedor` , 
	CHANGE `margen` `margen` float   NULL after `descripcion` , 
	CHANGE `impuesto` `impuesto` float   NULL after `margen` , 
	CHANGE `descuento` `descuento` float   NULL after `impuesto` , 
	CHANGE `descuento2` `descuento2` float   NULL after `descuento` , 
	CHANGE `id_estado` `id_estado` int(11)   NULL after `descuento2` , 
	ADD PRIMARY KEY(`id_proveedor`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `reglon_presupuesto` 
	CHANGE `id_renglon` `id_renglon` int(11)   NOT NULL auto_increment first , 
	CHANGE `id_presupuesto` `id_presupuesto` int(11)   NULL after `id_renglon` , 
	CHANGE `id_articulo` `id_articulo` int(11)   NULL after `id_presupuesto` , 
	CHANGE `cantidad` `cantidad` float   NULL after `id_articulo` , 
	CHANGE `precio` `precio` float   NULL after `cantidad` , 
	ADD COLUMN `iva_renglon` float   NOT NULL after `precio` , 
	CHANGE `estado` `estado` int(11)   NULL after `iva_renglon` , 
	ADD PRIMARY KEY(`id_renglon`) , ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `remito` 
	CHANGE `id_remito` `id_remito` int(20)   NOT NULL auto_increment first , 
	CHANGE `fecha` `fecha` datetime   NULL after `id_remito` , 
	CHANGE `monto` `monto` float   NULL after `fecha` , 
	CHANGE `devolucion` `devolucion` float   NULL after `monto` , 
	CHANGE `id_cliente` `id_cliente` int(11)   NULL after `devolucion` , 
	CHANGE `id_estado` `id_estado` int(11)   NULL after `id_cliente` , 
	ADD PRIMARY KEY(`id_remito`) , ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `remito_detalle` 
	CHANGE `id_remito_detalle` `id_remito_detalle` int(20)   NOT NULL auto_increment first , 
	CHANGE `monto` `monto` float   NULL after `id_remito_detalle` , 
	CHANGE `id_remito` `id_remito` int(11)   NULL after `monto` , 
	CHANGE `id_presupuesto` `id_presupuesto` int(11)   NULL after `id_remito` , 
	CHANGE `id_devolucion` `id_devolucion` int(11)   NULL after `id_presupuesto` , 
	CHANGE `a_cuenta` `a_cuenta` float   NULL after `id_devolucion` , 
	CHANGE `id_estado_presupuesto` `id_estado_presupuesto` int(11)   NULL after `a_cuenta` , 
	CHANGE `estado` `estado` int(11)   NULL after `id_estado_presupuesto` , 
	ADD PRIMARY KEY(`id_remito_detalle`) , ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `rol` 
	CHANGE `descripcion` `descripcion` varchar(32)  COLLATE utf8_spanish_ci NULL after `id_rol` , 
	CHANGE `permiso_articulo` `permiso_articulo` int(11)   NULL after `descripcion` , 
	CHANGE `permiso_proveedor` `permiso_proveedor` int(11)   NULL after `permiso_articulo` , 
	CHANGE `permiso_cliente` `permiso_cliente` int(11)   NULL after `permiso_proveedor` , 
	CHANGE `permiso_presupuesto` `permiso_presupuesto` int(11)   NULL after `permiso_cliente` , 
	CHANGE `permiso_ctacte` `permiso_ctacte` int(11)   NULL after `permiso_presupuesto` , 
	CHANGE `id_estado` `id_estado` int(11)   NULL after `permiso_ctacte` , 
	ADD PRIMARY KEY(`id_rol`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Create table in target */
CREATE TABLE `stock`(
	`id_stock` int(11) NOT NULL  auto_increment , 
	`date_add` datetime NOT NULL  , 
	`comentario` text COLLATE utf8mb4_general_ci NOT NULL  , 
	PRIMARY KEY (`id_stock`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8mb4' COLLATE='utf8mb4_general_ci';


/* Create table in target */
CREATE TABLE `stock_renglon`(
	`id_stock` bigint(20) NOT NULL  auto_increment , 
	`id_comprobante` int(11) NOT NULL  , 
	`nro_comprobante` int(11) NOT NULL  , 
	`id_articulo` int(11) NOT NULL  , 
	`cantidad` int(11) NOT NULL  , 
	PRIMARY KEY (`id_stock`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8mb4' COLLATE='utf8mb4_general_ci';


/* Alter table in target */
ALTER TABLE `subcategoria` 
	CHANGE `id_categoria_padre` `id_categoria_padre` int(11)   NULL after `id_subcategoria` , 
	CHANGE `descripcion` `descripcion` varchar(60)  COLLATE utf8_spanish_ci NULL after `id_categoria_padre` , 
	CHANGE `id_estado` `id_estado` int(11)   NULL after `descripcion` , 
	CHANGE `id_nota` `id_nota` int(11)   NULL after `id_estado` , 
	ADD PRIMARY KEY(`id_subcategoria`) ;

/* Alter table in target */
ALTER TABLE `tipo` 
	CHANGE `tipo` `tipo` varchar(32)  COLLATE utf8_spanish_ci NULL after `id_tipo` , 
	ADD PRIMARY KEY(`id_tipo`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `tipo_cliente` 
	CHANGE `id_tipo` `id_tipo` int(11)   NOT NULL auto_increment first , 
	ADD PRIMARY KEY(`id_tipo`) ;

/* Alter table in target */
ALTER TABLE `usuario` 
	CHANGE `descripcion` `descripcion` varchar(32)  COLLATE utf8_spanish_ci NULL after `id_usuario` , 
	CHANGE `pass` `pass` varchar(128)  COLLATE utf8_spanish_ci NULL after `descripcion` , 
	CHANGE `id_rol` `id_rol` int(11)   NULL after `pass` , 
	CHANGE `id_estado` `id_estado` int(11)   NULL after `id_rol` , 
	ADD PRIMARY KEY(`id_usuario`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;

/* Alter table in target */
ALTER TABLE `vendedor` 
	CHANGE `id_vendedor` `id_vendedor` int(11)   NOT NULL auto_increment first , 
	CHANGE `vendedor` `vendedor` varchar(128)  COLLATE utf8_spanish_ci NULL after `id_vendedor` , 
	CHANGE `id_estado` `id_estado` int(11)   NULL after `vendedor` , 
	ADD PRIMARY KEY(`id_vendedor`) , ENGINE=MyISAM, DEFAULT CHARSET='utf8' COLLATE ='utf8_spanish_ci' ;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;