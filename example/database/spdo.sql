/*
MySQL Data Transfer

Source Server         : localhost (127.0.0.1)
Source Server Version : 50541
Source Host           : 127.0.0.1:3306
Source Database       : spdo

Target Server Type    : MYSQL
Target Server Version : 50541
File Encoding         : 65001

Date: 2015-02-09 03:17:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pages
-- ----------------------------
DROP DATABASE IF EXISTS spdo;
CREATE DATABASE spdo CHARACTER SET utf8 COLLATE utf8_general_ci;

USE spdo;

-- ----------------------------
-- Table structure for pages
-- ----------------------------
DROP TABLE IF EXISTS pages;
CREATE TABLE pages (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'New title',
  content longtext COLLATE utf8_unicode_ci,
  public int(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 1,
  createrecord timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of pages
-- ----------------------------
INSERT INTO pages (title,content)
VALUES
	('Quisque augue mauris','Maecenas faucibus feugiat porta. Ut dapibus in quam et molestie. Sed mollis scelerisque velit, a lacinia nunc facilisis ac. Aenean quam mauris, commodo eu semper ac, sagittis vitae massa. Proin erat quam, porttitor id scelerisque scelerisque, venenatis sed nulla. Aenean eleifend consectetur felis vitae venenatis. Nullam non erat lacinia, pharetra nulla eget, facilisis sem. Nullam consectetur erat et gravida tempor. Proin efficitur risus ipsum, rhoncus varius ipsum semper sed. Vivamus vel tincidunt odio. Aliquam vel velit aliquam, suscipit sem non, aliquam orci. Ut interdum non eros a egestas.'),
	('Curabitur accumsan tincidunt dignissim','Ut nec tellus suscipit, convallis leo at, commodo lacus. Duis fringilla turpis ut diam sagittis finibus. Maecenas varius sapien sit amet elit bibendum hendrerit. Cras in risus urna. Duis laoreet ex tellus, non aliquet justo vestibulum vitae. Nullam vel pretium dolor. Donec mattis, diam et sagittis euismod, dui justo laoreet lorem, a molestie purus urna sed enim. Nulla at placerat mauris. Vivamus lacinia nulla felis, quis efficitur risus tristique sed. Nullam consequat consequat urna, et molestie dui finibus vel. Nam mattis et urna eget dictum. Aenean aliquet volutpat aliquet. Curabitur accumsan tincidunt dignissim. Nam et sagittis urna. In gravida commodo aliquam. Etiam fermentum purus eget urna bibendum iaculis.'),
	('Duis fringilla turpis','Suspendisse nec lorem sem. Quisque eget sapien eu nibh commodo convallis a at elit. Pellentesque euismod vehicula augue, vitae elementum neque vulputate sed. Aenean pulvinar erat tortor, vitae convallis lorem ultrices id. Etiam vel convallis libero. Praesent finibus in nulla nec scelerisque. Fusce et lacus ac justo ultricies congue. Curabitur fermentum urna quis sapien tempor, et egestas magna condimentum. Vestibulum egestas vulputate risus a tempor. Cras facilisis massa in lacus aliquet, ac pharetra velit malesuada. Quisque bibendum at velit vitae sagittis. Quisque aliquam, risus ut luctus pretium, turpis ligula bibendum ante, vitae egestas est augue tincidunt ipsum.'),
	('Proin efficitur risus ipsum','Vivamus non pharetra sapien. Morbi id quam id orci pharetra bibendum. Nullam ultrices, turpis et tincidunt volutpat, erat leo dictum mauris, sed placerat dolor nunc non lorem. Ut finibus augue sed quam rutrum maximus. In ac est vel risus porttitor euismod in vel velit. Fusce ut neque dignissim, posuere augue a, sodales urna. Quisque sit amet arcu vitae mauris mattis laoreet id tincidunt ante. Nunc in ipsum quis purus sodales posuere in et justo. Integer dolor mi, posuere convallis congue vestibulum, cursus eu risus. Pellentesque sagittis maximus nibh, et tempor dui laoreet sed. Nullam sollicitudin finibus dui, nec suscipit elit consectetur in. Curabitur at urna vitae mi tempor mattis. Interdum et malesuada fames ac ante ipsum primis in faucibus. Integer vel diam elit. ')
;
