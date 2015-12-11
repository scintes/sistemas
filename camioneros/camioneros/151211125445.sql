/*
MySQL Backup
Source Server Version: 5.5.24
Source Database: camioneros
Date: 11/12/2015 12:54:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `choferes`
-- ----------------------------
DROP TABLE IF EXISTS `choferes`;
CREATE TABLE `choferes` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `tel` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cel` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `cetegoria` int(11) DEFAULT NULL,
  `datos` tinytext COLLATE utf8_spanish2_ci,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `clientes`
-- ----------------------------
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `cuit_cuil` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `razon_social` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `responsable` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tel` varchar(16) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cel` varchar(16) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `id_localidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `clientes_ibfk_1` (`id_localidad`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`id_localidad`) REFERENCES `localidades` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Ficha personal del cliente de la empresa';

-- ----------------------------
--  Table structure for `gastos`
-- ----------------------------
DROP TABLE IF EXISTS `gastos`;
CREATE TABLE `gastos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `detalles` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `Importe` decimal(15,2) DEFAULT NULL,
  `id_tipo_gasto` int(11) DEFAULT NULL,
  `id_hoja_ruta` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `gastos_ibfk_1` (`id_tipo_gasto`),
  KEY `gastos_ibfk_2` (`id_hoja_ruta`),
  CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`id_tipo_gasto`) REFERENCES `tipo_gastos` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `gastos_ibfk_2` FOREIGN KEY (`id_hoja_ruta`) REFERENCES `hoja_rutas` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `gastos_mantenimientos`
-- ----------------------------
DROP TABLE IF EXISTS `gastos_mantenimientos`;
CREATE TABLE `gastos_mantenimientos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `detalle` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `id_tipo_gasto` int(11) DEFAULT NULL,
  `id_hoja_mantenimeinto` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo_gasto` (`id_tipo_gasto`),
  KEY `id_hoja_mantenimeinto` (`id_hoja_mantenimeinto`),
  CONSTRAINT `gastos_mantenimientos_ibfk_1` FOREIGN KEY (`id_tipo_gasto`) REFERENCES `tipo_gastos` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `gastos_mantenimientos_ibfk_2` FOREIGN KEY (`id_hoja_mantenimeinto`) REFERENCES `hoja_mantenimientos` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `hoja_mantenimientos`
-- ----------------------------
DROP TABLE IF EXISTS `hoja_mantenimientos`;
CREATE TABLE `hoja_mantenimientos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_ini` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha_fin` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL COMMENT 'Fecha en que finaliza el mantenimiento',
  `id_vehiculo` int(11) DEFAULT NULL,
  `id_taller` int(11) DEFAULT NULL,
  `id_tipo_mantenimiento` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `hoja_mantenimientos_ibfk_2` (`id_taller`),
  KEY `hoja_mantenimientos_ibfk_1` (`id_vehiculo`),
  KEY `hoja_mantenimientos_ibfk_3` (`id_tipo_mantenimiento`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `hoja_mantenimientos_ibfk_4` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_mantenimientos_ibfk_1` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_mantenimientos_ibfk_2` FOREIGN KEY (`id_taller`) REFERENCES `talleres` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_mantenimientos_ibfk_3` FOREIGN KEY (`id_tipo_mantenimiento`) REFERENCES `tipo_mantenimientos` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `hoja_rutas`
-- ----------------------------
DROP TABLE IF EXISTS `hoja_rutas`;
CREATE TABLE `hoja_rutas` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_ini` date DEFAULT NULL COMMENT 'fecha y hora de inicio del viaje',
  `fecha_fin` date DEFAULT NULL COMMENT 'fecha y hora en que finaliza el viaje',
  `Origen` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `Destino` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `Km_ini` int(10) unsigned DEFAULT NULL COMMENT 'Kilometraje con que finaliza el viaje',
  `km_fin` int(10) unsigned DEFAULT NULL COMMENT 'Kilometraje con que termina el viaje',
  `estado` varchar(1) COLLATE utf8_spanish2_ci DEFAULT 'A' COMMENT 'Determina en que estado se encuentra el trasnporte de la carga',
  `id_vehiculo` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_tipo_carga` int(11) DEFAULT NULL,
  `id_localidad_origen` int(11) DEFAULT NULL,
  `id_localidad_destino` int(11) DEFAULT NULL,
  `kg_carga` int(11) DEFAULT '0' COMMENT 'Peso transportado',
  `tarifa` decimal(15,2) DEFAULT '0.00' COMMENT 'Importe por kg de carga',
  `porcentaje` decimal(15,2) DEFAULT '0.00' COMMENT 'Porcentaje de comision del chofer',
  `adelanto` decimal(15,2) DEFAULT '0.00' COMMENT 'Dinero que recibe el chofer',
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `hoja_rutas_ibfk_1` (`id_vehiculo`),
  KEY `hoja_rutas_ibfk_2` (`id_cliente`),
  KEY `hoja_rutas_ibfk_4` (`id_localidad_origen`),
  KEY `hoja_rutas_ibfk_5` (`id_localidad_destino`),
  KEY `hoja_rutas_ibfk_3` (`id_tipo_carga`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `hoja_rutas_ibfk_6` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_rutas_ibfk_1` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_rutas_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_rutas_ibfk_3` FOREIGN KEY (`id_tipo_carga`) REFERENCES `tipo_cargas` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_rutas_ibfk_4` FOREIGN KEY (`id_localidad_origen`) REFERENCES `localidades` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_rutas_ibfk_5` FOREIGN KEY (`id_localidad_destino`) REFERENCES `localidades` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `localidades`
-- ----------------------------
DROP TABLE IF EXISTS `localidades`;
CREATE TABLE `localidades` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `localidad` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cp` int(11) DEFAULT NULL,
  `id_provincia` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `localidades_ibfk_1` (`id_provincia`),
  CONSTRAINT `localidades_ibfk_1` FOREIGN KEY (`id_provincia`) REFERENCES `provincias` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `marcas`
-- ----------------------------
DROP TABLE IF EXISTS `marcas`;
CREATE TABLE `marcas` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `marca` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modelo` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `nivel_permisos_usuario`
-- ----------------------------
DROP TABLE IF EXISTS `nivel_permisos_usuario`;
CREATE TABLE `nivel_permisos_usuario` (
  `id_nivel_usuario` int(11) NOT NULL,
  `nombre_tabla` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `permisos` int(11) NOT NULL,
  PRIMARY KEY (`id_nivel_usuario`,`nombre_tabla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `nivel_usuario`
-- ----------------------------
DROP TABLE IF EXISTS `nivel_usuario`;
CREATE TABLE `nivel_usuario` (
  `codigo` int(11) NOT NULL,
  `nombre_nivel` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `provincias`
-- ----------------------------
DROP TABLE IF EXISTS `provincias`;
CREATE TABLE `provincias` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `provincia` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `talleres`
-- ----------------------------
DROP TABLE IF EXISTS `talleres`;
CREATE TABLE `talleres` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `taller` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `direccion` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tel` varchar(16) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cel` varchar(16) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `mail` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tipo_cargas`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_cargas`;
CREATE TABLE `tipo_cargas` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `Tipo_carga` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `precio_base` decimal(15,2) DEFAULT NULL,
  `porcentaje_comision` decimal(15,2) DEFAULT '0.00' COMMENT 'comision a los choferes',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tipo_gastos`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_gastos`;
CREATE TABLE `tipo_gastos` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `tipo_gasto` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `clase` varchar(1) COLLATE utf8_spanish2_ci DEFAULT 'R' COMMENT 'R: tipo de gasto para hoja _ruta; M: tipo de gasto para Mantenimiento',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tipo_mantenimientos`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_mantenimientos`;
CREATE TABLE `tipo_mantenimientos` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `tipo_mantenimiento` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `usuarios`
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `contrasenia` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` int(1) DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `vehiculos`
-- ----------------------------
DROP TABLE IF EXISTS `vehiculos`;
CREATE TABLE `vehiculos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `Patente` varchar(7) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cantidad_rueda` int(11) DEFAULT NULL,
  `nombre` varchar(10) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modelo` int(11) DEFAULT NULL,
  `id_chofer` int(11) DEFAULT NULL,
  `id_guarda` int(11) DEFAULT NULL,
  `id_marca` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `vehiculos_ibfk_1` (`id_chofer`),
  KEY `vehiculos_ibfk_2` (`id_guarda`),
  KEY `vehiculos_ibfk_3` (`id_marca`),
  CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`id_chofer`) REFERENCES `choferes` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `vehiculos_ibfk_2` FOREIGN KEY (`id_guarda`) REFERENCES `choferes` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `vehiculos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  View definition for `v_gastos_hoja_ruta`
-- ----------------------------
DROP VIEW IF EXISTS `v_gastos_hoja_ruta`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `v_gastos_hoja_ruta` AS SELECT
pd.provincia AS prov_desde,
ph.provincia AS prov_hasta,
loc_hasta.localidad AS loc_hasta,
loc_hasta.cp AS cp_hasta,
loc_desde.localidad AS loc_desde,
loc_desde.cp AS cp_desde,
hr.fecha_ini,
hr.fecha_fin,
hr.Origen,
hr.Destino,
hr.Km_ini,
hr.km_fin,
hr.estado,
v.Patente,
tc.Tipo_carga,
cl.cuit_cuil,
cl.razon_social,
cl.responsable,
cho.nombre AS nom_chofer,
gua.nombre AS nom_guarda,
m.marca,
Sum(f_calc_precio_iva( g.Importe, 0, 0)) AS total_gasto
FROM
gastos AS g
INNER JOIN hoja_rutas AS hr ON hr.codigo = g.id_hoja_ruta
INNER JOIN clientes AS cl ON cl.codigo = hr.id_cliente
INNER JOIN vehiculos AS v ON v.codigo = hr.id_vehiculo
INNER JOIN choferes AS cho ON cho.codigo = v.id_chofer
LEFT  JOIN choferes AS gua ON gua.codigo = v.id_guarda
INNER JOIN marcas AS m ON m.codigo = v.id_marca
INNER JOIN tipo_cargas AS tc ON tc.codigo = hr.id_tipo_carga
INNER JOIN localidades AS loc_desde ON loc_desde.codigo = hr.id_localidad_origen
INNER JOIN localidades AS loc_hasta ON loc_hasta.codigo = hr.id_localidad_destino
INNER JOIN provincias AS pd ON pd.codigo = loc_desde.id_provincia
INNER JOIN provincias AS ph ON ph.codigo = loc_hasta.id_provincia

GROUP BY
pd.provincia,
ph.provincia,
loc_hasta.localidad,
loc_hasta.cp,
loc_desde.localidad,
loc_desde.cp,
hr.fecha_ini,
hr.fecha_fin,
hr.Origen,
hr.Destino,
hr.Km_ini,
hr.km_fin,
hr.estado,
v.Patente,
tc.Tipo_carga,
cl.cuit_cuil,
cl.razon_social,
cl.responsable,
cho.nombre,
gua.nombre,
m.marca ;

-- ----------------------------
--  View definition for `v_hoja_ruta`
-- ----------------------------
DROP VIEW IF EXISTS `v_hoja_ruta`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `v_hoja_ruta` AS SELECT
	hr.codigo AS codigo,
	hr.Origen AS Origen,
	hr.Destino AS Destino,
	hr.estado AS estado,
	v.Patente AS Patente,
	v.nombre AS nombre,
	v.modelo AS modelo,
	cl.razon_social AS razon_social,
	cl.responsable AS responsable,
	tc.Tipo_carga AS Tipo_carga,
	ld.localidad AS loc_desde,
	lh.localidad AS loc_hasta,
	lh.cp AS cp_hasta
FROM
	(
		(
			(
				(
					hoja_rutas hr
					JOIN vehiculos v ON v.codigo = hr.id_vehiculo
				)
				JOIN clientes cl ON cl.codigo = hr.id_cliente
			)
			JOIN tipo_cargas tc ON tc.codigo = hr.id_tipo_carga
		)
		JOIN v_localidad_provincia ld ON ld.codigo = hr.id_localidad_origen
	)
JOIN v_localidad_provincia lh ON lh.codigo = hr.id_localidad_destino ;

-- ----------------------------
--  View definition for `v_listado_gastos_hoja_ruta`
-- ----------------------------
DROP VIEW IF EXISTS `v_listado_gastos_hoja_ruta`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `v_listado_gastos_hoja_ruta` AS SELECT
	ve.Patente AS Patente,
	ve.modelo AS modelo,
	m.marca AS marca,
	ch.nombre AS chofer,
	ch.cetegoria AS categoria_chofer,
	gu.cetegoria AS categoria_guardia,
	gu.nombre AS guarda,
	hr.Origen AS Origen,
	hr.Destino AS Destino,
	hr.Km_ini AS Km_ini,
	hr.km_fin AS km_fin,
	f_estado_hoja_ruta (hr.estado) AS estado,
	hr.fecha_ini AS fecha_ini,
	hr.fecha_fin AS fecha_fin,
	g.fecha AS fecha_gasto,
	g.codigo AS nro_gasto,
	g.detalles AS detalle_gasto,
	g.Importe AS importe_gasto,
	f_calc_precio_iva (g.Importe, 0, 0) AS total,
	tg.tipo_gasto AS tipo_gasto,
	cl.razon_social AS razon_social,
	pd.provincia AS prov_desde,
	ph.provincia AS prov_hasta,
	ld.localidad AS loc_desde,
	ld.cp AS cp_desde,
	lh.localidad AS loc_hasta,
	lh.cp AS cp_hasta,
	cl.codigo AS nro_cliente
FROM
	(
		(
			(
				(
					(
						(
							(
								(
									(
										(
											vehiculos ve
											JOIN hoja_rutas hr ON ve.codigo = hr.id_vehiculo
										)
										JOIN gastos g ON g.id_hoja_ruta = hr.codigo
										AND hr.codigo = g.id_hoja_ruta
									)
									JOIN choferes ch ON ch.codigo = ve.id_chofer
								)
								JOIN clientes cl ON cl.codigo = hr.id_cliente
							)
							JOIN localidades ld ON ld.codigo = hr.id_localidad_origen
						)
						JOIN localidades lh ON lh.codigo = hr.id_localidad_destino
					)
					JOIN marcas m ON m.codigo = ve.id_marca
				)
				JOIN tipo_gastos tg ON tg.codigo = g.id_tipo_gasto
			)
			JOIN provincias pd ON pd.codigo = ld.id_provincia
		)
		JOIN provincias ph ON ph.codigo = lh.id_provincia
	)
LEFT JOIN choferes gu ON gu.codigo = ve.id_guarda ;

-- ----------------------------
--  View definition for `v_listado_totales_por_hoja_ruta`
-- ----------------------------
DROP VIEW IF EXISTS `v_listado_totales_por_hoja_ruta`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost`  VIEW `v_listado_totales_por_hoja_ruta` AS SELECT hr.codigo AS codigo, cl.responsable AS responsable, v.Patente AS Patente, hr.kg_carga AS kg_carga, hr.tarifa AS tarifa, Sum((hr.kg_carga * hr.tarifa)) AS sub_total, hr.porcentaje AS porcentaje, Sum((((hr.kg_carga * hr.tarifa) * hr.porcentaje) / 100)) AS comision_chofer, hr.adelanto AS adelanto, Sum((((hr.kg_carga * hr.tarifa) - (((hr.kg_carga * hr.tarifa) * hr.porcentaje) / 100)) - hr.adelanto)) AS total FROM ((hoja_rutas hr JOIN gastos g ON hr.codigo = g.id_hoja_ruta) JOIN clientes cl ON cl.codigo = hr.id_cliente) JOIN vehiculos v ON v.codigo = hr.id_vehiculo GROUP BY hr.codigo, cl.responsable, v.Patente, hr.kg_carga, hr.tarifa, hr.porcentaje, hr.adelanto ;

-- ----------------------------
--  View definition for `v_localidad_provincia`
-- ----------------------------
DROP VIEW IF EXISTS `v_localidad_provincia`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost`  VIEW `v_localidad_provincia` AS SELECT localidades.localidad, localidades.cp, provincias.provincia, localidades.codigo FROM localidades INNER JOIN provincias ON provincias.codigo = localidades.id_provincia ;

-- ----------------------------
--  Procedure definition for `f_calc_precio_iva`
-- ----------------------------
DROP FUNCTION IF EXISTS `f_calc_precio_iva`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `f_calc_precio_iva`(`precio` numeric(15,2),`iva` numeric(15,2),`descuento` numeric(15,2)) RETURNS decimal(15,2)
    NO SQL
    COMMENT 'Funcion que calcula el precio.\r\nPrecio - descuento +iva;\r\n'
BEGIN
	#Routine body goes here...		
	IF descuento>0 THEN
		SET precio = precio - descuento;	
	END IF;

	IF iva>0 THEN			
		SET precio = precio + ((precio*iva)/100);
	END IF;

	RETURN precio;

END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `f_categoria_chofer`
-- ----------------------------
DROP FUNCTION IF EXISTS `f_categoria_chofer`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `f_categoria_chofer`(`categ_chofer` int) RETURNS varchar(10) CHARSET utf8 COLLATE utf8_spanish_ci
    NO SQL
BEGIN
	#Routine body goes here...
			CASE categ_chofer
        
        WHEN 1 THEN
            RETURN 'Inicial';
        WHEN 2 THEN
            RETURN 'Normal';
        WHEN 3 THEN
            RETURN 'Profecional';
        ELSE
            RETURN 'No espesificado';
        END CASE;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `f_estado_hoja_ruta`
-- ----------------------------
DROP FUNCTION IF EXISTS `f_estado_hoja_ruta`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `f_estado_hoja_ruta`(`estado_hoja_ruta` char) RETURNS varchar(10) CHARSET utf8
    NO SQL
    COMMENT 'Muestra el estado de hoja ruta segun la letra del campo ESTADO'
BEGIN
	#Routine body goes here...
			CASE estado_hoja_ruta
        
        WHEN 'I' THEN
            RETURN 'Informado';
        WHEN 'C' THEN
            RETURN 'En Curso';
        WHEN 'T' THEN
            RETURN 'Finalizado';
        ELSE
            RETURN 'No espesificado';
        END CASE;

END
;;
DELIMITER ;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `choferes` VALUES ('1','Juan Altamirano','Cora 223','1974-12-03','4563214',NULL,NULL,'1982-12-14','1',NULL), ('2','Lucas Agirre','pedro ferrer 4637','1974-12-03','4563214',NULL,NULL,'1982-12-14','1',NULL);
INSERT INTO `clientes` VALUES ('1','25643125','Imprenta LUZ','Mariano List','4569856',NULL,NULL,NULL,'2');
INSERT INTO `gastos` VALUES ('1','2015-12-09','fdsghfdgdfgfdh','1234.00','4','1',NULL), ('2','2015-12-09','cacakjkljslkds','1340.00','4','3',NULL), ('3','2015-12-10','fgdhg','345.00','1','2',NULL), ('4','2015-12-10','fghdhgd','45.00','4','2',NULL), ('5','2015-12-10','fdgsdfhfjhfg','120.00','4','4',NULL), ('6','2015-12-10','hkjhkg','120.00','3','4',NULL), ('7','2015-12-10','hgjfjfghjf','36.00','3','2',NULL), ('8','2015-12-10','jhv,hfk hkgk','36.00','3','2',NULL);
INSERT INTO `gastos_mantenimientos` VALUES ('1','carla kwjsadfgfh','2015-12-10','1','1',NULL);
INSERT INTO `hoja_mantenimientos` VALUES ('1','10/12/2015','10/12/2015','1','1','3',NULL);
INSERT INTO `hoja_rutas` VALUES ('1','2015-12-03',NULL,'Carto 789','Iriondo 908','123552',NULL,'A','2','1','1','2','2','452','256.00','15.00','0.00',NULL), ('2','2015-12-03',NULL,'aaa','bbbb','12346',NULL,'A','1','1','3','1','2','0','420.00','15.00','0.00',NULL), ('3','2015-12-03',NULL,'aaashasdsakj','dfñlkjgdfljglskdjg',NULL,NULL,'A','2','1','2','2','2','0','158.00','15.00','0.00',NULL), ('4','2015-12-10',NULL,'fghfghgf','fgdhgdhg','14788565',NULL,'A','2','1','3','2','2','120','420.00','15.00','100.00',NULL);
INSERT INTO `localidades` VALUES ('1','Reconquista','3560','1'), ('2','Santa Fe','3000','1');
INSERT INTO `marcas` VALUES ('1','IVECO','Duna'), ('2','FIAT','Ducatto');
INSERT INTO `nivel_permisos_usuario` VALUES ('0','','0'), ('0','choferes','1'), ('0','gastos','1'), ('0','gastos_mantenimientos','1'), ('0','hoja_rutas','1'), ('0','localidades','1'), ('0','marcas','1'), ('0','provincias','1'), ('0','talleres','1'), ('0','tipo_cargas','1'), ('0','tipo_gastos','1'), ('0','tipo_mantenimientos','1'), ('0','usuarios','1'), ('0','vehiculos','1');
INSERT INTO `nivel_usuario` VALUES ('-1','Administrator'), ('0','Default');
INSERT INTO `provincias` VALUES ('1','Santa Fe'), ('2','Entre Rios'), ('3','Bs As');
INSERT INTO `talleres` VALUES ('1','El patito','Iriondo 908','4569872',NULL,NULL), ('2','Talleres IVECO','Mendoza 234',NULL,NULL,NULL);
INSERT INTO `tipo_cargas` VALUES ('1','Piedras','256.00','15.00'), ('2','CAL','158.00','15.00'), ('3','Arena','420.00','15.00');
INSERT INTO `tipo_gastos` VALUES ('1','Cambio de bateria','M'), ('2','Viaticos chofer','R'), ('3','Peajes','R'), ('4','Combustible','R');
INSERT INTO `tipo_mantenimientos` VALUES ('1','Cambio de aceite y filtros'), ('2','Neumático Nuevos'), ('3','Cambio de bateria');
INSERT INTO `usuarios` VALUES ('1','admin','202cb962ac59075b964b07152d234b70','Cintes Sergio','scintes@msn.com','1'), ('2','sergio','202cb962ac59075b964b07152d234b70','Ruben Cintes','scintes@msn.com','1'), ('3','ruben','202cb962ac59075b964b07152d234b70',NULL,'scintes@msn.com','1');
INSERT INTO `vehiculos` VALUES ('1','FRT-123','6','IVECO Amar','2008','1',NULL,'1'), ('2','YUI-112','8','FIAT LUZ 3','2015','2',NULL,'2');
