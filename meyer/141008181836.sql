/*
MySQL Backup
Source Server Version: 5.5.24
Source Database: meyer
Date: 08/10/2014 18:18:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `estados`
-- ----------------------------
DROP TABLE IF EXISTS `estados`;
CREATE TABLE `estados` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) CHARACTER SET latin1 NOT NULL,
  `activo` varchar(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'S',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `trabajos`
-- ----------------------------
DROP TABLE IF EXISTS `trabajos`;
CREATE TABLE `trabajos` (
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `nro_orden` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_recepcion` date NOT NULL,
  `cliente` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `objetos` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `detalle_a_realizar` text COLLATE utf8_spanish_ci,
  `precio` decimal(15,2) DEFAULT NULL,
  `entrega` decimal(15,2) DEFAULT NULL,
  `saldo` decimal(15,2) DEFAULT NULL,
  `observaciones` text COLLATE utf8_spanish_ci,
  `tel` varchar(16) COLLATE utf8_spanish_ci DEFAULT NULL,
  `foto1` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `foto2` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  PRIMARY KEY (`nro_orden`),
  KEY `id_usuario` (`usuario`),
  KEY `trabajos_ibfk_1` (`id_estado`),
  CONSTRAINT `trabajos_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  Table structure for `usuario`
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(10) CHARACTER SET latin1 NOT NULL,
  `contrasenia` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cel` varchar(16) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` varchar(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`codigo`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
--  View definition for `v_trabajos_a_entregar`
-- ----------------------------
DROP VIEW IF EXISTS `v_trabajos_a_entregar`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `v_trabajos_a_entregar` AS SELECT trabajos.cliente AS cliente, trabajos.objetos AS objetos, trabajos.precio AS precio, trabajos.entrega AS entrega, trabajos.saldo AS saldo, estados.estado AS estado, trabajos.fecha_recepcion AS fecha_recepcion, trabajos.fecha_entrega AS fecha_entrega, trabajos.foto1, trabajos.foto2 FROM trabajos JOIN estados ON trabajos.id_estado = estados.codigo ;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `estados` VALUES ('1','Recibido','S'), ('2','En Reparación','S'), ('3','Reparado','S'), ('4','Avisado','S'), ('5','Retirado','S');
INSERT INTO `trabajos` VALUES ('Administrator','4','2014-10-08','Altamirano','taladro','cambiar rotor',NULL,NULL,'0.00',NULL,'154297401','images(1).jpg',NULL,NULL,'1'), ('Administrator','5','2014-10-08','ruben','amoladora','cambiar rotor','50.00',NULL,'50.00',NULL,'154297401','images.jpg',NULL,NULL,'1');
INSERT INTO `usuario` VALUES ('1','a','a','Usuario a','scintes@msn.com',NULL,'S'), ('2','s','s','usuario s','scintes@msn.com',NULL,'S');
