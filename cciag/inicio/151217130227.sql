/*
MySQL Backup
Source Server Version: 5.5.24
Source Database: cciag-sauceviejo
Date: 17/12/2015 13:02:27
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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `codigo_actividad`
-- ----------------------------
DROP TABLE IF EXISTS `codigo_actividad`;
CREATE TABLE `codigo_actividad` (
  `id` int(11) DEFAULT '0',
  `codigo` int(255) DEFAULT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion_resumida` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `observaciones` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `id_rubro` int(11) DEFAULT NULL,
  `fecha_alta` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_baja` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `objeto_cuantificable` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `manipula_alimento` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_clanae` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_actividad_padre` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=latin1;

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
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cant_socios_actividad` AS select count(`s`.`socio_nro`) AS `socio_nro`,`a`.`actividad` AS `actividad` from (`socios` `s` join `actividad` `a` on((`a`.`id` = `s`.`id_actividad`))) where (`a`.`activa` = 'S') group by `a`.`actividad`,`s`.`id_actividad`,`a`.`activa`;

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
INSERT INTO `actividad` VALUES ('1','2','ALOJAMIENTO',NULL,'S'), ('2','1','ART DEL HOGAR',NULL,'S'), ('3','1','AUTOMOTORES',NULL,'S'), ('4','3','AUXILIO REMOLQUES',NULL,'S'), ('5','1','BEBIDAS',NULL,'S'), ('6','3','CAMPING',NULL,NULL), ('7','1','COMESTIBLES',NULL,NULL), ('8','3','COMIDAS',NULL,NULL), ('9','3','COMIDAS PARA LLEVAR',NULL,NULL), ('10','1','COMPUTACION',NULL,NULL), ('11','3','CONSTRUCCION',NULL,NULL), ('12','1','FARMACIA',NULL,NULL), ('13','1','FERRETERIA',NULL,NULL), ('14','1','FORRAJERIA',NULL,NULL), ('15','1','FOTO VIDEO',NULL,NULL), ('16',NULL,'FUNDACIONES',NULL,NULL), ('17','1','GESTORIA',NULL,NULL), ('18',NULL,'GUARDERIA NAUTICAS',NULL,NULL), ('19',NULL,'HELADERIA',NULL,NULL), ('20',NULL,'INDUMENTARIA',NULL,NULL), ('21',NULL,'MAXIQUIOSCO',NULL,NULL), ('22',NULL,'MUTUALES',NULL,NULL), ('23',NULL,'PANADERIA',NULL,NULL), ('24',NULL,'PAÑALERA',NULL,NULL), ('25',NULL,'PAPELERA EMBALAJES',NULL,NULL), ('26',NULL,'PELUQUERIA',NULL,NULL), ('27',NULL,'PERIODICO',NULL,NULL), ('28',NULL,'PROFESIONALES',NULL,NULL), ('29',NULL,'REGIONALES',NULL,NULL), ('30',NULL,'REPARACIONES',NULL,NULL), ('31',NULL,'REPARACIONES CONSTRUCCION',NULL,NULL), ('32',NULL,'REPUESTOS',NULL,NULL), ('33',NULL,'RESTAURANT',NULL,NULL), ('34',NULL,'SALUD',NULL,NULL), ('35',NULL,'SEGURIDAD INDUSTRIAL',NULL,NULL), ('36',NULL,'VETERINARIA',NULL,NULL), ('37',NULL,'Rubro No espesificado',NULL,NULL), ('38',NULL,'',NULL,NULL), ('42',NULL,'Plomeros',NULL,NULL);
INSERT INTO `detalles` VALUES ('1','Autorizo Revista','El socio autoriza a publicar su empresa en la página como en la revista.','S'), ('2','Autorizo Email.','El socio autoriza a publicar tanto en la página como en la revista el MAIL de su comercio.','S'), ('3','Autorizo Tel.','El socio autoriza a publicar tanto en la página como en la revista los TELEFONOS de su comercio.','S'), ('4','Autorizo Dirección','El socio autoriza a publicar tanto en la página como en la revista las DIERECCIONES de su comercio.','S'), ('5','Autorizo Horarios','El socio autoriza a publicar tanto en la página como en la revista los horarios de su comercio','S');
INSERT INTO `detalle_deudas` VALUES ('1','aaaaa','120.00'), ('1','bbbb','50.00'), ('2','bbbb','50.00'), ('3','bbbb','50.00'), ('3','cccc','110.00');
INSERT INTO `deudas` VALUES ('1','2','1','1','2015','2015-07-24','50'), ('2','2','1','2','2015','2015-07-24','60'), ('3','3','1','1','2015','2015-07-24','100'), ('4','110','1','12','2015','2015-10-10','123');
INSERT INTO `montos` VALUES ('1','Cuota base','25.00','2015-07-21','S','1'), ('2','Cuota Empresa','50.00','2015-07-24','S','1'), ('3','Propaganda 1','100.00','2015-07-24','S','1'), ('4','Propaganda 2','200.00','2015-07-24','S','1'), ('5','Propaganda 3','300.00','2015-07-24','S','1');
INSERT INTO `pagos` VALUES ('1','1','2015-07-24','100.00','1'), ('2','1','2015-07-24','50.00','1'), ('3','2','2015-07-24','60.00','1');
INSERT INTO `rubros` VALUES ('1','Comercio',' Comercio minorista de artículos medicinales de aplicación humana.','S'), ('2','Industria','Ingresos provenientes de la fabricación, elaboración o\r\ntransformación de frutos, productos, materias primas y/o insumos, por\r\nlas ventas al por mayor y siempre que no tengan previsto otro\r\ntratamiento. \r','S'), ('3','Empresas','Empresas de radiofonía y televisión que perciban ingresos de sus\r\na) Empresas de radiofonía y televisión que perciban ingresos de sus receptores. b) Servicios de salones de baile, confiterías bailables y similares.  c) Todas las actividades comerciales y de servicios que no tengan expresamente previsto otro tratamiento. ','S'), ('4','Sin fines de lucro','Abarca a fundaciones, vecinales, etc','S');
INSERT INTO `socios` VALUES ('2','1',NULL,'MARSO LORENA','FASHION SON','ROSARIO 2242',NULL,NULL,NULL,'S',NULL), ('3','7',NULL,'ROSSINI NORMA','VERDULERIA \"EL RAFA\"','DR. MANUEL GALVEZ 1240',NULL,NULL,NULL,'S',NULL), ('4','12',NULL,'RAVIOLO PABLO ','FARMACIA \"RAVIOLO\"','AV. GRAL LOPEZ 2133',NULL,NULL,NULL,'S',NULL), ('5','21',NULL,'FLEITAS CALUDIA','MULTIVENTAS','GRAL LOPEZ 2127',NULL,NULL,NULL,'S',NULL), ('6','1',NULL,'GODANO MARCELA','CABAÑA \"LAS TIPAS\"','RUTA 11 KM 449',NULL,NULL,NULL,'S',NULL), ('7','14',NULL,'MOMO JUAN PABLO','FORRAJERIA PABLITO','SAN MARTIN 1030',NULL,NULL,NULL,'S',NULL), ('8','19',NULL,'MAGALLANES MARIELA','LA HELADERIA','GRAL LOPEZ 2199',NULL,NULL,NULL,'S',NULL), ('9','7',NULL,'SANDOVAL ROSANA','CASA DE QUESOS','SAN MARTIN 1040',NULL,NULL,NULL,'S',NULL), ('10','19',NULL,'HANG, OMAR','HELADERIA MARCOS POLO','QUINTANA 1387',NULL,NULL,NULL,'S',NULL), ('11','5',NULL,'RODOLFO Y OSVALDO LEIVA','SODERIA \"SANTINA\"','AV. GRAL LOPEZ 2161',NULL,NULL,NULL,'N',NULL), ('13','34',NULL,'MIRALLE MARIA BELEN','CENTRO DE ESTETICA MIRALLES','SAN MARTIN Y LAS HERAS',NULL,NULL,NULL,'S',NULL), ('14','20',NULL,'ORDINAS MARIA FERNANDA','EL ESTABLO','DR. MANUEL GALVEZ 1285',NULL,NULL,NULL,'N',NULL), ('15','21',NULL,'MONDINO ALEJANDRO','MAXIQUIOSCO \"TOLI\"','DR. MANUEL GAL. Y A G. LOPEZ',NULL,NULL,NULL,'S',NULL), ('16','2',NULL,'VALLONE REGINO','CASA MARIMI','DR MANUEL GALVEZ 1085',NULL,NULL,NULL,'S',NULL), ('17','13',NULL,'LOPEZ MIRTA','FERRETERIA RESTELLI','DR. MANUEL GALVEZ 1065',NULL,NULL,NULL,'S',NULL), ('18','7',NULL,'VALLONE ENRIQUE','CARNICERIA \"LA MADRUGADA\"','DR. MANUEL GALVEZ 1025',NULL,NULL,NULL,'S',NULL), ('19','20',NULL,'ESCALAS ANA','ZAPATERIA \"ANDRES Y ANDREA\"','AV GRAL LOPEZ 2240',NULL,NULL,NULL,'S',NULL), ('20','7',NULL,'ORELLANO NESTOR','POLLERIA \"ANYALU\"','AV. RIVADAVIA 1310',NULL,NULL,NULL,'S',NULL), ('21','12',NULL,'COLOMBO LILIANA',' FARMACIA COLOMBO','AV. RIVADAVIA 1082',NULL,NULL,NULL,'S',NULL), ('22','6',NULL,'BERARDO ANDRES','\"CAZA Y PESCA\"','AV. GRAL LOPEZ 2447',NULL,NULL,NULL,'S',NULL), ('23','21',NULL,'DROZDC JESUS  EDUARDO','MAXIQUIOSCO \"LAS 2 HERMANITAS\"','AV RIVADAVIA 1050',NULL,NULL,NULL,'S',NULL), ('24','23',NULL,'PUCCIO ROSANA ','PANADERIA \"BONEO\"','AV. GRAL LOPEZ 2245',NULL,NULL,NULL,'S',NULL), ('25','22',NULL,'MUTUAL CENTRAL CORONDA','MUTUAL CENTRAL CORONDA','AV. GRAL LOPEZ Y SAN MARTIN',NULL,NULL,NULL,'S',NULL), ('26','24',NULL,'HERBSTEIN MARIAN','PAÑALERA MIMICHA','SAN MARTIN Y LAS HERAS',NULL,NULL,NULL,'S',NULL), ('27','20',NULL,'CORGNALI LILIANA','NEGOCIO DE ROPA \"ALTOS TRAPOS\"','DR. MANUEL GALVEZ 1043',NULL,NULL,NULL,'S',NULL), ('28','26',NULL,'FERREYRA MARCOS FABIAN','\"SALON MASCULINO MARCOS\"','AV. GRAL LOPEZ 2411',NULL,NULL,NULL,'S',NULL), ('29','10',NULL,'SANCHO MARIA DEL LUJAN','WINDOWS TEE ( INFORMATICA )','SANTA FE 1011',NULL,NULL,NULL,'S',NULL), ('30','13',NULL,'ALDERETE MARIANA V.','FERRETERIA IRUPE','AV GRAL LOPEZ 2431',NULL,NULL,NULL,'S',NULL), ('31','15',NULL,'NIEVA GRISELDA ANGELA','FOTOGRAFIA \" JUAN CARLOS \"','BUENOS AIRES 1033',NULL,NULL,NULL,'N',NULL), ('32','32',NULL,'BAER, LUIS','RESPUESTOS BAER','GRAL LOPEZ Y RIVADAVIA',NULL,NULL,NULL,'S',NULL), ('33','8',NULL,'GASTRONOMIA TINCHO','GASTRONOMIA','AV GRAL LOPEZ 2235',NULL,NULL,NULL,'S',NULL), ('34','7',NULL,'ZABALA JUAN','SABORES DE SAUCE','AV GRAL LOPEZ 2235',NULL,NULL,NULL,'S',NULL), ('35','20',NULL,'VERGARA SILVIA RAQUEL','NOVEDADES ITA','AV. GRAL LOPEZ 2201',NULL,NULL,NULL,'S',NULL), ('36','20',NULL,'CATTANEO LIA','BLANCOLY','AV GRAL LOPEZ 2201',NULL,NULL,NULL,'S',NULL), ('37','20',NULL,'SALVO DANIELA','ROPA ALGUN LADO','DR. M. GALVEZ Y LAS HERAS',NULL,NULL,NULL,'N',NULL), ('38','9',NULL,'ALARCON MARIA PILAR','CARRIBAR \"AMERICA\"','AROMOS Y MARGARITA',NULL,NULL,NULL,'S',NULL), ('39','9',NULL,'ALARCON MARIA PILAR','PARADOR EL PARQUE','AROMOS Y MARGARITA',NULL,NULL,NULL,'S',NULL), ('40','23',NULL,'GALLUCCIO ANDREA','PANADERIA \"LANDRE\"','CORDOBA 1269',NULL,NULL,NULL,'S',NULL), ('41','7',NULL,'MOSCHEN, NOELIA','SUPER EL GALPON','ECUADOR 3286',NULL,NULL,NULL,'S',NULL), ('42','20',NULL,'NUÑEZ BEATRIZ',' NEGOCIO DE ROPA \"FABILUC\"','ENTRE RIOS Y CHILE',NULL,NULL,NULL,'N',NULL), ('43','7',NULL,'PORTERO ISABEL','DESPENSA \"VANI\"','PANAMA 3997',NULL,NULL,NULL,'S',NULL), ('44','7',NULL,'RAMIREZ MANUELA','DES. M. DEL ROSARIO DE SAN NICOLAS','CANADA 2396',NULL,NULL,NULL,'S',NULL), ('45','7',NULL,'LOBOS EDUARDO','CARNICERA \" LA TITA \"','EE.UU (FRENTE ESC. DELICIAS )',NULL,NULL,NULL,'S',NULL), ('46','33',NULL,'TOLEDO ABEL PATRICIO','PAPAGAYO Y PUNTA SUR','SAN MARTIN 1076',NULL,NULL,NULL,'S',NULL), ('47','33',NULL,'TOLEDO ABEL PATRICIO','COMEDOR PUNTA SUR','SAN MARTIN Y 9 DE JULIO',NULL,NULL,NULL,'S',NULL), ('49','33',NULL,'DI PAOLO, FRANCISCO','ALTO RESTO','FRANCIA Y PORTUGAL',NULL,NULL,NULL,'S',NULL), ('51','27',NULL,'KAY, EXEQUIEL','EL SANTAFESINO (periódico)','HUNGRIA 1241',NULL,NULL,NULL,'S',NULL), ('52','11',NULL,'BIRCHE, ENRIQUE','CONTRATISTA','SUIZA Y FINLANDIA',NULL,NULL,NULL,'S',NULL), ('53','34',NULL,'CAUZZO, JULIO','EMP. DE SERVICIOS','SUECIA 1186',NULL,NULL,NULL,'S',NULL), ('54','33',NULL,'DESTEFANIS, CARIINA','VINOTECA  EL VIEJO ORSI','RUTA 11 Y SUECIA',NULL,NULL,NULL,'S',NULL), ('55','28',NULL,'BUSANICHE, MARIANO','CONTADOR','NORUEGA 1437',NULL,NULL,NULL,'S',NULL), ('56','1',NULL,'HANG, HECTOR','LOS LEONES CABAÑAS','MAGNOLIAS 4207',NULL,NULL,NULL,'S',NULL), ('57','7',NULL,'HILLMAN DARIO HECTOR','DESPENSA EL OBRERO','LAS HERAS 2571',NULL,NULL,NULL,'S',NULL), ('58','33',NULL,'MARSO ANIBAL DANIEL','COMEDOR \" LAGUNA PORA\"','RUTA 11 Y MITRE',NULL,NULL,NULL,'S',NULL), ('59','7',NULL,'IGLESIAS CLAUDIA','DESPENSA LA MARTINA','AMERICA 2011',NULL,NULL,NULL,'S',NULL), ('60','7',NULL,'ROMERO GABRIELA','VERDULERIA ( EN LOS CHINOS )','RUTA 11',NULL,NULL,NULL,'S',NULL), ('61','7',NULL,'QUIROGA RICARDO','CARNES SELECCIONADAS QUIROGA','RUTA 11',NULL,NULL,NULL,'S',NULL), ('62','6',NULL,'CLOSTER, VERONICA','EL DELTA CAMPING','RUTA 11 KM 448',NULL,NULL,NULL,'S',NULL), ('63','11',NULL,'ERPEN, GERARDO M.','ELECTRICISTA','ITALIA  1132',NULL,NULL,NULL,'S',NULL), ('64','7',NULL,'VIDELA, MARISA','SUPER EL SAPO','RUTA 11 Y FINLANDIA',NULL,NULL,NULL,'N',NULL), ('65','7',NULL,'GONZALEZ, LUIS ALBERTO','VERD.Y FRUT. EL CLASICO','RUTA 11 5309',NULL,NULL,NULL,'N',NULL), ('66','7',NULL,'RODRIGUEZ GISELA','ALMACEN SAN GIAN','RUTA 11  5129',NULL,NULL,NULL,'S',NULL), ('67','7',NULL,'AEINEN NORMA','SUPER SAN CAYETANO','RUTA 11  5017',NULL,NULL,NULL,'S',NULL), ('68','11',NULL,'PEREYRA, MARTIN','ELECTRICISTA','AUSTRIA 1358',NULL,NULL,NULL,'S',NULL), ('69','33',NULL,'MURANO ADRIAN','QUINCHO LA VIEJA ESTACION','AVDA GRAL LOPEZ 2351',NULL,NULL,NULL,'S',NULL), ('70','28',NULL,'VALLONES ANDRES','ESTUDIO JURIDICO','DR MANUEL GALVEZ 1045',NULL,NULL,NULL,'S',NULL), ('71','14',NULL,'PLACIDI MARIO','FORRAJERIA EL ALAMO','RUTA 11',NULL,NULL,NULL,'S',NULL), ('72','11',NULL,'FORNACIERI, CLARISA','CASA QUINTA','RUTA 11 Y QUINTANA',NULL,NULL,NULL,'S',NULL), ('73','18',NULL,'FAURE,  IVAN','LOS AROMOS CAMPING','PJE ORTIZ 1221',NULL,NULL,NULL,'S',NULL), ('74','3',NULL,'GUSSELLA, ANDRES','GUSSELLA AUTOMOTORES','RUTA 11 4069',NULL,NULL,NULL,'S',NULL), ('75','29',NULL,'BARROSO, HORACIO','MENDOZA PRODUCTOS','RUTA 11 KM 456 Y J. DE GARAY',NULL,NULL,NULL,'S',NULL), ('76','11',NULL,'DE PEDRO CECILIA','LA AGUADA REVESTIMIENTOS','PELLEGRINI 8883',NULL,NULL,NULL,'N',NULL), ('77','36',NULL,'GALVAN MARIA ELISA','VETERINARIA \" LA PACHA\"','AV. RIVADAVIA 1055',NULL,NULL,NULL,'S',NULL), ('78','11',NULL,'BOUVIER MARIA EUGENIA','JARDINES DEL LITORAL','RUTA 11 KM 456',NULL,NULL,NULL,'S',NULL), ('79','7',NULL,'ZAPATA, OMAR','DESPENSA RIKI','CALLE 6 E/ 69 Y 71',NULL,NULL,NULL,'S',NULL), ('80','7',NULL,'CANCELLIERI, ALBERTO','DESPENSA KARMA','CALLE 73 E/ 4 Y 6',NULL,NULL,NULL,'S',NULL), ('81','7',NULL,'TOREA, NORA G.','SUPER  RAICES','CALLE 75 S/N',NULL,NULL,NULL,'S',NULL), ('82','7',NULL,'CASAFUS, SERGIO','MINI MERCADO EL DANI','DIAGONAL 8 E/ 65 Y 67',NULL,NULL,NULL,'S',NULL), ('83','7',NULL,'CATELANI, ILEANA','ALMACEN EL KOALA','EL MATE 1460',NULL,NULL,NULL,'S',NULL), ('84','7',NULL,'GOMEZ, HUGO','SUPER KARUKINCA','FRESNOS Y ROSAS',NULL,NULL,NULL,'S',NULL), ('85','20',NULL,'OLIVARES, ANA MARIA','A. M. INDUMENTARIA','SAUCE 1569',NULL,NULL,NULL,'S',NULL), ('86','11',NULL,'GARCIA JUAN','JUAN J GARCIA','FINLANDIA 1153',NULL,NULL,NULL,'S',NULL), ('87','11',NULL,'ORFICE SRL','ORFIEC SRL','RUTA 11 KM 454',NULL,NULL,NULL,'S',NULL), ('88','13',NULL,'CISTARI, MAXIMILIANO','M Y L FERRETERIA','BANDERAS E/MAGNOLIAS E IRUPE',NULL,NULL,NULL,'S',NULL), ('89','23',NULL,'FUSCO, ALFONSO','EL VIEJO SAUCE PANADERIA','SAUCES Y IRUPE',NULL,NULL,NULL,'S',NULL), ('90','7',NULL,'VILLA, CELIA','SUPERMERCADO LOS AMIGOS','SAUCE 1577',NULL,NULL,NULL,'S',NULL), ('91','18',NULL,'BONO, HERNAN','GUARDERIA EL ARCA','ALISOS Y MARGARITA',NULL,NULL,NULL,'S',NULL), ('92','35',NULL,'GIACCAGLIA, DANIEL','DISTRIBUIDORA DAG','HUNGRIA 1119',NULL,NULL,NULL,'S',NULL), ('93','34',NULL,'APUT, INES MARIA','SPA DE CAMPO Y CABAÑAS','FRANCIA Y PORTUGAL',NULL,NULL,NULL,'S',NULL), ('94','28',NULL,'PALACIN,  DARDO','DESPAC. ADUANA.COM.EXTER.','ITALIA 1202',NULL,NULL,NULL,'S',NULL), ('95','25',NULL,'CORTES NORMA RAQUEL','NRC PLASTICOS Y EMBALAJES','SUECIA 1170',NULL,NULL,NULL,'S',NULL), ('96','7',NULL,'GONZALEZ, GABRIELA','MINI MERCADO EL BALNEARIO','RUTA 11 Y ALCORTA',NULL,NULL,NULL,'S',NULL), ('97','7',NULL,'WANG, RUIXIANG','SUPER SOL DE SAUCE','RUTA 11 KM  446',NULL,NULL,NULL,'S',NULL), ('98','11',NULL,'WASINGER, VICENTE','VENTA LEÑA Y POSTES','RUTA 11 Y FINLANDIA',NULL,NULL,NULL,'S',NULL), ('99','11',NULL,'OLIVER DANIEL','CUM SRL','RUTA 11 KM 454',NULL,NULL,NULL,'S',NULL), ('100','3',NULL,'ESCOBAR AUTOMOTORES','AUTOMOTORES ','RUTA 11 KM 365',NULL,NULL,NULL,'S',NULL), ('101','19',NULL,'CARBALLO ESTEBAN','VENETO S.A.','PARQ. INDUS. COLECT.SUR L. 08',NULL,NULL,NULL,'S',NULL), ('102','33',NULL,'MARSO NORMA','COMEDOR DE PESCADO EL DORADO','RUTA 11 E IRLANDA',NULL,NULL,NULL,'N',NULL), ('103','17',NULL,'ENGLER MIRIAN','SEGUROS SANCOR','M GALVEZ 1241',NULL,NULL,NULL,'S',NULL), ('104','30',NULL,'MEYER SERGIO','REPUESTOS Y BOBINADOS','RUTA 11 KM 447,5',NULL,NULL,NULL,'S',NULL);
INSERT INTO `socios` VALUES ('106','11',NULL,'ROSSO SEBASTIAN','JARDIN DEL SAUCE','IRLANDA Y RUTA 11',NULL,NULL,NULL,'S',NULL), ('107','7',NULL,'TOLEDO MARIA LORENA','CHAMIGO','RUTA 11 Y ITALIA',NULL,NULL,NULL,'S',NULL), ('108','31',NULL,'CARLOS HENAULT','PLOMERIA','',NULL,NULL,NULL,'S',NULL), ('109','17',NULL,'DISTASIO LAURA','GESTORIA','',NULL,NULL,NULL,'S',NULL), ('110','1',NULL,'VALLONE MARIO','RIO MIO HOTEL','RUTA 11',NULL,NULL,NULL,'S',NULL), ('111','17',NULL,'RODRIGUEZ DANIELA','MANDATARIA','IRLANDA 1500',NULL,NULL,NULL,'S',NULL), ('112','4',NULL,'PETRASSO NORBERTO','AUXILIO SAUCE VIEJO','SARMIENTO 2165-RUTA 11 KM 445',NULL,NULL,NULL,'S',NULL), ('113','1',NULL,'STESSENS HECTOR','CABAÑAS LA ARIPUCA','',NULL,NULL,NULL,'S',NULL), ('114','1',NULL,'FERRER ANA','CABAÑAS LO JORGE','',NULL,NULL,NULL,'S',NULL), ('115','16',NULL,'ALANIZ SONIA','FUNDACION LINEA VERDE','RUTA 11',NULL,NULL,NULL,'S',NULL);
INSERT INTO `socios_cuotas` VALUES ('2','1','1','2015-07-24'), ('2','5','1','2015-07-24');
INSERT INTO `socios_detalles` VALUES ('30','1','2015-10-19',NULL), ('30','2','2015-10-19',NULL), ('30','3','2015-10-19',NULL), ('30','4','2015-10-19',NULL), ('30','5','2015-10-19',NULL), ('2','3','2015-10-19',NULL), ('2','1','2015-10-19',NULL), ('2','5','2015-10-19',NULL), ('2','2','2015-10-19',NULL), ('2','4','2015-10-19',NULL);
INSERT INTO `tramites` VALUES ('1','expediente de prueba','<p style=\"text-align: center;\"><strong><span style=\"background-color:#00FFFF\">esto es prueba</span></strong></p>\r\n\r\n<p><span style=\"color:#FF0000\"><strong>Esto es un ejemplo de lo que no se debe hacer...</strong></span></p>','2015-12-01',NULL,'I','backup.jpg,logo.jpg,mini_logo(1).jpg'), ('2','Solicitud de subsidio','<h3><img alt=\"\" src=\" \" style=\"float:left; height:100px; margin-right:10px; width:100px\" />Type the title here</h3>\r\n\r\n<p>Type the text here</p>',NULL,NULL,'I','mini_logo.jpg');
INSERT INTO `userlevelpermissions` VALUES ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}acerca_de.php','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}actividad','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}backup_v1.php','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}backup.php','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}cant_socios_actividad','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}cantidad_socios_por_actividad','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}codigo_actividad','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalle_asociado','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalle_deudas','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalles','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}deudas','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}inicio.php','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}montos','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}pagos','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}resumen_por_mes_de_pago','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}rubros','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_activos','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_cuotas','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_detalles','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}userlevelpermissions','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}userlevels','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}usuario','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}v_ configuracion_deuda','0'), ('0','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}v_deuda_adquirida','0'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}acerca_de.php','8'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}actividad','104'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}backup_v1.php','8'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}backup.php','8'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}cant_socios_actividad','40'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}cantidad_socios_por_actividad','8'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}codigo_actividad','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalle_asociado','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalle_deudas','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalles','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}deudas','109'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}inicio.php','8'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}montos','104'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}pagos','109'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}resumen_por_mes_de_pago','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}rubros','104'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios','104'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_activos','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_cuotas','104'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_detalles','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}userlevelpermissions','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}userlevels','76'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}usuario','104'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}v_ configuracion_deuda','0'), ('1','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}v_deuda_adquirida','0'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}acerca_de.php','8'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}actividad','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}backup_v1.php','8'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}backup.php','8'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}cant_socios_actividad','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}cantidad_socios_por_actividad','8'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}codigo_actividad','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalle_asociado','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalle_deudas','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}detalles','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}deudas','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}inicio.php','8'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}montos','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}pagos','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}resumen_por_mes_de_pago','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}rubros','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_activos','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_cuotas','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}socios_detalles','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}userlevelpermissions','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}userlevels','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}usuario','111'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}v_ configuracion_deuda','0'), ('2','{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}v_deuda_adquirida','0');
INSERT INTO `userlevels` VALUES ('-1','Administración'), ('0','Default'), ('1','Cobradores'), ('2','sistemas');
INSERT INTO `usuario` VALUES ('1','sergio','202cb962ac59075b964b07152d234b70','Usuario sergio','scintes@msn.com',NULL,'1'), ('2','aas','202cb962ac59075b964b07152d234b70','usuario s','scintes@msn.com',NULL,'1');
