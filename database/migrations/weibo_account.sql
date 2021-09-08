/*
 Navicat Premium Data Transfer

 Source Server         : 阿里云
 Source Server Type    : MySQL
 Source Server Version : 80013
 Source Host           : 47.98.110.129:3306
 Source Schema         : jenny_crawl

 Target Server Type    : MySQL
 Target Server Version : 80013
 File Encoding         : 65001

 Date: 08/09/2021 17:45:30
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for weibo_account
-- ----------------------------
DROP TABLE IF EXISTS `weibo_account`;
CREATE TABLE `weibo_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '1有效，1无效',
  `attention` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `fans` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `feed` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '微博数',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of weibo_account
-- ----------------------------
BEGIN;
INSERT INTO `weibo_account` VALUES (1, 'ZB中币', '6398362671', 1, 0, 0, 0, '2021-09-08 16:44:06', '2021-09-08 16:44:53');
INSERT INTO `weibo_account` VALUES (2, 'LBank蓝贝壳', '6642251088', 1, 0, 0, 0, '2021-09-08 16:45:37', '2021-09-08 16:45:48');
INSERT INTO `weibo_account` VALUES (3, 'BitMart社区', '6424218806', 1, 0, 0, 0, '2021-09-08 16:47:29', '2021-09-08 16:47:36');
INSERT INTO `weibo_account` VALUES (4, 'Bibox资讯', '6587962025', 1, 0, 0, 0, '2021-09-08 16:48:15', '2021-09-08 16:48:15');
INSERT INTO `weibo_account` VALUES (5, 'Hoo虎符', '6556553702', 1, 0, 0, 0, '2021-09-08 16:48:48', '2021-09-08 16:48:48');
INSERT INTO `weibo_account` VALUES (6, 'HOTBIT社区', '7298243956', 1, 0, 0, 0, '2021-09-08 16:49:12', '2021-09-08 16:49:12');
INSERT INTO `weibo_account` VALUES (7, 'BKEX-Global', '6568272665', 1, 0, 0, 0, '2021-09-08 16:39:53', '2021-09-08 17:44:10');
INSERT INTO `weibo_account` VALUES (8, 'AscendEX微博', '6608296213', 1, 0, 0, 0, '2021-09-08 16:50:07', '2021-09-08 16:50:07');
INSERT INTO `weibo_account` VALUES (9, 'WBFGlobal', '7029681334', 1, 0, 0, 0, '2021-09-08 16:50:26', '2021-09-08 16:50:26');
INSERT INTO `weibo_account` VALUES (10, '币赢CoinW', '7060364162', 1, 0, 0, 0, '2021-09-08 16:50:46', '2021-09-08 16:50:46');
INSERT INTO `weibo_account` VALUES (11, '币格BigONE', '7316536921', 1, 0, 0, 0, '2021-09-08 16:51:16', '2021-09-08 16:51:16');
INSERT INTO `weibo_account` VALUES (12, 'BHEX_Official', '6978008641', 1, 0, 0, 0, '2021-09-08 16:51:36', '2021-09-08 16:51:36');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
