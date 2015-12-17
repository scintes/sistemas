/*
MySQL Backup
Source Server Version: 5.1.33
Source Database: meyer
Date: 05/10/2014 13:21:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `trabajos`
-- ----------------------------
DROP TABLE IF EXISTS `trabajos`;
CREATE TABLE `trabajos` (
  `nro_orden` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_recepcion` date NOT NULL,
  `cliente` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `objetos` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `odetalle_a_realizar` text COLLATE utf8_spanish_ci,
  `precio` decimal(15,2) DEFAULT NULL,
  `entrega` decimal(15,2) DEFAULT NULL,
  `saldo` decimal(15,2) DEFAULT NULL,
  `observaciones` text COLLATE utf8_spanish_ci,
  `tel` varchar(16) COLLATE utf8_spanish_ci DEFAULT NULL,
  `foto1` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `foto2` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  PRIMARY KEY (`nro_orden`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
--  View definition for `v_trabajos_a_entregar`
-- ----------------------------
DROP VIEW IF EXISTS `v_trabajos_a_entregar`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trabajos_a_entregar` AS select `trabajos`.`cliente` AS `cliente`,`trabajos`.`objetos` AS `objetos`,`trabajos`.`precio` AS `precio`,`trabajos`.`entrega` AS `entrega`,`trabajos`.`saldo` AS `saldo` from `trabajos`;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `trabajos` VALUES ('1','2015-01-01','Cintes','taladro','cambio carbon','12.52','10.00','2.52','flojo el rotor','4955008',NULL,NULL,NULL);
INSERT INTO `usuario` VALUES ('a','a','Usuario a','scintes@msn.com',NULL,'S'), ('s','s','usuario s','scintes@msn.com',NULL,'S');
