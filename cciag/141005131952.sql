/*
MySQL Backup
Source Server Version: 5.1.33
Source Database: cciag-sauceviejo
Date: 05/10/2014 13:19:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `rubros`
-- ----------------------------
DROP TABLE IF EXISTS `rubros`;
CREATE TABLE `rubros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rubro` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `socios`
-- ----------------------------
DROP TABLE IF EXISTS `socios`;
CREATE TABLE `socios` (
  `socio_nro` int(11) NOT NULL,
  `propietario` varchar(255) DEFAULT NULL,
  `comercio` varchar(255) DEFAULT NULL,
  `direccion_comercio` varchar(255) DEFAULT NULL,
  `id_rubro` int(11) DEFAULT NULL,
  `activo` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`socio_nro`),
  UNIQUE KEY `NOMBRE` (`propietario`,`comercio`),
  KEY `id_rubro` (`id_rubro`),
  CONSTRAINT `socios_ibfk_1` FOREIGN KEY (`id_rubro`) REFERENCES `rubros` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `usuario`
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `usuario` varchar(10) CHARACTER SET latin1 NOT NULL,
  `contrasenia` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cel` varchar(16) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` varchar(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  View definition for `socios_activos`
-- ----------------------------
DROP VIEW IF EXISTS `socios_activos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `socios_activos` AS select `socios`.`socio_nro` AS `socio_nro`,`rubros`.`rubro` AS `rubro1`,`socios`.`propietario` AS `propietario`,`socios`.`comercio` AS `comercio`,`socios`.`direccion_comercio` AS `direccion_comercio` from (`rubros` join `socios` on((`socios`.`id_rubro` = `rubros`.`id`))) where (`socios`.`activo` = 'S');

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `rubros` VALUES ('1','ALOJAMIENTO'), ('2','ART DEL HOGAR'), ('3','AUTOMOTORES'), ('4','AUXILIO REMOLQUES'), ('5','BEBIDAS'), ('6','CAMPING'), ('7','COMESTIBLES'), ('8','COMIDAS'), ('9','COMIDAS PARA LLEVAR'), ('10','COMPUTACION'), ('11','CONSTRUCCION'), ('12','FARMACIA'), ('13','FERRETERIA'), ('14','FORRAJERIA'), ('15','FOTO VIDEO'), ('16','FUNDACIONES'), ('17','GESTORIA'), ('18','GUARDERIA NAUTICAS'), ('19','HELADERIA'), ('20','INDUMENTARIA'), ('21','MAXIQUIOSCO'), ('22','MUTUALES'), ('23','PANADERIA'), ('24','PAÑALERA'), ('25','PAPELERA EMBALAJES'), ('26','PELUQUERIA'), ('27','PERIODICO'), ('28','PROFESIONALES'), ('29','REGIONALES'), ('30','REPARACIONES'), ('31','REPARACIONES CONSTRUCCION'), ('32','REPUESTOS'), ('33','RESTAURANT'), ('34','SALUD'), ('35','SEGURIDAD INDUSTRIAL'), ('36','VETERINARIA'), ('37','Rubro'), ('38','No espesificado'), ('39','ALOJAMIENTO'), ('40','ALOJAMIENTO'), ('41','ALOJAMIENTO'), ('42','Plomeros');
INSERT INTO `socios` VALUES ('2','MARSO LORENA','FASHION SON','ROSARIO 2242','20','S'), ('3','ROSSINI NORMA','VERDULERIA \"EL RAFA\"','DR. MANUEL GALVEZ 1240','7','S'), ('4','RAVIOLO PABLO ','FARMACIA \"RAVIOLO\"','AV. GRAL LOPEZ 2133','12','S'), ('5','FLEITAS CALUDIA','MULTIVENTAS','GRAL LOPEZ 2127','21','S'), ('6','GODANO MARCELA','CABAÑA \"LAS TIPAS\"','RUTA 11 KM 449','1','S'), ('7','MOMO JUAN PABLO','FORRAJERIA PABLITO','SAN MARTIN 1030','14','S'), ('8','MAGALLANES MARIELA','LA HELADERIA','GRAL LOPEZ 2199','19','S'), ('9','SANDOVAL ROSANA','CASA DE QUESOS','SAN MARTIN 1040','7','S'), ('10','HANG, OMAR','HELADERIA MARCOS POLO','QUINTANA 1387','19','S'), ('11','RODOLFO Y OSVALDO LEIVA','SODERIA \"SANTINA\"','AV. GRAL LOPEZ 2161','5','N'), ('13','MIRALLE MARIA BELEN','CENTRO DE ESTETICA MIRALLES','SAN MARTIN Y LAS HERAS','34','S'), ('14','ORDINAS MARIA FERNANDA','EL ESTABLO','DR. MANUEL GALVEZ 1285','20','N'), ('15','MONDINO ALEJANDRO','MAXIQUIOSCO \"TOLI\"','DR. MANUEL GAL. Y A G. LOPEZ','21','S'), ('16','VALLONE REGINO','CASA MARIMI','DR MANUEL GALVEZ 1085','2','S'), ('17','LOPEZ MIRTA','FERRETERIA RESTELLI','DR. MANUEL GALVEZ 1065','13','S'), ('18','VALLONE ENRIQUE','CARNICERIA \"LA MADRUGADA\"','DR. MANUEL GALVEZ 1025','7','S'), ('19','ESCALAS ANA','ZAPATERIA \"ANDRES Y ANDREA\"','AV GRAL LOPEZ 2240','20','S'), ('20','ORELLANO NESTOR','POLLERIA \"ANYALU\"','AV. RIVADAVIA 1310','7','S'), ('21','COLOMBO LILIANA',' FARMACIA COLOMBO','AV. RIVADAVIA 1082','12','S'), ('22','BERARDO ANDRES','\"CAZA Y PESCA\"','AV. GRAL LOPEZ 2447','6','S'), ('23','DROZDC JESUS  EDUARDO','MAXIQUIOSCO \"LAS 2 HERMANITAS\"','AV RIVADAVIA 1050','21','S'), ('24','PUCCIO ROSANA ','PANADERIA \"BONEO\"','AV. GRAL LOPEZ 2245','23','S'), ('25','MUTUAL CENTRAL CORONDA','MUTUAL CENTRAL CORONDA','AV. GRAL LOPEZ Y SAN MARTIN','22','S'), ('26','HERBSTEIN MARIAN','PAÑALERA MIMICHA','SAN MARTIN Y LAS HERAS','24','S'), ('27','CORGNALI LILIANA','NEGOCIO DE ROPA \"ALTOS TRAPOS\"','DR. MANUEL GALVEZ 1043','20','S'), ('28','FERREYRA MARCOS FABIAN','\"SALON MASCULINO MARCOS\"','AV. GRAL LOPEZ 2411','26','S'), ('29','SANCHO MARIA DEL LUJAN','WINDOWS TEE ( INFORMATICA )','SANTA FE 1011','10','S'), ('30','ALDERETE MARIANA V.','FERRETERIA IRUPE','AV GRAL LOPEZ 2431','13','S'), ('31','NIEVA GRISELDA ANGELA','FOTOGRAFIA \" JUAN CARLOS \"','BUENOS AIRES 1033','15','N'), ('32','BAER, LUIS','RESPUESTOS BAER','GRAL LOPEZ Y RIVADAVIA','32','S'), ('33','GASTRONOMIA TINCHO','GASTRONOMIA','AV GRAL LOPEZ 2235','8','S'), ('34','ZABALA JUAN','SABORES DE SAUCE','AV GRAL LOPEZ 2235','7','S'), ('35','VERGARA SILVIA RAQUEL','NOVEDADES ITA','AV. GRAL LOPEZ 2201','20','S'), ('36','CATTANEO LIA','BLANCOLY','AV GRAL LOPEZ 2201','20','S'), ('37','SALVO DANIELA','ROPA ALGUN LADO','DR. M. GALVEZ Y LAS HERAS','20','N'), ('38','ALARCON MARIA PILAR','CARRIBAR \"AMERICA\"','AROMOS Y MARGARITA','9','S'), ('39','ALARCON MARIA PILAR','PARADOR EL PARQUE','AROMOS Y MARGARITA','9','S'), ('40','GALLUCCIO ANDREA','PANADERIA \"LANDRE\"','CORDOBA 1269','23','S'), ('41','MOSCHEN, NOELIA','SUPER EL GALPON','ECUADOR 3286','7','S'), ('42','NUÑEZ BEATRIZ',' NEGOCIO DE ROPA \"FABILUC\"','ENTRE RIOS Y CHILE','20','N'), ('43','PORTERO ISABEL','DESPENSA \"VANI\"','PANAMA 3997','7','S'), ('44','RAMIREZ MANUELA','DES. M. DEL ROSARIO DE SAN NICOLAS','CANADA 2396','7','S'), ('45','LOBOS EDUARDO','CARNICERA \" LA TITA \"','EE.UU (FRENTE ESC. DELICIAS )','7','S'), ('46','TOLEDO ABEL PATRICIO','PAPAGAYO Y PUNTA SUR','SAN MARTIN 1076','33','S'), ('47','TOLEDO ABEL PATRICIO','COMEDOR PUNTA SUR','SAN MARTIN Y 9 DE JULIO','33','S'), ('49','DI PAOLO, FRANCISCO','ALTO RESTO','FRANCIA Y PORTUGAL','33','S'), ('51','KAY, EXEQUIEL','EL SANTAFESINO (periódico)','HUNGRIA 1241','27','S'), ('52','BIRCHE, ENRIQUE','CONTRATISTA','SUIZA Y FINLANDIA','11','S'), ('53','CAUZZO, JULIO','EMP. DE SERVICIOS','SUECIA 1186','34','S'), ('54','DESTEFANIS, CARIINA','VINOTECA  EL VIEJO ORSI','RUTA 11 Y SUECIA','33','S'), ('55','BUSANICHE, MARIANO','CONTADOR','NORUEGA 1437','28','S'), ('56','HANG, HECTOR','LOS LEONES CABAÑAS','MAGNOLIAS 4207','1','S'), ('57','HILLMAN DARIO HECTOR','DESPENSA EL OBRERO','LAS HERAS 2571','7','S'), ('58','MARSO ANIBAL DANIEL','COMEDOR \" LAGUNA PORA\"','RUTA 11 Y MITRE','33','S'), ('59','IGLESIAS CLAUDIA','DESPENSA LA MARTINA','AMERICA 2011','7','S'), ('60','ROMERO GABRIELA','VERDULERIA ( EN LOS CHINOS )','RUTA 11','7','S'), ('61','QUIROGA RICARDO','CARNES SELECCIONADAS QUIROGA','RUTA 11','7','S'), ('62','CLOSTER, VERONICA','EL DELTA CAMPING','RUTA 11 KM 448','6','S'), ('63','ERPEN, GERARDO M.','ELECTRICISTA','ITALIA  1132','11','S'), ('64','VIDELA, MARISA','SUPER EL SAPO','RUTA 11 Y FINLANDIA','7','N'), ('65','GONZALEZ, LUIS ALBERTO','VERD.Y FRUT. EL CLASICO','RUTA 11 5309','7','N'), ('66','RODRIGUEZ GISELA','ALMACEN SAN GIAN','RUTA 11  5129','7','S'), ('67','AEINEN NORMA','SUPER SAN CAYETANO','RUTA 11  5017','7','S'), ('68','PEREYRA, MARTIN','ELECTRICISTA','AUSTRIA 1358','11','S'), ('69','MURANO ADRIAN','QUINCHO LA VIEJA ESTACION','AVDA GRAL LOPEZ 2351','33','S'), ('70','VALLONES ANDRES','ESTUDIO JURIDICO','DR MANUEL GALVEZ 1045','28','S'), ('71','PLACIDI MARIO','FORRAJERIA EL ALAMO','RUTA 11','14','S'), ('72','FORNACIERI, CLARISA','CASA QUINTA','RUTA 11 Y QUINTANA','11','S'), ('73','FAURE,  IVAN','LOS AROMOS CAMPING','PJE ORTIZ 1221','18','S'), ('74','GUSSELLA, ANDRES','GUSSELLA AUTOMOTORES','RUTA 11 4069','3','S'), ('75','BARROSO, HORACIO','MENDOZA PRODUCTOS','RUTA 11 KM 456 Y J. DE GARAY','29','S'), ('76','DE PEDRO CECILIA','LA AGUADA REVESTIMIENTOS','PELLEGRINI 8883','11','N'), ('77','GALVAN MARIA ELISA','VETERINARIA \" LA PACHA\"','AV. RIVADAVIA 1055','36','S'), ('78','BOUVIER MARIA EUGENIA','JARDINES DEL LITORAL','RUTA 11 KM 456','11','S'), ('79','ZAPATA, OMAR','DESPENSA RIKI','CALLE 6 E/ 69 Y 71','7','S'), ('80','CANCELLIERI, ALBERTO','DESPENSA KARMA','CALLE 73 E/ 4 Y 6','7','S'), ('81','TOREA, NORA G.','SUPER  RAICES','CALLE 75 S/N','7','S'), ('82','CASAFUS, SERGIO','MINI MERCADO EL DANI','DIAGONAL 8 E/ 65 Y 67','7','S'), ('83','CATELANI, ILEANA','ALMACEN EL KOALA','EL MATE 1460','7','S'), ('84','GOMEZ, HUGO','SUPER KARUKINCA','FRESNOS Y ROSAS','7','S'), ('85','OLIVARES, ANA MARIA','A. M. INDUMENTARIA','SAUCE 1569','20','S'), ('86','GARCIA JUAN','JUAN J GARCIA','FINLANDIA 1153','11','S'), ('87','ORFICE SRL','ORFIEC SRL','RUTA 11 KM 454','11','S'), ('88','CISTARI, MAXIMILIANO','M Y L FERRETERIA','BANDERAS E/MAGNOLIAS E IRUPE','13','S'), ('89','FUSCO, ALFONSO','EL VIEJO SAUCE PANADERIA','SAUCES Y IRUPE','23','S'), ('90','VILLA, CELIA','SUPERMERCADO LOS AMIGOS','SAUCE 1577','7','S'), ('91','BONO, HERNAN','GUARDERIA EL ARCA','ALISOS Y MARGARITA','18','S'), ('92','GIACCAGLIA, DANIEL','DISTRIBUIDORA DAG','HUNGRIA 1119','35','S'), ('93','APUT, INES MARIA','SPA DE CAMPO Y CABAÑAS','FRANCIA Y PORTUGAL','34','S'), ('94','PALACIN,  DARDO','DESPAC. ADUANA.COM.EXTER.','ITALIA 1202','28','S'), ('95','CORTES NORMA RAQUEL','NRC PLASTICOS Y EMBALAJES','SUECIA 1170','25','S'), ('96','GONZALEZ, GABRIELA','MINI MERCADO EL BALNEARIO','RUTA 11 Y ALCORTA','7','S'), ('97','WANG, RUIXIANG','SUPER SOL DE SAUCE','RUTA 11 KM  446','7','S'), ('98','WASINGER, VICENTE','VENTA LEÑA Y POSTES','RUTA 11 Y FINLANDIA','11','S'), ('99','OLIVER DANIEL','CUM SRL','RUTA 11 KM 454','11','S'), ('100','ESCOBAR AUTOMOTORES','AUTOMOTORES ','RUTA 11 KM 365','3','S'), ('101','CARBALLO ESTEBAN','VENETO S.A.','PARQ. INDUS. COLECT.SUR L. 08','19','S'), ('102','MARSO NORMA','COMEDOR DE PESCADO EL DORADO','RUTA 11 E IRLANDA','33','N'), ('103','ENGLER MIRIAN','SEGUROS SANCOR','M GALVEZ 1241','17','S'), ('104','MEYER SERGIO','REPUESTOS Y BOBINADOS','RUTA 11 KM 447,5','30','S');
INSERT INTO `socios` VALUES ('106','ROSSO SEBASTIAN','JARDIN DEL SAUCE','IRLANDA Y RUTA 11','11','S'), ('107','TOLEDO MARIA LORENA','CHAMIGO','RUTA 11 Y ITALIA','7','S'), ('108','CARLOS HENAULT','PLOMERIA','','31','S'), ('109','DISTASIO LAURA','GESTORIA','','17','S'), ('110','VALLONE MARIO','RIO MIO HOTEL','RUTA 11','1','S'), ('111','RODRIGUEZ DANIELA','MANDATARIA','IRLANDA 1500','17','S'), ('112','PETRASSO NORBERTO','AUXILIO SAUCE VIEJO','SARMIENTO 2165-RUTA 11 KM 445','4','S'), ('113','STESSENS HECTOR','CABAÑAS LA ARIPUCA','','1','S'), ('114','FERRER ANA','CABAÑAS LO JORGE','','1','S'), ('115','ALANIZ SONIA','FUNDACION LINEA VERDE','RUTA 11','16','S');
INSERT INTO `usuario` VALUES ('a','a','Usuario a','scintes@msn.com',NULL,'S'), ('s','s','usuario s','scintes@msn.com',NULL,'S');
