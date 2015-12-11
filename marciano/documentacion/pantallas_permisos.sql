/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50626
Source Host           : localhost:3306
Source Database       : prueba

Target Server Type    : MYSQL
Target Server Version : 50626
File Encoding         : 65001

Date: 2015-09-28 10:39:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pantallas_permisos
-- ----------------------------
DROP TABLE IF EXISTS `pantallas_permisos`;
CREATE TABLE `pantallas_permisos` (
  `id_usuario` int(11) NOT NULL,
  `id_pantalla` int(11) DEFAULT NULL,
  `id_seccion` int(11) DEFAULT NULL,
  UNIQUE KEY `id_usuario` (`id_usuario`,`id_pantalla`,`id_seccion`) USING BTREE,
  KEY `pantallas_permisos_ibfk_1` (`id_usuario`),
  KEY `id_pantalla` (`id_pantalla`),
  KEY `id_seccion` (`id_seccion`),
  CONSTRAINT `pantallas_permisos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pantallas_permisos_ibfk_2` FOREIGN KEY (`id_pantalla`) REFERENCES `pantallas_items` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `pantallas_permisos_ibfk_3` FOREIGN KEY (`id_seccion`) REFERENCES `pantallas_secciones` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of pantallas_permisos
-- ----------------------------
INSERT INTO `pantallas_permisos` VALUES ('1', null, '0');
INSERT INTO `pantallas_permisos` VALUES ('1', null, '1');
INSERT INTO `pantallas_permisos` VALUES ('1', null, '2');
INSERT INTO `pantallas_permisos` VALUES ('1', null, '3');
INSERT INTO `pantallas_permisos` VALUES ('1', null, '4');
INSERT INTO `pantallas_permisos` VALUES ('1', null, '5');
INSERT INTO `pantallas_permisos` VALUES ('1', null, '6');
INSERT INTO `pantallas_permisos` VALUES ('1', null, '7');
INSERT INTO `pantallas_permisos` VALUES ('1', '1', '0');
INSERT INTO `pantallas_permisos` VALUES ('1', '2', '0');
INSERT INTO `pantallas_permisos` VALUES ('1', '3', '0');
INSERT INTO `pantallas_permisos` VALUES ('1', '4', '0');
INSERT INTO `pantallas_permisos` VALUES ('1', '5', '1');
INSERT INTO `pantallas_permisos` VALUES ('1', '6', '1');
INSERT INTO `pantallas_permisos` VALUES ('1', '7', '1');
INSERT INTO `pantallas_permisos` VALUES ('1', '8', '2');
INSERT INTO `pantallas_permisos` VALUES ('1', '9', '2');
INSERT INTO `pantallas_permisos` VALUES ('1', '10', '2');
INSERT INTO `pantallas_permisos` VALUES ('1', '11', '2');
INSERT INTO `pantallas_permisos` VALUES ('1', '12', '3');
INSERT INTO `pantallas_permisos` VALUES ('1', '13', '3');
INSERT INTO `pantallas_permisos` VALUES ('1', '14', '3');
INSERT INTO `pantallas_permisos` VALUES ('1', '15', '4');
INSERT INTO `pantallas_permisos` VALUES ('1', '16', '4');
INSERT INTO `pantallas_permisos` VALUES ('1', '17', '4');
INSERT INTO `pantallas_permisos` VALUES ('1', '18', '4');
INSERT INTO `pantallas_permisos` VALUES ('1', '19', '5');
INSERT INTO `pantallas_permisos` VALUES ('1', '20', '5');
INSERT INTO `pantallas_permisos` VALUES ('1', '21', '5');
INSERT INTO `pantallas_permisos` VALUES ('1', '22', '5');
INSERT INTO `pantallas_permisos` VALUES ('1', '23', '5');
INSERT INTO `pantallas_permisos` VALUES ('1', '24', '5');
INSERT INTO `pantallas_permisos` VALUES ('1', '25', '6');
INSERT INTO `pantallas_permisos` VALUES ('1', '26', '6');
INSERT INTO `pantallas_permisos` VALUES ('1', '27', '6');
INSERT INTO `pantallas_permisos` VALUES ('1', '28', '6');
INSERT INTO `pantallas_permisos` VALUES ('1', '29', '6');
INSERT INTO `pantallas_permisos` VALUES ('1', '30', '6');
INSERT INTO `pantallas_permisos` VALUES ('1', '31', '7');
INSERT INTO `pantallas_permisos` VALUES ('1', '32', '7');
INSERT INTO `pantallas_permisos` VALUES ('1', '33', '7');
INSERT INTO `pantallas_permisos` VALUES ('1', '34', '7');
INSERT INTO `pantallas_permisos` VALUES ('1', '35', '7');
INSERT INTO `pantallas_permisos` VALUES ('1', '36', '7');
INSERT INTO `pantallas_permisos` VALUES ('5', null, '0');
INSERT INTO `pantallas_permisos` VALUES ('5', null, '1');
INSERT INTO `pantallas_permisos` VALUES ('5', null, '2');
INSERT INTO `pantallas_permisos` VALUES ('5', null, '3');
INSERT INTO `pantallas_permisos` VALUES ('5', null, '4');
INSERT INTO `pantallas_permisos` VALUES ('5', null, '5');
INSERT INTO `pantallas_permisos` VALUES ('5', null, '6');
INSERT INTO `pantallas_permisos` VALUES ('5', null, '7');
INSERT INTO `pantallas_permisos` VALUES ('5', '5', '1');
INSERT INTO `pantallas_permisos` VALUES ('5', '6', '1');
INSERT INTO `pantallas_permisos` VALUES ('5', '7', '1');
INSERT INTO `pantallas_permisos` VALUES ('5', '8', '2');
INSERT INTO `pantallas_permisos` VALUES ('5', '9', '2');
INSERT INTO `pantallas_permisos` VALUES ('5', '10', '2');
INSERT INTO `pantallas_permisos` VALUES ('5', '11', '2');
INSERT INTO `pantallas_permisos` VALUES ('5', '12', '3');
INSERT INTO `pantallas_permisos` VALUES ('5', '13', '3');
INSERT INTO `pantallas_permisos` VALUES ('5', '14', '3');
INSERT INTO `pantallas_permisos` VALUES ('5', '15', '3');
INSERT INTO `pantallas_permisos` VALUES ('5', '16', '3');
INSERT INTO `pantallas_permisos` VALUES ('5', '17', '3');
INSERT INTO `pantallas_permisos` VALUES ('5', '18', '3');
INSERT INTO `pantallas_permisos` VALUES ('5', '19', '4');
INSERT INTO `pantallas_permisos` VALUES ('5', '20', '5');
INSERT INTO `pantallas_permisos` VALUES ('5', '21', '5');
INSERT INTO `pantallas_permisos` VALUES ('5', '22', '5');
INSERT INTO `pantallas_permisos` VALUES ('5', '23', '5');
INSERT INTO `pantallas_permisos` VALUES ('5', '24', '5');
INSERT INTO `pantallas_permisos` VALUES ('5', '25', '6');
INSERT INTO `pantallas_permisos` VALUES ('5', '26', '6');
INSERT INTO `pantallas_permisos` VALUES ('5', '27', '6');
INSERT INTO `pantallas_permisos` VALUES ('5', '28', '6');
INSERT INTO `pantallas_permisos` VALUES ('5', '29', '6');
INSERT INTO `pantallas_permisos` VALUES ('5', '30', '6');
INSERT INTO `pantallas_permisos` VALUES ('5', '31', '7');
INSERT INTO `pantallas_permisos` VALUES ('5', '32', '7');
INSERT INTO `pantallas_permisos` VALUES ('5', '33', '7');
INSERT INTO `pantallas_permisos` VALUES ('5', '34', '7');
INSERT INTO `pantallas_permisos` VALUES ('5', '35', '7');
INSERT INTO `pantallas_permisos` VALUES ('5', '36', '7');
INSERT INTO `pantallas_permisos` VALUES ('6', '5', '1');
INSERT INTO `pantallas_permisos` VALUES ('6', '6', '1');
INSERT INTO `pantallas_permisos` VALUES ('6', '7', '1');
INSERT INTO `pantallas_permisos` VALUES ('6', '8', '2');
INSERT INTO `pantallas_permisos` VALUES ('6', '9', '2');
INSERT INTO `pantallas_permisos` VALUES ('6', '10', '2');
INSERT INTO `pantallas_permisos` VALUES ('6', '11', '2');
INSERT INTO `pantallas_permisos` VALUES ('6', '12', '3');
INSERT INTO `pantallas_permisos` VALUES ('6', '13', '3');
INSERT INTO `pantallas_permisos` VALUES ('6', '14', '3');
INSERT INTO `pantallas_permisos` VALUES ('6', '15', '3');
INSERT INTO `pantallas_permisos` VALUES ('6', '16', '3');
INSERT INTO `pantallas_permisos` VALUES ('6', '17', '3');
INSERT INTO `pantallas_permisos` VALUES ('6', '18', '3');
INSERT INTO `pantallas_permisos` VALUES ('6', '19', '4');
INSERT INTO `pantallas_permisos` VALUES ('6', '20', '5');
INSERT INTO `pantallas_permisos` VALUES ('6', '21', '5');
INSERT INTO `pantallas_permisos` VALUES ('6', '22', '5');
INSERT INTO `pantallas_permisos` VALUES ('6', '23', '5');
INSERT INTO `pantallas_permisos` VALUES ('6', '24', '5');
INSERT INTO `pantallas_permisos` VALUES ('6', '25', '6');
INSERT INTO `pantallas_permisos` VALUES ('6', '26', '6');
INSERT INTO `pantallas_permisos` VALUES ('6', '27', '6');
INSERT INTO `pantallas_permisos` VALUES ('6', '28', '6');
INSERT INTO `pantallas_permisos` VALUES ('6', '29', '6');
INSERT INTO `pantallas_permisos` VALUES ('6', '30', '6');
INSERT INTO `pantallas_permisos` VALUES ('6', '31', '7');
INSERT INTO `pantallas_permisos` VALUES ('6', '32', '7');
INSERT INTO `pantallas_permisos` VALUES ('6', '33', '7');
INSERT INTO `pantallas_permisos` VALUES ('6', '34', '7');
INSERT INTO `pantallas_permisos` VALUES ('6', '35', '7');
INSERT INTO `pantallas_permisos` VALUES ('6', '36', '7');
INSERT INTO `pantallas_permisos` VALUES ('20', '5', '1');
INSERT INTO `pantallas_permisos` VALUES ('20', '6', '1');
INSERT INTO `pantallas_permisos` VALUES ('20', '7', '1');
INSERT INTO `pantallas_permisos` VALUES ('20', '8', '2');
INSERT INTO `pantallas_permisos` VALUES ('20', '9', '2');
INSERT INTO `pantallas_permisos` VALUES ('20', '10', '2');
INSERT INTO `pantallas_permisos` VALUES ('20', '11', '2');
INSERT INTO `pantallas_permisos` VALUES ('20', '12', '3');
INSERT INTO `pantallas_permisos` VALUES ('20', '13', '3');
INSERT INTO `pantallas_permisos` VALUES ('20', '14', '3');
INSERT INTO `pantallas_permisos` VALUES ('20', '15', '3');
INSERT INTO `pantallas_permisos` VALUES ('20', '16', '3');
INSERT INTO `pantallas_permisos` VALUES ('20', '17', '3');
INSERT INTO `pantallas_permisos` VALUES ('20', '18', '3');
INSERT INTO `pantallas_permisos` VALUES ('20', '19', '4');
INSERT INTO `pantallas_permisos` VALUES ('20', '20', '5');
INSERT INTO `pantallas_permisos` VALUES ('20', '21', '5');
INSERT INTO `pantallas_permisos` VALUES ('20', '22', '5');
INSERT INTO `pantallas_permisos` VALUES ('20', '23', '5');
INSERT INTO `pantallas_permisos` VALUES ('20', '24', '5');
INSERT INTO `pantallas_permisos` VALUES ('20', '25', '6');
INSERT INTO `pantallas_permisos` VALUES ('20', '26', '6');
INSERT INTO `pantallas_permisos` VALUES ('20', '27', '6');
INSERT INTO `pantallas_permisos` VALUES ('20', '28', '6');
INSERT INTO `pantallas_permisos` VALUES ('20', '29', '6');
INSERT INTO `pantallas_permisos` VALUES ('20', '30', '6');
INSERT INTO `pantallas_permisos` VALUES ('20', '31', '7');
INSERT INTO `pantallas_permisos` VALUES ('20', '32', '7');
INSERT INTO `pantallas_permisos` VALUES ('20', '33', '7');
INSERT INTO `pantallas_permisos` VALUES ('20', '34', '7');
INSERT INTO `pantallas_permisos` VALUES ('20', '35', '7');
INSERT INTO `pantallas_permisos` VALUES ('20', '36', '7');
