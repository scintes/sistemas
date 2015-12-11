/*
MySQL Backup
Source Server Version: 5.6.26
Source Database: camioneros
Date: 02/12/2015 20:40:40
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Ficha personal del cliente de la empresa';

-- ----------------------------
--  Table structure for `gastos`
-- ----------------------------
DROP TABLE IF EXISTS `gastos`;
CREATE TABLE `gastos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `detalles` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `iva` decimal(10,2) DEFAULT NULL,
  `Importe` decimal(15,2) DEFAULT NULL,
  `id_tipo_gasto` int(11) DEFAULT NULL,
  `id_hoja_ruta` int(11) DEFAULT NULL,
  `id_hoja_mantenimiento` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `gastos_ibfk_1` (`id_tipo_gasto`),
  KEY `gastos_ibfk_2` (`id_hoja_ruta`),
  KEY `gastos_ibfk_3` (`id_hoja_mantenimiento`),
  CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`id_tipo_gasto`) REFERENCES `tipo_gastos` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `gastos_ibfk_2` FOREIGN KEY (`id_hoja_ruta`) REFERENCES `hoja_rutas` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `gastos_ibfk_3` FOREIGN KEY (`id_hoja_mantenimiento`) REFERENCES `hoja_mantenimientos` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
  PRIMARY KEY (`codigo`),
  KEY `hoja_mantenimientos_ibfk_2` (`id_taller`),
  KEY `hoja_mantenimientos_ibfk_1` (`id_vehiculo`),
  KEY `hoja_mantenimientos_ibfk_3` (`id_tipo_mantenimiento`),
  CONSTRAINT `hoja_mantenimientos_ibfk_1` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_mantenimientos_ibfk_2` FOREIGN KEY (`id_taller`) REFERENCES `talleres` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_mantenimientos_ibfk_3` FOREIGN KEY (`id_tipo_mantenimiento`) REFERENCES `tipo_mantenimientos` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
  PRIMARY KEY (`codigo`),
  KEY `hoja_rutas_ibfk_1` (`id_vehiculo`),
  KEY `hoja_rutas_ibfk_2` (`id_cliente`),
  KEY `hoja_rutas_ibfk_4` (`id_localidad_origen`),
  KEY `hoja_rutas_ibfk_5` (`id_localidad_destino`),
  KEY `hoja_rutas_ibfk_3` (`id_tipo_carga`),
  CONSTRAINT `hoja_rutas_ibfk_1` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_rutas_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_rutas_ibfk_3` FOREIGN KEY (`id_tipo_carga`) REFERENCES `tipo_cargas` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_rutas_ibfk_4` FOREIGN KEY (`id_localidad_origen`) REFERENCES `localidades` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `hoja_rutas_ibfk_5` FOREIGN KEY (`id_localidad_destino`) REFERENCES `localidades` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `provincias`
-- ----------------------------
DROP TABLE IF EXISTS `provincias`;
CREATE TABLE `provincias` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `provincia` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tipo_gastos`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_gastos`;
CREATE TABLE `tipo_gastos` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `tipo_gasto` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `clase` varchar(1) COLLATE utf8_spanish2_ci DEFAULT 'R' COMMENT 'R: tipo de gasto para hoja _ruta; M: tipo de gasto para Mantenimiento',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tipo_mantenimientos`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_mantenimientos`;
CREATE TABLE `tipo_mantenimientos` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `tipo_mantenimiento` varchar(150) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
  `id_tipo_carga` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `vehiculos_ibfk_1` (`id_chofer`),
  KEY `vehiculos_ibfk_2` (`id_guarda`),
  KEY `vehiculos_ibfk_3` (`id_marca`),
  KEY `vehiculos_ibfk_4` (`id_tipo_carga`),
  CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`id_chofer`) REFERENCES `choferes` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `vehiculos_ibfk_2` FOREIGN KEY (`id_guarda`) REFERENCES `choferes` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `vehiculos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `vehiculos_ibfk_4` FOREIGN KEY (`id_tipo_carga`) REFERENCES `tipo_cargas` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  View definition for `v_gastos_hoja_mantenimiento`
-- ----------------------------
DROP VIEW IF EXISTS `v_gastos_hoja_mantenimiento`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `v_gastos_hoja_mantenimiento` AS SELECT
hr.fecha_ini,
hr.fecha_fin,
ve.Patente,
t.taller,
tm.tipo_mantenimiento,
hr.codigo AS nro_hoja_mantenimiento,
(select SUM( f_calc_precio_iva(g.Importe, g.iva,0) ) from gastos AS g where g.id_hoja_mantenimiento = hr.codigo) AS total_gasto
FROM
hoja_mantenimientos AS hr
INNER JOIN vehiculos AS ve ON ve.codigo = hr.id_vehiculo
INNER JOIN talleres AS t ON t.codigo = hr.id_taller
INNER JOIN tipo_mantenimientos AS tm ON tm.codigo = hr.id_tipo_mantenimiento ;

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
Sum(f_calc_precio_iva( g.Importe, g.iva, 0)) AS total_gasto
FROM
gastos AS g
INNER JOIN hoja_rutas AS hr ON hr.codigo = g.id_hoja_ruta
INNER JOIN clientes AS cl ON cl.codigo = hr.id_cliente
INNER JOIN vehiculos AS v ON v.codigo = hr.id_vehiculo
INNER JOIN choferes AS cho ON cho.codigo = v.id_chofer
LEFT  JOIN choferes AS gua ON gua.codigo = v.id_guarda
INNER JOIN marcas AS m ON m.codigo = v.id_tipo_carga
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
m.marca,
g.iva ;

-- ----------------------------
--  View definition for `v_hoja_manteniminto`
-- ----------------------------
DROP VIEW IF EXISTS `v_hoja_manteniminto`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost`  VIEW `v_hoja_manteniminto` AS SELECT tm.tipo_mantenimiento AS tipo_mantenimiento, t.taller AS taller, v.Patente AS Patente, v.modelo AS modelo, v.nombre AS nombre, hm.fecha_ini AS fecha_ini, hm.fecha_fin AS fecha_fin, hm.codigo AS codigo FROM ((hoja_mantenimientos hm JOIN vehiculos v ON v.codigo = hm.id_vehiculo) JOIN talleres t ON t.codigo = hm.id_taller) JOIN tipo_mantenimientos tm ON tm.codigo = hm.id_tipo_mantenimiento ;

-- ----------------------------
--  View definition for `v_hoja_ruta`
-- ----------------------------
DROP VIEW IF EXISTS `v_hoja_ruta`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost`  VIEW `v_hoja_ruta` AS SELECT hr.codigo, hr.Origen, hr.Destino, hr.estado, v.Patente, v.nombre, v.modelo, cl.razon_social, cl.responsable, tc.Tipo_carga, ld.localidad AS loc_desde, lh.localidad AS loc_hasta, lh.cp AS cp_hasta FROM hoja_rutas hr INNER JOIN vehiculos v ON v.codigo = hr.id_vehiculo INNER JOIN clientes cl ON cl.codigo = hr.id_cliente INNER JOIN tipo_cargas tc ON tc.codigo = hr.id_tipo_carga INNER JOIN v_localidad_provincia ld ON ld.codigo = hr.id_localidad_origen INNER JOIN v_localidad_provincia lh ON lh.codigo = hr.id_localidad_destino ;

-- ----------------------------
--  View definition for `v_listado_gastos_hoja_ruta`
-- ----------------------------
DROP VIEW IF EXISTS `v_listado_gastos_hoja_ruta`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `v_listado_gastos_hoja_ruta` AS SELECT
ve.Patente,
ve.modelo,
m.marca,
ch.nombre AS chofer,
ch.cetegoria AS categoria_chofer,
gu.cetegoria AS categoria_guardia,
gu.nombre AS guarda,
hr.Origen,
hr.Destino,
hr.Km_ini,
hr.km_fin,
f_estado_hoja_ruta(hr.estado) AS estado,
hr.fecha_ini,
hr.fecha_fin,
g.fecha AS fecha_gasto,
g.codigo AS nro_gasto,
g.detalles AS detalle_gasto,
g.iva AS iva_gasto,
g.Importe AS importe_gasto,
f_calc_precio_iva(g.Importe, g.iva,0) AS total,
tg.tipo_gasto,
cl.razon_social,
pd.provincia AS prov_desde,
ph.provincia AS prov_hasta,
ld.localidad AS loc_desde,
ld.cp AS cp_desde,
lh.localidad AS loc_hasta,
lh.cp AS cp_hasta,
cl.codigo AS nro_cliente
FROM
vehiculos AS ve
INNER JOIN hoja_rutas AS hr ON ve.codigo = hr.id_vehiculo
INNER JOIN gastos AS g ON g.id_hoja_ruta = hr.codigo AND hr.codigo = g.id_hoja_ruta
INNER JOIN choferes AS ch ON ch.codigo = ve.id_chofer
INNER JOIN clientes AS cl ON cl.codigo = hr.id_cliente
INNER JOIN localidades AS ld ON ld.codigo = hr.id_localidad_origen
INNER JOIN localidades AS lh ON lh.codigo = hr.id_localidad_destino

INNER JOIN marcas AS m ON m.codigo = ve.id_marca

INNER JOIN tipo_gastos AS tg ON tg.codigo = g.id_tipo_gasto

INNER JOIN provincias AS pd ON pd.codigo = ld.id_provincia
INNER JOIN provincias AS ph ON ph.codigo = lh.id_provincia 

LEFT JOIN choferes AS gu ON gu.codigo = ve.id_guarda ;

-- ----------------------------
--  View definition for `v_localidad_provincia`
-- ----------------------------
DROP VIEW IF EXISTS `v_localidad_provincia`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost`  VIEW `v_localidad_provincia` AS SELECT localidades.localidad, localidades.cp, provincias.provincia, localidades.codigo FROM localidades INNER JOIN provincias ON provincias.codigo = localidades.id_provincia ;

-- ----------------------------
--  Procedure definition for `f_calc_precio_iva`
-- ----------------------------
DROP FUNCTION IF EXISTS `f_calc_precio_iva`;
DELIMITER //
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
//
DELIMITER ;

-- ----------------------------
--  Procedure definition for `f_categoria_chofer`
-- ----------------------------
DROP FUNCTION IF EXISTS `f_categoria_chofer`;
DELIMITER //
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
//
DELIMITER ;

-- ----------------------------
--  Procedure definition for `f_estado_hoja_ruta`
-- ----------------------------
DROP FUNCTION IF EXISTS `f_estado_hoja_ruta`;
DELIMITER //
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
//
DELIMITER ;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `talleres` VALUES ('1','Taller el TITO','Venezuela 3890','0342-4951177','154225669',NULL), ('2','El Patito','corti 980','435467',NULL,NULL);
INSERT INTO `tipo_cargas` VALUES ('1','Piedras x 1 semi','4900.00',NULL), ('2','Piedras x 1/2 semi','3800.00',NULL);
INSERT INTO `tipo_gastos` VALUES ('1','Carga Combustible','R'), ('2','Peajes','R'), ('3','Viaticos','R'), ('4','Reparación - Gomería','M'), ('5','Reparación - Eléctrica','M'), ('6','Cambio de bateria','M');
INSERT INTO `tipo_mantenimientos` VALUES ('1','Service en garantia 0 a 10000 km'), ('2','Service en garantia10000 a 30000 km'), ('3','Rotura mecanica/eléctrica en ruta'), ('4','Mantenimiento Programado');
INSERT INTO `provincias` VALUES ('1','Santa Fe'), ('2','Entre Rios');
INSERT INTO `localidades` VALUES ('1','Reconquista','3560','1'), ('2','santa fe','3000','1');

INSERT INTO `choferes` VALUES ('1','Jose','Verdad y Justicia 789','1974-11-25','0342-4695401',NULL,'verdad_justicia@hotmail.com','2015-11-25','1',NULL), ('2','ruben','vertyui 899','1985-11-28','0342-4567789',NULL,NULL,'2015-11-28','1',NULL);
INSERT INTO `clientes` VALUES ('1','2344',NULL,NULL,NULL,NULL,NULL,NULL,NULL), ('2','24194791','HardSoft Sistemas','cintes Sergio','03482-4567887',NULL,NULL,NULL,'1');
INSERT INTO `gastos` VALUES ('1','2015-11-29','mano de obra','21.00','1200.00','6',NULL,'4'), ('3','2015-11-29','ypf','21.00','1000.00','1',NULL,'4'), ('4','2016-11-30',NULL,'21.00','1209.00','6','1',NULL);
INSERT INTO `marcas` VALUES ('1','IVECO','3060'), ('2','FORD','Culler 12'), ('3','Nissan','Furgon');
INSERT INTO `vehiculos` VALUES ('1','BNA-963','6','IVE-1','2014','1',NULL,'1',NULL), ('2','asd-456','8','Volvo amar','2001','1','2','2',NULL);
INSERT INTO `hoja_mantenimientos` VALUES ('1','28/11/2015',NULL,'1','1','3'), ('2','28/11/2015',NULL,'1','2','3'), ('3','28/11/2015','28/11/2015','1','2','1'), ('4','29/11/2015',NULL,'2','1','3');
INSERT INTO `hoja_rutas` VALUES ('1','2016-11-30',NULL,'casa de carlos','casa de andres','129099',NULL,'A','1','2','2','1','2',NULL,NULL,'0.00');
