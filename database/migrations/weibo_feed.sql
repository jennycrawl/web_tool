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

 Date: 08/09/2021 17:45:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for weibo_feed
-- ----------------------------
DROP TABLE IF EXISTS `weibo_feed`;
CREATE TABLE `weibo_feed` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` varchar(255) NOT NULL DEFAULT '',
  `account_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '账号id',
  `forward` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '转发数',
  `comment` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `like` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `pubtime` timestamp NOT NULL COMMENT '发布时间',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `forward` (`forward`),
  KEY `comment` (`comment`),
  KEY `like` (`like`),
  KEY `pubtime` (`pubtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
