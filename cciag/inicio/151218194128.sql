/*
MySQL Backup
Source Server Version: 5.5.24
Source Database: cciag-sauceviejo
Date: 18/12/2015 19:41:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `actividad`
-- ----------------------------
DROP TABLE IF EXISTS `actividad`;
CREATE TABLE `actividad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_rubro` int(11) DEFAULT NULL,
  `actividad` varchar(100) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `activa` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_rubro` (`id_rubro`),
  CONSTRAINT `actividad_ibfk_1` FOREIGN KEY (`id_rubro`) REFERENCES `rubros` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `detalles`
-- ----------------------------
DROP TABLE IF EXISTS `detalles`;
CREATE TABLE `detalles` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8_spanish_ci,
  `activa` varchar(1) COLLATE utf8_spanish_ci DEFAULT 'S',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `detalle_deudas`
-- ----------------------------
DROP TABLE IF EXISTS `detalle_deudas`;
CREATE TABLE `detalle_deudas` (
  `id_deuda` int(11) DEFAULT NULL,
  `detalle` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `importe` decimal(15,2) DEFAULT NULL,
  KEY `id_deuda` (`id_deuda`),
  CONSTRAINT `detalle_deudas_ibfk_1` FOREIGN KEY (`id_deuda`) REFERENCES `deudas` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `deudas`
-- ----------------------------
DROP TABLE IF EXISTS `deudas`;
CREATE TABLE `deudas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_socio` int(11) DEFAULT NULL,
  `id_usuario` int(255) DEFAULT NULL,
  `mes` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Indica el mes de deuda',
  `anio` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'indica el anio de deuda',
  `fecha` date DEFAULT NULL,
  `monto` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deudas_ibfk_1` (`id_usuario`),
  KEY `id_socio` (`id_socio`),
  CONSTRAINT `deudas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON UPDATE NO ACTION,
  CONSTRAINT `deudas_ibfk_2` FOREIGN KEY (`id_socio`) REFERENCES `socios` (`socio_nro`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `montos`
-- ----------------------------
DROP TABLE IF EXISTS `montos`;
CREATE TABLE `montos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `importe` decimal(15,2) DEFAULT NULL COMMENT 'Determina el valor del registro;',
  `fecha_creacion` date DEFAULT NULL COMMENT 'determina cuando se modifico el registro;',
  `activa` varchar(1) COLLATE utf8_spanish_ci DEFAULT 'S' COMMENT 'Determina si esta vigente o no el registro',
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `montos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `pagos`
-- ----------------------------
DROP TABLE IF EXISTS `pagos`;
CREATE TABLE `pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_deuda` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `monto` decimal(15,2) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_deuda` (`id_deuda`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_deuda`) REFERENCES `deudas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `rubros`
-- ----------------------------
DROP TABLE IF EXISTS `rubros`;
CREATE TABLE `rubros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rubro` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Descripcion` varchar(400) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activa` varchar(1) COLLATE utf8_spanish_ci DEFAULT 'S',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `seguimiento_tramites`
-- ----------------------------
DROP TABLE IF EXISTS `seguimiento_tramites`;
CREATE TABLE `seguimiento_tramites` (
  `id_tramite` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `titulo` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8_spanish_ci,
  `id_usuario` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `archivo` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id_tramite`,`fecha`,`hora`),
  CONSTRAINT `seguimiento_tramites_ibfk_1` FOREIGN KEY (`id_tramite`) REFERENCES `tramites` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `socios`
-- ----------------------------
DROP TABLE IF EXISTS `socios`;
CREATE TABLE `socios` (
  `socio_nro` int(11) NOT NULL AUTO_INCREMENT,
  `id_actividad` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `propietario` varchar(255) DEFAULT NULL,
  `comercio` varchar(255) DEFAULT NULL,
  `direccion_comercio` varchar(255) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `tel` varchar(40) DEFAULT NULL,
  `cel` varchar(40) DEFAULT NULL,
  `activo` varchar(1) DEFAULT NULL,
  `cuit_cuil` varchar(14) DEFAULT NULL,
  PRIMARY KEY (`socio_nro`),
  UNIQUE KEY `NOMBRE` (`propietario`,`comercio`),
  KEY `id_rubro` (`id_actividad`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `socios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `socios_ibfk_3` FOREIGN KEY (`id_actividad`) REFERENCES `actividad` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=314 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `socios_cuotas`
-- ----------------------------
DROP TABLE IF EXISTS `socios_cuotas`;
CREATE TABLE `socios_cuotas` (
  `id_socio` int(11) DEFAULT NULL,
  `id_montos` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  KEY `id_socio` (`id_socio`),
  KEY `id_montos` (`id_montos`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `socios_cuotas_ibfk_1` FOREIGN KEY (`id_socio`) REFERENCES `socios` (`socio_nro`) ON UPDATE CASCADE,
  CONSTRAINT `socios_cuotas_ibfk_2` FOREIGN KEY (`id_montos`) REFERENCES `montos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `socios_cuotas_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `socios_detalles`
-- ----------------------------
DROP TABLE IF EXISTS `socios_detalles`;
CREATE TABLE `socios_detalles` (
  `id_socio` int(11) DEFAULT NULL,
  `id_detalles` int(11) DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  KEY `id_socio` (`id_socio`),
  KEY `id_detalles` (`id_detalles`),
  CONSTRAINT `socios_detalles_ibfk_1` FOREIGN KEY (`id_socio`) REFERENCES `socios` (`socio_nro`) ON UPDATE CASCADE,
  CONSTRAINT `socios_detalles_ibfk_2` FOREIGN KEY (`id_detalles`) REFERENCES `detalles` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `tramites`
-- ----------------------------
DROP TABLE IF EXISTS `tramites`;
CREATE TABLE `tramites` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Descripcion` text COLLATE utf8_spanish_ci,
  `fecha` date DEFAULT NULL,
  `id_usuario` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` varchar(1) COLLATE utf8_spanish_ci DEFAULT NULL,
  `archivo` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `userlevelpermissions`
-- ----------------------------
DROP TABLE IF EXISTS `userlevelpermissions`;
CREATE TABLE `userlevelpermissions` (
  `userlevelid` int(11) NOT NULL,
  `tablename` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `permission` int(11) NOT NULL,
  PRIMARY KEY (`userlevelid`,`tablename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `userlevels`
-- ----------------------------
DROP TABLE IF EXISTS `userlevels`;
CREATE TABLE `userlevels` (
  `userlevelid` int(11) NOT NULL,
  `userlevelname` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`userlevelid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `usuario`
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(10) CHARACTER SET latin1 NOT NULL,
  `contrasenia` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cel` varchar(16) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  View definition for `cant_socios_actividad`
-- ----------------------------
DROP VIEW IF EXISTS `cant_socios_actividad`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cant_socios_actividad` AS select count(`s`.`socio_nro`) AS `socio_nro`,`a`.`actividad` AS `actividad`,`r`.`rubro` AS `rubro` from ((`socios` `s` join `actividad` `a` on((`a`.`id` = `s`.`id_actividad`))) join `rubros` `r` on((`r`.`id` = `a`.`id_rubro`))) where ((`a`.`activa` = 'S') and (`r`.`activa` = 'S')) group by `r`.`rubro`,`a`.`actividad`,`s`.`id_actividad`,`a`.`activa`;

-- ----------------------------
--  View definition for `detalle_asociado`
-- ----------------------------
DROP VIEW IF EXISTS `detalle_asociado`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detalle_asociado` AS select `s`.`socio_nro` AS `socio_nro`,`s`.`comercio` AS `comercio`,sum(`m`.`importe`) AS `total` from ((`socios_cuotas` `s_c` join `socios` `s` on((`s`.`socio_nro` = `s_c`.`id_socio`))) join `montos` `m` on((`m`.`id` = `s_c`.`id_montos`))) group by `s`.`socio_nro`,`s`.`comercio` order by `s`.`propietario`,`s`.`comercio`,`s`.`cuit_cuil`,`s`.`socio_nro`;

-- ----------------------------
--  View definition for `resumen_por_mes_de_pago`
-- ----------------------------
DROP VIEW IF EXISTS `resumen_por_mes_de_pago`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `resumen_por_mes_de_pago` AS select `s`.`socio_nro` AS `socio_nro`,`s`.`comercio` AS `comercio`,`d`.`mes` AS `mes`,`d`.`anio` AS `anio`,sum(`p`.`monto`) AS `Pagos` from ((`deudas` `d` join `socios` `s` on((`s`.`socio_nro` = `d`.`id_socio`))) left join `pagos` `p` on((`d`.`id` = `p`.`id_deuda`))) group by `s`.`socio_nro`,`s`.`comercio`,`d`.`mes`,`d`.`anio`;

-- ----------------------------
--  View definition for `socios_activos`
-- ----------------------------
DROP VIEW IF EXISTS `socios_activos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `socios_activos` AS select `socios`.`socio_nro` AS `socio_nro`,`actividad`.`actividad` AS `rubro1`,`socios`.`propietario` AS `propietario`,`socios`.`comercio` AS `comercio`,`socios`.`direccion_comercio` AS `direccion_comercio` from (`actividad` join `socios` on((`socios`.`id_actividad` = `actividad`.`id`))) where (`socios`.`activo` = 'S');

-- ----------------------------
--  View definition for `v_ configuracion_deuda`
-- ----------------------------
DROP VIEW IF EXISTS `v_ configuracion_deuda`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_ configuracion_deuda` AS select `sc`.`id_socio` AS `id_socio`,`sc`.`id_montos` AS `id_montos`,`sc`.`fecha` AS `fecha`,`m`.`descripcion` AS `descripcion`,`m`.`importe` AS `importe`,`m`.`fecha_creacion` AS `fecha_creacion`,`m`.`activa` AS `activa` from (`socios_cuotas` `sc` join `montos` `m` on((`sc`.`id_montos` = `m`.`id`)));

-- ----------------------------
--  View definition for `v_db_rubro_actividad`
-- ----------------------------
DROP VIEW IF EXISTS `v_db_rubro_actividad`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_db_rubro_actividad` AS select `r`.`rubro` AS `rubro`,`a`.`actividad` AS `actividad`,`r`.`id` AS `id_rubro`,`a`.`id` AS `id_actividad` from (`actividad` `a` join `rubros` `r` on(((`r`.`id` = `a`.`id_rubro`) and (`r`.`activa` = 'S') and (`a`.`activa` = 'S')))) order by `r`.`rubro`,`a`.`actividad`;

-- ----------------------------
--  View definition for `v_db_rubro_actividad_socio`
-- ----------------------------
DROP VIEW IF EXISTS `v_db_rubro_actividad_socio`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_db_rubro_actividad_socio` AS select `a`.`id_rubro` AS `id_rubro`,`s`.`id_actividad` AS `id_actividad`,`r`.`rubro` AS `rubro`,`a`.`actividad` AS `actividad`,`s`.`propietario` AS `propietario`,`s`.`comercio` AS `comercio`,`s`.`direccion_comercio` AS `direccion_comercio`,`s`.`mail` AS `mail`,`s`.`tel` AS `tel`,`s`.`cel` AS `cel`,`s`.`cuit_cuil` AS `cuit_cuil` from ((`rubros` `r` join `actividad` `a` on((`a`.`id_rubro` = `r`.`id`))) join `socios` `s` on((`s`.`id_actividad` = `a`.`id`)));

-- ----------------------------
--  View definition for `v_deuda_adquirida`
-- ----------------------------
DROP VIEW IF EXISTS `v_deuda_adquirida`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_deuda_adquirida` AS select `s`.`propietario` AS `propietario`,`s`.`comercio` AS `comercio`,`s`.`direccion_comercio` AS `direccion_comercio`,`s`.`mail` AS `mail`,`s`.`tel` AS `tel`,`s`.`cel` AS `cel`,`d`.`mes` AS `mes`,`d`.`anio` AS `anio`,`d`.`fecha` AS `fecha`,`d`.`monto` AS `monto`,`dd`.`detalle` AS `detalle`,`dd`.`importe` AS `importe`,`dd`.`id_deuda` AS `id_deuda`,`d`.`id_socio` AS `id_socio` from ((`socios` `s` join `deudas` `d` on((`d`.`id_socio` = `s`.`socio_nro`))) join `detalle_deudas` `dd` on((`dd`.`id_deuda` = `d`.`id`)));

-- ----------------------------
--  Procedure definition for `p_generar_deuda_mensual`
-- ----------------------------
DROP PROCEDURE IF EXISTS `p_generar_deuda_mensual`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_generar_deuda_mensual`(IN `mes` int,IN `anio` int)
BEGIN
-- Declaración de variables
DECLARE ID_VENDEDOR INT;
DECLARE ACUMULADO_VENTAS INT;

DECLARE TEMPV INT DEFAULT 0;
DECLARE TEMPID INT DEFAULT 0;

-- Definición de la consulta
DECLARE cd_cursor CURSOR FOR

SELECT V.IDVENDEDOR,SUM(DF.UNIDADES*DF.PRECIO)
FROM VENDEDOR AS V 
		INNER JOIN FACTURA AS F ON V.IDVENDEDOR = F.IDVENDEDOR  AND (F.FECHA BETWEEN fecha_inicio AND fecha_final)
		INNER JOIN DETALLEFACTURA AS DF	ON F.IDFACTURA = DF.IDFACTURA
GROUP BY V.IDVENDEDOR;

-- Declaración de un manejador de error tipo NOT FOUND
DECLARE CONTINUE HANDLER FOR NOT FOUND SET @hecho = TRUE;

-- Abrimos el cursor
OPEN cd_cursor;

-- Comenzamos nuestro bucle de lectura
loop1: LOOP

-- Obtenemos la primera fila en la variables correspondientes
FETCH cd_cursor INTO ID_VENDEDOR, ACUMULADO_VENTAS;

-- Si el cursor se quedó sin elementos,
-- entonces nos salimos del bucle
IF @hecho THEN
LEAVE loop1;
END IF;

-- Guardamos el acumulado de ventas y el código
-- si el vendedor actual tiene mejores resultados
IF ACUMULADO_VENTAS>=TEMPV THEN
SET TEMPV = ACUMULADO_VENTAS;
SET TEMPID = ID_VENDEDOR;
END IF;

END LOOP loop1;

-- Cerramos el cursor
CLOSE cd_cursor;

-- Imprimimos el código y total acumulado de ventas del vendedor
SELECT  TEMPID AS CODIGO_VENDEDOR, TEMPV AS TOTAL_VENTAS;	

END
;;
DELIMITER ;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `actividad` VALUES ('1','1','Alojamientos','Cabañas, Hoteles y Apart.','S'), ('2','1','Súper-Mercado','Tiendas de comestibles y bazar','S'), ('3','4','Asociaciones, Vecinales, Fundaciones','','S'), ('4','1','Ferreterías','','S'), ('5','1','Autopartes','Comercialización de autos partes de autos, camionetas y camiones','S'), ('6','3','Profesionales-Arquitectura','Arquitectos he ingenieros civiles ','S'), ('7','3','Camping','Explotación de terreno para carpas y casas rodantes.','S'), ('8','3','Oficios Varios','','S'), ('9','3','Cocheras y guardería','Se refiere al alquiler de terreno para autos, camionetas camiones y vehículos náuticos','S'), ('10','1','Viveros y Florerías','','S'), ('11','3','Profesionales-Contables','Se refiere a los contadores y auditores contables.','S'), ('12','1','Heladerías','Se refieren a la elaboración y/o venta de helados','S'), ('13','3','Profesionales-Médicos','Se refieren a los consultorios, clínicas, y sanatorios','S'), ('14','1','Mini-Mercado y kioscos','Se refiere venta de comestibles chicos y además los kioscos.','S'), ('15','1','Indumentarias','Se refiere a todas las tiendas de ropas y mercerías.','S'), ('16','1','Servicios Varios','','S'), ('17','1','Bazares-Regalarías','','S'), ('18','3','Profesionales-Farmacéuticos','Se refiere a los farmacéuticos y/o farmacias.','S'), ('19','1','Vinoteca','Se refiere a venta de vino, y vinos espumantes.','S'), ('20','3','Profesionales-Jurídico','Se refiere a los abobados, jueces y escribanos.','S'), ('21','3','Aseguradoras','','S'), ('22','3','Oficios-Electricista','Se refiere a todos los electricista industrial y domiciliarios.','S'), ('23','1','Zapaterías','Se refiere a venta de calzados.','S'), ('24','1','Automotores','Se refiere a la comercialización de autos, camionetas y camiones.','S'), ('25','1','Camping','Se refiere a la comercialización de bienes usados para el camping y la pesca.','S'), ('26','3','Peluquería','','S'), ('27','1','Panadería','Se refiere a la elaboración y/o venta de productos de panaderías','S'), ('28','3','Profesionales-Veterinaria','','S'), ('29','1','Comercio-Gastronomía','Se refiere a los restaurantes, patios cerveceros.','S'), ('30','3','Mayoristas','','S'), ('31','1','Corralones','Se refiere a la venta de bienes para la construcción.(excluida ferreterías)','S'), ('32','1','Pañalera','','S'), ('33','3','Profesionales-Geriátricos','','S'), ('34','3','Publicidad','Se refiere a la producción y transmisión de revistas, diarios, radio, televisión','S'), ('35','1','Pescaderías','Se refiere a la venta de peces de ríos y mar.','S'), ('36','1','Carnicerías','Se refiere a la venta de carne porcina y bovina.','S'), ('37','1','Náutica','Se refiere a la comercialización de embarcaciones de agua.','S'), ('38','3','Servicios-Electromecánicos','Se refiere a la reparación de motores, y/o electrodomésticos.','S'), ('39','1','Forrajearía','','S'), ('40','3','Oficios-Plomerías','','S'), ('41','3','Servicios varios','','S'), ('42','3','Mutuales','','S'), ('43','1','Pollería','','S'), ('44','3','Auxilio Mecánicos','','S'), ('45','3','Profesionales-Mandataria','','S'), ('46','1','Informática','','S'), ('47',NULL,'Rotiserias y viandas','','S'), ('48','1','Cerrajería','','S'), ('49','1','Electrodomésticos','','S'), ('50','3','Gimnasios','','S'), ('51','1','Librerías','','S'), ('52','1','Estéticas','','S'), ('53','2','Industria Metalúrgicas','','S'), ('54','3','Profesionales-Ingenieros Agrónomo','','S'), ('55','2','Laboratorios','','S');
INSERT INTO `detalles` VALUES ('1','Autorizo Revista','El socio autoriza a publicar su empresa en la página como en la revista.','S'), ('2','Autorizo Email.','El socio autoriza a publicar tanto en la página como en la revista el MAIL de su comercio.','S'), ('3','Autorizo Tel.','El socio autoriza a publicar tanto en la página como en la revista los TELEFONOS de su comercio.','S'), ('4','Autorizo Dirección','El socio autoriza a publicar tanto en la página como en la revista las DIERECCIONES de su comercio.','S'), ('5','Autorizo Horarios','El socio autoriza a publicar tanto en la página como en la revista los horarios de su comercio','S');
INSERT INTO `montos` VALUES ('1','Cuota base','25.00','2015-07-21','S','1'), ('2','Cuota Empresa','50.00','2015-07-24','S','1'), ('3','Propaganda 1','100.00','2015-07-24','S','1'), ('4','Propaganda 2','200.00','2015-07-24','S','1'), ('5','Propaganda 3','300.00','2015-07-24','S','1');
INSERT INTO `rubros` VALUES ('1','Comercio',' Comercio minorista de artículos medicinales de aplicación humana.','S'), ('2','Industria','Ingresos provenientes de la fabricación, elaboración o\r\ntransformación de frutos, productos, materias primas y/o insumos, por\r\nlas ventas al por mayor y siempre que no tengan previsto otro\r\ntratamiento. \r','S'), ('3','Empresas','Empresas de radiofonía y televisión que perciban ingresos de sus\r\na) Empresas de radiofonía y televisión que perciban ingresos de sus receptores. b) Servicios de salones de baile, confiterías bailables y similares.  c) Todas las actividades comerciales y de servicios que no tengan expresamente previsto otro tratamiento. ','S'), ('4','Sin fines de lucro','Abarca a fundaciones, vecinales, etc','S');
INSERT INTO `socios` VALUES ('1','1','1','ACOSTA, Sergio','Cabañas Don Rodolfo','Méjico y el Rio','','',NULL,'S',''), ('2','29','1','ACUÑA LILIANA','ENCERES LILIANA','AZOPARDO','','',NULL,'S',''), ('3','2','1','AEINEN NORMA','SUPER SAN CAYETANO','RUTA 11  5017','','',NULL,'S',''), ('4','3','1','ALANIZ SONIA','FUNACION LINEA VERDE','RUTA 11','','',NULL,'S',''), ('5','47','1','ALARCON MARIA PILAR','PARADOR EL PARQUE','AROMOS Y MARGARITA','','',NULL,'S',''), ('6','4','1','ALDERETE M. VALENTINA','FERRETERIA \"IRUPE\" ','AV GRAL LOPEZ 2431','','',NULL,'S',''), ('7','1','1','APUT, INES MARIA','SPA DE CAMPO Y CABAÑAS','FRANCIA Y PORTUGAL',NULL,NULL,NULL,'N',''), ('8','5','1','BAER, LUIS','RESPUESTOS BAER','GRAL LOPEZ Y RIVADAVIA','','',NULL,'S',''), ('9','6','1','BAGILET, Paulina','Estudio Arquitectura','Avellaneda 1299','','',NULL,'S',''), ('10','29','1','BARROSO, HORACIO','MENDOZA PRODUCTOS','RUTA 11 KM 456 Y J. DE GARAY','','',NULL,'S',''), ('11','7','1','BERARDO ANDRES','\"CAZA Y PESCA\"','AV. GRAL LOPEZ 2447','','',NULL,'S',''), ('12','1','1','BERTUZZI, Luis Maria','Cabañas al Coronda','Jujuy y el Rio','','',NULL,'S',''), ('13','8','1','BIRCHE, ENRIQUE','CONTRATISTA','SUIZA Y FINLANDIA','','',NULL,'S',''), ('14','15','1','BLANCO, Maria Priscila','La Reja (Maria Isab. Muolo)','Banderas 1253','','',NULL,'S',''), ('15','9','1','BONO, HERNAN','GUARDERIA EL ARCA','ALISOS Y MARGARITA','','',NULL,'S',''), ('16','10','1','BOUVIER MARIA EUGENIA','JARDSINES DEL LITORAL','RUTA 11 KM 456','','',NULL,'S',''), ('17','6','1','BROLLO, Silvana','Estudio Arquitectura','Noruega 1531','','',NULL,'S',''), ('18','11','1','BUSANICHE, MARIANO','Contador','NORUEGA 1437','','',NULL,'S',''), ('19','14','1','CANCELLIERI, ALBERTO','DESPENSA KARMA','CALLE 73 E/ 4 Y 6','','',NULL,'S',''), ('20','12','1','CARBALLO ESTEBAN','VENETO S.A.','PARQ. INDUS. COLECT.SUR L. 08','','',NULL,'S',''), ('21','40','1','CARLOS HENAULT','PLOMERIA','Chañares 1231','','',NULL,'S',''), ('22','13','1','CARRIZO LOZANO, Daniela','Consultorio','Suecia 1137',NULL,NULL,NULL,'N',''), ('23','14','1','CASAFUS, SERGIO','MINI MERCADO EL DANI','DIAGONAL 8 E/ 65 Y 67','','',NULL,'N',''), ('24','14','1','CATELANI, ILEANA','ALMACEN EL KOALA','EL MATE 1460','','',NULL,'S',''), ('25','15','1','CATTANEO LIA','BLANCOLY','AV GRAL LOPEZ 2201','','',NULL,'S',''), ('26','7','1','CAUZZO, FEDERICO','PURA ISLA','ALISOS 1582','','',NULL,'S',''), ('27','41','1','CAUZZO, JULIO','EMP. DE SERVICIOS','SUECIA 1186','','',NULL,'S',''), ('28','4','1','CISTARI, MAXIMILIANO','M Y L FERRETERIA','BANDERAS E/MAGNOLIAS E IRUPE','','',NULL,'S',''), ('29','7','1','CLOSTER, VERONICA','EL DELTA CAMPING','RUTA 11 KM 448','','',NULL,'S',''), ('30','17','1','COGGIOLA CARLOS','CASA CALIGUS','9 DE JULIO 2458','','',NULL,'S',''), ('31','18','1','COLOMBO LILIANA','\" FARMACIA COLOMBO\"','AV. RIVADAVIA 1082','','',NULL,'S',''), ('32','15','1','CORGNALI LILIANA','NEGOCIO DE ROPA \"ALTOS TRAPOS\"','DR. MANUEL GALVEZ 1043','','',NULL,'S',''), ('33','30','1','CORTES NORMA RAQUEL','NRC PLASTICOS Y EMBALAJES','SUECIA 1170','','',NULL,'S',''), ('34','19','1','DESTEFANIS, CARIINA','VINOTECA  EL VIEJO ORSI','RUTA 11 Y SUECIA','','',NULL,'S',''), ('35',NULL,'1','DIAZ NOELIA','QUINTEX EXPRESS','DR MANUEL GALVEZ','','',NULL,'N',''), ('36','1','1','DITOMASSO, Graciela','Cabañas los Crespones','',NULL,NULL,NULL,'N',''), ('37','6','1','DONATI, Griselda','Estudio Jurídico','Luis Saenz Peña 1177','','',NULL,'S',''), ('38','14','1','DROZDC JESUS  EDUARDO','MAXIQUIOSCO \"LAS 2 HERMANITAS\"','AV RIVADAVIA 1050','','',NULL,'S',''), ('39','21','1','ENGLER MIRIAN','SEGUROS SANCOR','M GALVEZ 1241','','',NULL,'S',''), ('40','22','1','ERPEN, Gerardo','Electricista','Austria 1350','','',NULL,'N',''), ('41','22','1','ERPEN, GERARDO M.','ELECTRICISTA','ITALIA  1132',NULL,NULL,NULL,'N',''), ('42','23','1','ESCALAS ANA','ZAPATERIA \"ANDRES Y ANDREA\"','AV GRAL LOPEZ 2240',NULL,NULL,NULL,'N',''), ('43','24','1','ESCOBAR AUTOMOTORES','AUTOMOTORES ','RUTA 11 KM 365','','',NULL,'S',''), ('44','20','1','FARINA LAURA','ABOGADA','ITALIA',NULL,NULL,NULL,'N',''), ('45','25','1','FAURE,  IVAN','LOS AROMOS CAMPING','PJE ORTIZ 1221','','',NULL,'S',''), ('46','1','1','FERRER ANA','CABAÑAS LO JORGE','','','',NULL,'S',''), ('47','26','1','FERREYRA MARCOS FABIAN','\"SALON MASCULINO MARCOS\"','AV. GRAL LOPEZ 2411','','',NULL,'S',''), ('48','17','1','FLEITAS CALUDIA','MULTIVENTAS EISES','GRAL LOPEZ 2127','','',NULL,'N',''), ('49','25','1','FORNACIERI, CLARISA','CASA QUINTA','RUTA 11 Y QUINTANA',NULL,NULL,NULL,'N',''), ('50','27','1','FUSCO, ALFONSO','EL VIEJO SAUCE PANADERIA','SAUCES Y IRUPE','','',NULL,'S',''), ('51','27','1','GALLUCCIO ANDREA','PANADERIA \"LANDRE\"','CORDOBA 1269','','',NULL,'S',''), ('52','28','1','GALVAN MARIA ELISA','VETERINARIA \" LA PACHA\"','AV. RIVADAVIA 1055','','',NULL,'S',''), ('53','29','1','GASTRONOMIA TINCHO','GASTRONOMIA','AV GRAL LOPEZ 2235','','',NULL,'S',''), ('54','41','1','GAUTERO, JUAN ANGEL','SERVICIOS','AUSTRIA 1373','','',NULL,'S',''), ('55','30','1','GIACCAGLIA, DANIEL','DISTRIBUIDORA DAG','HUNGRIA 1119','','',NULL,'S',''), ('56','1','1','GIORIA, Jose Luis','Bungalows Casa San Jose','Malvinas',NULL,NULL,NULL,'N',''), ('57','1','1','GODANO MARCELA','CABAÑA \"LAS TIPAS\"','RUTA 11 KM 449','','',NULL,'S',''), ('58','2','1','GOMEZ, HUGO','SUPER KARUKINCA','FRESNOS Y ROSAS','','',NULL,'S',''), ('60','50','1','GOMEZ, SONIA','ANGELO FIT','RUTA 11','','',NULL,'S',''), ('61','31','1','GONZALEZ, Mónica','Corralón don Juan','Ruta 11 y Hungria','','',NULL,'S',''), ('62','24','1','GUSSELLA, ANDRES','GUSSELLA AUTOMOTORES','RUTA 11 4069',NULL,NULL,NULL,'N',''), ('63','1','1','HANG, HECTOR','LOS LEONES CABAÑAS','MAGNOLIAS 4207','','',NULL,'S',''), ('64','12','1','HANG, OMAR','HELADERIA MARCOS POLO','QUINTANA 1387','','',NULL,'N',''), ('65','32','1','HERBSTEIN MARIAN','PAÑALERA MIMICHA','SAN MARTIN Y LAS HERAS',NULL,NULL,NULL,'N',''), ('66','14','1','HILLMAN DARIO HECTOR','DESPENSA EL OBRERO','LAS HERAS 2571','','',NULL,'N',''), ('67','14','1','IGLESIAS CLAUDIA','DESPENSA LA MARTINA','AMERICA 2011','','',NULL,'S',''), ('68','33','1','JERARQUICOS','Casa de Mayores','Ruta 11 y Acceso','','',NULL,'S',''), ('69','34','1','KAY, EXEQUIEL','EL SANTAFESINO (periódico)','HUNGRIA 1241','','',NULL,'S',''), ('70','51','1','KELLEMBERGER BELKIS','COSAS QUE ME GUSTAN','FINLANDIA 1256','','',NULL,'N',''), ('71','35','1','LEFEVRE, GABRIEL','Pescadería Salta','RUTA 11 Y SALTA','','',NULL,'S',''), ('72','36','1','LOBOS EDUARDO','CARNICERA \" LA TITA \"','EE.UU (FRENTE ESC. DELICIAS )','','',NULL,'S',''), ('73','20','1','LOMBARDI, Pablo','Estudio Jurídico','Luis Saenz Peña 1177','','',NULL,'S',''), ('74','4','1','LOPEZ MIRTA','FERRETERIA Rastelli','DR. MANUEL GALVEZ 1065','','',NULL,'N',''), ('75','12','1','MAGALLANES MARIELA','LA HELADERIA','GRAL LOPEZ 2199','','',NULL,'S',''), ('76','1','1','MARIN, Sandra  ( Amborg )','Cab. Barrancas de Sauce','El Mate y Est. Federal',NULL,NULL,NULL,'N',''), ('77','29','1','MARSO ANIBAL DANIEL','COMEDOR \" LAGUNA PORA\"','RUTA 11 Y MITRE',NULL,NULL,NULL,'N',''), ('78','15','1','MARSO LORENA','FASHION SON','ROSARIO 2242','','',NULL,'S',''), ('79','29','1','MARSO NORMA','COMEDOR DE PESCADO EL DORADO','RUTA 11 E IRLANDA','','',NULL,'N',''), ('80','37','1','MARSO, (MARTIN)','Nautica Sauce Viejo','',NULL,NULL,NULL,'N',''), ('81','11','1','MASIN, MARINA','Contadora','Solis 1429','','',NULL,'S',''), ('82','38','1','MEYER SERGIO','REPUESTOS Y BOBINADOS','RUTA 11 KM 447,5','','',NULL,'S',''), ('83','52','1','MIRALLES MARIA BELEN','CENTRO DE ESTETICA MIRALLES','SAN MARTIN Y LAS HERAS',NULL,NULL,NULL,'N',''), ('84','39','1','MOMO JUAN PABLO','FORRAJERIA PABLITO','SAN MARTIN 1030','','',NULL,'S',''), ('85','14','1','MONDINO ALEJANDRO','MAXIQUIOSCO \"TOLI\"','DR. MANUEL GAL. Y A G. LOPEZ','','',NULL,'S',''), ('86','13','1','MONTIEL ROMERO, Ernesto','Médico','Finlandia 1353',NULL,NULL,NULL,'N',''), ('87','2','1','MOSCHEN, NOELIA','SUPER EL GALPON','ECUADOR 3286','','',NULL,'S',''), ('88','29','1','MURANO ADRIAN','QUINCHO LA VIEJA ESTACION','AVDA GRAL LOPEZ 2351',NULL,NULL,NULL,'N',''), ('89','42','1','MUTUAL CENTRAL CORONDA','MUTUAL CENTRAL CORONDA','AV. GRAL LOPEZ Y SAN MARTIN','','',NULL,'S',''), ('90','11','1','NOIR, MIRIAN','Contadora','',NULL,NULL,NULL,'N',''), ('91','15','1','OLIVARES, ANA MARIA','A. M. INDUMENTARIA','SAUCE 1569','','',NULL,'N',''), ('92','53','1','OLIVER DANIEL','CUM SRL','RUTA 11 KM 454','','',NULL,'S',''), ('93','43','1','ORELLANO NESTOR','POLLERIA \"ANYALU\"','AV. RIVADAVIA 1310','','',NULL,'S',''), ('94','20','1','PAIVA MARIA CLAUDIA','ABOGADA','EL MATE 1321','','',NULL,'S',''), ('95','14','1','PALACIN,  DARDO','DESPAC. ADUANA.COM.EXTER.','ITALIA 1202',NULL,NULL,NULL,'N',''), ('96','22','1','PEREYRA, MARTIN','ELECTRICISTA','AUSTRIA 1358','','',NULL,'N',''), ('98','44','1','PETRASSO NORBERTO','AUXILIO SAUCE VIEJO','SARMIENTO 2165-RUTA 11 KM 445','','',NULL,'S',''), ('99','39','1','PLACIDI MARIO','FORRAJERIA EL ALAMO','RUTA 11','','',NULL,'S',''), ('100','14','1','PORTERO ISABEL','DESPENSA \"VANI\"','PANAMA 3997','','',NULL,'S',''), ('101','27','1','PUCCIO ROSANA ','PANADERIA \"BONEO\"','AV. GRAL LOPEZ 2245','','',NULL,'S',''), ('102','36','1','QUIROGA RICARDO','CARNES SELECCIONADAS QUIROGA','RUTA 11','','',NULL,'S','');
INSERT INTO `socios` VALUES ('103','14','1','RAMIREZ MANUELA','DES. M. DEL R. DE SAN NICOLAS','CANADA 2396','','',NULL,'N',''), ('104','18','1','RAVIOLO PABLO ','FARMACIA \"RAVIOLO\"','AV. GRAL LOPEZ 2133','','',NULL,'S',''), ('105','1','1','RIOS, Rosana','Cabañas Curupies','Ceibos y Margaritas',NULL,NULL,NULL,'N',''), ('106','45','1','RODRIGUEZ DANIELA','MANDATARIA','IRLANDA 1500','','',NULL,'S',''), ('107','14','1','RODRIGUEZ GISELA','ALMACEN SAN GIAN','RUTA 11  5129','','',NULL,'S',''), ('108','14','1','ROMERO GABRIELA','VERDULERIA ( EN LOS CHINOS )','RUTA 11',NULL,NULL,NULL,'N',''), ('109','46','1','SANCHO MARIA DEL LUJAN','WINDOWS TEE ( INFORMATICA )','SANTA FE 1011','','',NULL,'N',''), ('110','47','1','SANDOVAL ROSANA','CASA DE QUESOS','SAN MARTIN 1040','','',NULL,'S',''), ('111','6','1','SLAVNER, Adrian','Estudio Arquitectura','Avellaneda 1299','','',NULL,'S',''), ('112','31','1','SOSA CAROLINA','AZOPARDO MATERIALES','AZOPARDO Y JULIO ROCA','','',NULL,'S',''), ('113','48','1','STAHLBERG VICTOR','CERRAJERIA  NY H','RUTA 11','','',NULL,'S',''), ('114','1','1','STESSENS HECTOR','CABAÑAS LA ARIPUCA','RUTA 11','','',NULL,'S',''), ('115','29','1','TOLEDO ABEL PATRICIO','PAPAGAYO ','SAN MARTIN 1076','','',NULL,'S',''), ('116','29','1','TOLEDO ABEL PATRICIO','COMEDOR PUNTA SUR','SAN MARTIN Y 9 DE JULIO','','',NULL,'S',''), ('117','14','1','TOLEDO MARIA LORENA','DESPENSA CHAMIGO CUE','RUTA 11 Y ITALIA','','',NULL,'S',''), ('118','2','1','TOREA, NORA G.','SUPER  RAICES','CALLE 75 S/N','','',NULL,'S',''), ('119','36','1','VALLONE ENRIQUE','CARNICERIA \"LA MADRUGADA\"','DR. MANUEL GALVEZ 1025','','',NULL,'N',''), ('120','1','1','VALLONE MARIO','RIO MIO HOTEL','RUTA 11',NULL,NULL,NULL,'N',''), ('121','49','1','VALLONE REGINO','CASA MARIMI','DR MANUEL GALVEZ 1085','','',NULL,'S',''), ('122','20','1','VALLONES ANDRES','ESTUDIO JURIDICO','DR MANUEL GALVEZ 1045','','',NULL,'S',''), ('123','15','1','VERGARA SILVIA RAQUEL','NOVEDADES ITA','AV. GRAL LOPEZ 2201',NULL,NULL,NULL,'N',''), ('124','2','1','VILLA, CELIA','SUPERMERCADO LOS AMIGOS','SAUCE 1577','','',NULL,'S',''), ('125','49','1','VILLA, Sergio','Hogar Dulce Hogar','Ruta 11 y Austria','','',NULL,'S',''), ('126','27','1','ZABALA JUAN','SABORES DE SAUCE','AV GRAL LOPEZ 2235','','',NULL,'S',''), ('127','14','1','ZAPATA, OMAR','DESPENSA RIKI','CALLE 6 E/ 69 Y 71','','',NULL,'N','');
INSERT INTO `tramites` VALUES ('1','expediente de prueba','<p style=\"text-align: center;\"><strong><span style=\"background-color:#00FFFF\">esto es prueba</span></strong></p>\r\n\r\n<p><span style=\"color:#FF0000\"><strong>Esto es un ejemplo de lo que no se debe hacer...</strong></span></p>','2015-12-01',NULL,'I','backup.jpg,logo.jpg,mini_logo(1).jpg'), ('2','Solicitud de subsidio','<h3><img alt=\"\" src=\" \" style=\"float:left; height:100px; margin-right:10px; width:100px\" />Type the title here</h3>\r\n\r\n<p>Type the text here</p>',NULL,NULL,'I','mini_logo.jpg');
INSERT INTO `userlevelpermissions` VALUES ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}acerca_de.php','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}actividad','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}backup_v1.php','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}backup.php','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}cant_socios_actividad','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}cantidad_socios_por_actividad','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}codigo_actividad','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalle_asociado','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalle_deudas','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalles','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}deudas','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}inicio.php','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}montos','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}pagos','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}resumen_por_mes_de_pago','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}rubros','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_activos','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_cuotas','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_detalles','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}userlevelpermissions','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}userlevels','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}usuario','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}v_ configuracion_deuda','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}v_deuda_adquirida','0'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}acerca_de.php','8'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}actividad','104'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}backup_v1.php','8'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}backup.php','8'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}cant_socios_actividad','40'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}cantidad_socios_por_actividad','8'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}codigo_actividad','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalle_asociado','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalle_deudas','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalles','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}deudas','109'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}inicio.php','8'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}montos','104'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}pagos','109'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}resumen_por_mes_de_pago','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}rubros','104'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios','104'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_activos','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_cuotas','104'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_detalles','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}userlevelpermissions','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}userlevels','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}usuario','104'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}v_ configuracion_deuda','0'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}v_deuda_adquirida','0'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}acerca_de.php','8'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}actividad','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}backup_v1.php','8'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}backup.php','8'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}cant_socios_actividad','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}cantidad_socios_por_actividad','8'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}codigo_actividad','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalle_asociado','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalle_deudas','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalles','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}deudas','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}inicio.php','8'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}montos','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}pagos','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}resumen_por_mes_de_pago','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}rubros','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_activos','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_cuotas','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_detalles','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}userlevelpermissions','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}userlevels','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}usuario','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}v_ configuracion_deuda','0'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}v_deuda_adquirida','0');
INSERT INTO `userlevels` VALUES ('-1','Administración'), ('0','Default'), ('1','Cobradores'), ('2','sistemas');
INSERT INTO `usuario` VALUES ('1','sergio','202cb962ac59075b964b07152d234b70','Usuario sergio','scintes@msn.com',NULL,'1'), ('2','aas','202cb962ac59075b964b07152d234b70','usuario s','scintes@msn.com',NULL,'1');
