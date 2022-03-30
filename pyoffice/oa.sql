/*
 Navicat Premium Data Transfer

 Source Server         : phpstudy
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : oa

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 06/01/2022 09:42:06
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for auth_group
-- ----------------------------
DROP TABLE IF EXISTS `auth_group`;
CREATE TABLE `auth_group`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for auth_group_permissions
-- ----------------------------
DROP TABLE IF EXISTS `auth_group_permissions`;
CREATE TABLE `auth_group_permissions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `auth_group_permissions_group_id_permission_id_0cd325b0_uniq`(`group_id`, `permission_id`) USING BTREE,
  INDEX `auth_group_permissions_group_id_b120cbf9`(`group_id`) USING BTREE,
  INDEX `auth_group_permissions_permission_id_84c5c92e`(`permission_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for auth_permission
-- ----------------------------
DROP TABLE IF EXISTS `auth_permission`;
CREATE TABLE `auth_permission`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `codename` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `auth_permission_content_type_id_codename_01ab375a_uniq`(`content_type_id`, `codename`) USING BTREE,
  INDEX `auth_permission_content_type_id_2f476e4b`(`content_type_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 25 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_permission
-- ----------------------------
INSERT INTO `auth_permission` VALUES (1, 'Can add log entry', 1, 'add_logentry');
INSERT INTO `auth_permission` VALUES (2, 'Can change log entry', 1, 'change_logentry');
INSERT INTO `auth_permission` VALUES (3, 'Can delete log entry', 1, 'delete_logentry');
INSERT INTO `auth_permission` VALUES (4, 'Can view log entry', 1, 'view_logentry');
INSERT INTO `auth_permission` VALUES (5, 'Can add permission', 2, 'add_permission');
INSERT INTO `auth_permission` VALUES (6, 'Can change permission', 2, 'change_permission');
INSERT INTO `auth_permission` VALUES (7, 'Can delete permission', 2, 'delete_permission');
INSERT INTO `auth_permission` VALUES (8, 'Can view permission', 2, 'view_permission');
INSERT INTO `auth_permission` VALUES (9, 'Can add group', 3, 'add_group');
INSERT INTO `auth_permission` VALUES (10, 'Can change group', 3, 'change_group');
INSERT INTO `auth_permission` VALUES (11, 'Can delete group', 3, 'delete_group');
INSERT INTO `auth_permission` VALUES (12, 'Can view group', 3, 'view_group');
INSERT INTO `auth_permission` VALUES (13, 'Can add user', 4, 'add_user');
INSERT INTO `auth_permission` VALUES (14, 'Can change user', 4, 'change_user');
INSERT INTO `auth_permission` VALUES (15, 'Can delete user', 4, 'delete_user');
INSERT INTO `auth_permission` VALUES (16, 'Can view user', 4, 'view_user');
INSERT INTO `auth_permission` VALUES (17, 'Can add content type', 5, 'add_contenttype');
INSERT INTO `auth_permission` VALUES (18, 'Can change content type', 5, 'change_contenttype');
INSERT INTO `auth_permission` VALUES (19, 'Can delete content type', 5, 'delete_contenttype');
INSERT INTO `auth_permission` VALUES (20, 'Can view content type', 5, 'view_contenttype');
INSERT INTO `auth_permission` VALUES (21, 'Can add session', 6, 'add_session');
INSERT INTO `auth_permission` VALUES (22, 'Can change session', 6, 'change_session');
INSERT INTO `auth_permission` VALUES (23, 'Can delete session', 6, 'delete_session');
INSERT INTO `auth_permission` VALUES (24, 'Can view session', 6, 'view_session');

-- ----------------------------
-- Table structure for auth_user
-- ----------------------------
DROP TABLE IF EXISTS `auth_user`;
CREATE TABLE `auth_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `last_login` datetime(6) DEFAULT NULL,
  `is_superuser` tinyint(1) NOT NULL,
  `username` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `first_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `last_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(254) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_staff` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `date_joined` datetime(6) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for auth_user_groups
-- ----------------------------
DROP TABLE IF EXISTS `auth_user_groups`;
CREATE TABLE `auth_user_groups`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `auth_user_groups_user_id_group_id_94350c0c_uniq`(`user_id`, `group_id`) USING BTREE,
  INDEX `auth_user_groups_user_id_6a12ed8b`(`user_id`) USING BTREE,
  INDEX `auth_user_groups_group_id_97559544`(`group_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for auth_user_user_permissions
-- ----------------------------
DROP TABLE IF EXISTS `auth_user_user_permissions`;
CREATE TABLE `auth_user_user_permissions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `auth_user_user_permissions_user_id_permission_id_14a6b632_uniq`(`user_id`, `permission_id`) USING BTREE,
  INDEX `auth_user_user_permissions_user_id_a95ead1b`(`user_id`) USING BTREE,
  INDEX `auth_user_user_permissions_permission_id_1fbb5f2c`(`permission_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for django_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `django_admin_log`;
CREATE TABLE `django_admin_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_time` datetime(6) NOT NULL,
  `object_id` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `object_repr` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `action_flag` smallint(5) UNSIGNED NOT NULL,
  `change_message` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_type_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `django_admin_log_content_type_id_c4bce8eb`(`content_type_id`) USING BTREE,
  INDEX `django_admin_log_user_id_c564eba6`(`user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for django_content_type
-- ----------------------------
DROP TABLE IF EXISTS `django_content_type`;
CREATE TABLE `django_content_type`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_label` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `model` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `django_content_type_app_label_model_76bd3d3b_uniq`(`app_label`, `model`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of django_content_type
-- ----------------------------
INSERT INTO `django_content_type` VALUES (1, 'admin', 'logentry');
INSERT INTO `django_content_type` VALUES (2, 'auth', 'permission');
INSERT INTO `django_content_type` VALUES (3, 'auth', 'group');
INSERT INTO `django_content_type` VALUES (4, 'auth', 'user');
INSERT INTO `django_content_type` VALUES (5, 'contenttypes', 'contenttype');
INSERT INTO `django_content_type` VALUES (6, 'sessions', 'session');

-- ----------------------------
-- Table structure for django_migrations
-- ----------------------------
DROP TABLE IF EXISTS `django_migrations`;
CREATE TABLE `django_migrations`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `applied` datetime(6) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of django_migrations
-- ----------------------------
INSERT INTO `django_migrations` VALUES (1, 'contenttypes', '0001_initial', '2021-12-21 06:18:02.460715');
INSERT INTO `django_migrations` VALUES (2, 'auth', '0001_initial', '2021-12-21 06:18:02.829421');
INSERT INTO `django_migrations` VALUES (3, 'admin', '0001_initial', '2021-12-21 06:18:03.785217');
INSERT INTO `django_migrations` VALUES (4, 'admin', '0002_logentry_remove_auto_add', '2021-12-21 06:18:04.021798');
INSERT INTO `django_migrations` VALUES (5, 'admin', '0003_logentry_add_action_flag_choices', '2021-12-21 06:18:04.031772');
INSERT INTO `django_migrations` VALUES (6, 'contenttypes', '0002_remove_content_type_name', '2021-12-21 06:18:04.136492');
INSERT INTO `django_migrations` VALUES (7, 'auth', '0002_alter_permission_name_max_length', '2021-12-21 06:18:04.188354');
INSERT INTO `django_migrations` VALUES (8, 'auth', '0003_alter_user_email_max_length', '2021-12-21 06:18:04.236283');
INSERT INTO `django_migrations` VALUES (9, 'auth', '0004_alter_user_username_opts', '2021-12-21 06:18:04.248253');
INSERT INTO `django_migrations` VALUES (10, 'auth', '0005_alter_user_last_login_null', '2021-12-21 06:18:04.307907');
INSERT INTO `django_migrations` VALUES (11, 'auth', '0006_require_contenttypes_0002', '2021-12-21 06:18:04.312893');
INSERT INTO `django_migrations` VALUES (12, 'auth', '0007_alter_validators_add_error_messages', '2021-12-21 06:18:04.321869');
INSERT INTO `django_migrations` VALUES (13, 'auth', '0008_alter_user_username_max_length', '2021-12-21 06:18:04.369740');
INSERT INTO `django_migrations` VALUES (14, 'auth', '0009_alter_user_last_name_max_length', '2021-12-21 06:18:04.459500');
INSERT INTO `django_migrations` VALUES (15, 'auth', '0010_alter_group_name_max_length', '2021-12-21 06:18:04.526440');
INSERT INTO `django_migrations` VALUES (16, 'auth', '0011_update_proxy_permissions', '2021-12-21 06:18:04.539785');
INSERT INTO `django_migrations` VALUES (17, 'sessions', '0001_initial', '2021-12-21 06:18:04.652007');

-- ----------------------------
-- Table structure for django_session
-- ----------------------------
DROP TABLE IF EXISTS `django_session`;
CREATE TABLE `django_session`  (
  `session_key` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `session_data` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `expire_date` datetime(6) NOT NULL,
  PRIMARY KEY (`session_key`) USING BTREE,
  INDEX `django_session_expire_date_a5c62663`(`expire_date`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of django_session
-- ----------------------------
INSERT INTO `django_session` VALUES ('x4o2eevy285g0e4ya3j0m5oeilzago3r', 'YzZjMjIwZTlhMWRkY2I0MDlhMGZjOGM5MTEwMjcwMWU2YWVjYTU4Yzp7InBob25lIjoiYWRtaW4ifQ==', '2022-01-20 01:10:10.055438');

-- ----------------------------
-- Table structure for oa_article
-- ----------------------------
DROP TABLE IF EXISTS `oa_article`;
CREATE TABLE `oa_article`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `type_id` int(11) DEFAULT NULL,
  `pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime(0) DEFAULT NULL,
  `updated_at` datetime(0) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of oa_article
-- ----------------------------
INSERT INTO `oa_article` VALUES (1, '江城子·乙卯正月二十日夜记梦', '十年生死两茫茫，不思量，自难忘。千里孤坟，无处话凄凉。纵使相逢应不识，尘满面，鬓如霜。\n夜来幽梦忽还乡，小轩窗，正梳妆。相顾无言，惟有泪千行。料得年年肠断处，明月夜，短松冈。', 3, '/static/upload/img/06b28ca9e54742539332cfeeb934bcff.jpg', 1, '2022-01-05 16:20:46', '2022-01-05 16:38:30');
INSERT INTO `oa_article` VALUES (2, '《定风波·莫听穿林打叶声》', '莫听穿林打叶声，何妨吟啸且徐行。\n竹杖芒鞋轻胜马，谁怕？一蓑烟雨任平生。', 3, '/static/upload/img/20abd77d6d73439eb80704635aa4aab0.jpg', 1, '2022-01-05 16:56:55', '2022-01-05 16:56:55');
INSERT INTO `oa_article` VALUES (3, '《水调歌头·明月几时有》', '明月几时有，把酒问青天。\n不知天上宫阙，今夕是何年？\n我欲乘风归去，又恐琼楼玉宇，\n高处不胜寒。\n起舞弄清影，何似在人间！\n转朱阁，低绮户，照无眠。\n不应有恨，何事长向别时圆？\n人有悲欢离合，月有阴晴圆缺，\n此事古难全。\n但愿人长久，千里共婵娟。', 3, '/static/upload/img/37b356a435a6443cb7452ba0e350a0ee.jpg', 1, '2022-01-05 16:59:11', '2022-01-05 16:59:11');
INSERT INTO `oa_article` VALUES (4, '《水调歌头·安石在东海》', '《水调歌头·安石在东海》\n安石在东海，从事鬓惊秋。\n中年亲友难别，丝竹缓离愁。\n一旦功成名遂，准拟东还海道，扶病入西州。\n雅志困轩冕，遗恨寄沧洲。\n岁云暮，须早计，要褐裘。\n故乡归去千里，佳处辄迟留。\n我醉歌时君和，醉倒须君扶我，惟酒可忘忧。\n一任刘玄德，相对卧高楼。', 3, '/static/upload/img/78ac150c01424ff9937f75ad648a8a2a.jpg', 1, '2022-01-05 16:59:44', '2022-01-05 16:59:44');
INSERT INTO `oa_article` VALUES (5, '春夏秋冬', '冬爷爷悄悄地走了，把春姑娘轻轻地送来了，春风犹如妈妈的手，柳树犹如女孩的长辫，如：把嫩芽连串起来，就像春天的音符。　　春姑娘把春雨姐姐也叫来了，春雨姐姐哗哗的流着泪花，春姑娘问它怎么了。春雨姐姐说：“白云变成乌云，所以我现在就成了这样。”　　柳树把光光的头戴上了绿色的千百个发夹。　　嫩芽原是枯老的，现在春风一吹就成了小嫩芽。它自豪的说：“我拥有数不完的年纪，我有长久的生命，我就是一嫩一枯，一嫩一枯，一直都这样。”小朋友放起风筝，把愿望写在上面，希望梦想成真。春天看着他们那么的喜欢我，所以我要年年都有这一份礼物。春天到来，百花盛开，万物复苏，有说不完的词语来形容春天的美景。春天真美啊！我爱我自己的春天！', 2, '', 1, '2022-01-05 17:28:49', '2022-01-06 09:10:36');
INSERT INTO `oa_article` VALUES (6, '小时候的风景', '小时候，或者是闲来无事的时候，我最喜欢的就是看风景。　　虽然我不知道，这些问题到底意味着什么，可是我想看到更多的生活，看到那些来自于生活的波折，还有苦尽甘来的所有风格，对于我们来讲，都有着很大的含义。　　看风景，也如同看人生。　　无论是自己的人生，还是其他的人生，我们都觉得很有意思。　　那个时候，我想要去看懂其他人的生活和心态，也是像突然了解到别人，这一切似乎都有不一样的地方，我听着那一首歌曲突然就感觉很熟悉。　　每个人都因为生活尴尬，每个人都曾经犯过错误。　　可是，如果真的要去承认这一切，我想每个人都能够去理解，原来你用一颗拒绝别人的心去看待这一切，本身就是没有很好的结局的。　　所有的心态，或许都是如此。　　只不过事情回到当年，我知道，那些所有的心态，都忘记的一干二净了吧。　　也许过了很多年，你都需要有一个澄清的机会。　　哪怕是看风景，也是如此。', 2, '', 1, '2022-01-05 17:29:24', '2022-01-05 17:29:24');
INSERT INTO `oa_article` VALUES (7, '校园的春', '春天来了，春姑娘悄悄地走进了我们的校园。让我来给你介绍一下校园的美景吧！　　来到学校，我们会看到一个大门，上面写着四个大字——城关一小。走进大门，两旁各有一排杨树，像一位位士兵，守卫着我们的校园。校园的西边是操场，下课了，同学们在操场里打篮球、玩乒乓球、跳绳等等。雨后的校园清爽极了，小花小草从湿润的泥土里小心翼翼地钻出来，好像在和同学玩捉迷藏呢！校园的北边有一棵大松树，像孙悟空的如意金箍棒一样能突破天空，直入云霄。　　校园的东边，当然是我们的教学楼了。上课了，同学们停止了吵闹，都急忙跑回了各自的班级，等待老师的到来。班级里静的连针掉下来都能听得见。可是，一下课，有些同学就像小鸟一样飞快地奔到操场。对了，还有那些“小音乐家”，每次都可以看见它们在树上唱歌呢！　　快乐的时光总是过得这么快，这就是我们校园的美景。', 2, '', 1, '2022-01-05 17:29:50', '2022-01-05 17:29:50');
INSERT INTO `oa_article` VALUES (8, '科技改变生活', '随着科技日新月异的发展，我们的社会突飞猛进，现在生活中有很多我们平常习以为常的事情，现在都变得跟以前不一样了。比如：以前坐公交车我们都会掏一些零钱硬币，但是现在上公交车都是用刷卡了，投币的人也越来越少了，但是这种改变却简化了人们的生活，方便了人们的生活方式。', 1, '', 2, '2022-01-06 09:09:13', '2022-01-06 09:09:13');
INSERT INTO `oa_article` VALUES (9, '未来的科技', '人们生病是因为身体里缺少某种元素，未来有这样一种糖，香甜美味，而且里面富有人体需要的所有元素，只要吃上一颗就会增强人体的免疫力，也就没有人会生病了。但是这样的“神药”一定十分昂贵，该怎么办呢?没关系，这种药大批生产，广泛使用，只卖一元一颗。', 1, '', 2, '2022-01-06 09:09:35', '2022-01-06 09:09:35');

-- ----------------------------
-- Table structure for oa_article_type
-- ----------------------------
DROP TABLE IF EXISTS `oa_article_type`;
CREATE TABLE `oa_article_type`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of oa_article_type
-- ----------------------------
INSERT INTO `oa_article_type` VALUES (1, '科技');
INSERT INTO `oa_article_type` VALUES (2, '风景');
INSERT INTO `oa_article_type` VALUES (3, '诗词');

-- ----------------------------
-- Table structure for oa_depart
-- ----------------------------
DROP TABLE IF EXISTS `oa_depart`;
CREATE TABLE `oa_depart`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pid` int(11) NOT NULL DEFAULT 0,
  `intro` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of oa_depart
-- ----------------------------
INSERT INTO `oa_depart` VALUES (1, 'IT技术部', 0, '');
INSERT INTO `oa_depart` VALUES (2, '人事部', 0, '人事管理');
INSERT INTO `oa_depart` VALUES (3, '采购部', 0, '采购管理');
INSERT INTO `oa_depart` VALUES (4, '网络部', 1, '网络维护');
INSERT INTO `oa_depart` VALUES (5, '开发部', 1, '技术开发');
INSERT INTO `oa_depart` VALUES (6, '需求部', 1, '产品需求');
INSERT INTO `oa_depart` VALUES (7, '总经理', 0, '');

-- ----------------------------
-- Table structure for oa_menu
-- ----------------------------
DROP TABLE IF EXISTS `oa_menu`;
CREATE TABLE `oa_menu`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `status` int(4) NOT NULL DEFAULT 1 COMMENT '1正常 2禁用',
  `idx` int(4) NOT NULL DEFAULT 0 COMMENT '排序',
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of oa_menu
-- ----------------------------
INSERT INTO `oa_menu` VALUES (1, 0, '个人', '', 1, 0, 'el-icon-location');
INSERT INTO `oa_menu` VALUES (2, 0, '文档', '', 1, 0, 'el-icon-menu');
INSERT INTO `oa_menu` VALUES (3, 0, '人事', '', 1, 0, 'el-icon-document');
INSERT INTO `oa_menu` VALUES (4, 3, '员工管理', '/oa/user/', 1, 0, '');
INSERT INTO `oa_menu` VALUES (5, 1, '个人信息', '/oa/user/me/', 1, 0, '');
INSERT INTO `oa_menu` VALUES (6, 2, '文档分类', '/oa/article/typeIndex/', 1, 0, '');
INSERT INTO `oa_menu` VALUES (7, 2, '我的文档', '/oa/article/myArticle/', 1, 0, '');
INSERT INTO `oa_menu` VALUES (10, 0, '设置', '', 1, 0, 'el-icon-setting');
INSERT INTO `oa_menu` VALUES (11, 10, '菜单管理', '/oa/menu/', 1, 2, '');
INSERT INTO `oa_menu` VALUES (12, 10, '权限管理', '/oa/role/', 1, 0, '');
INSERT INTO `oa_menu` VALUES (13, 3, '部门管理', '/oa/depart/', 1, 0, '');
INSERT INTO `oa_menu` VALUES (14, 1, '修改密码', '/oa/user/password/', 1, 0, '');
INSERT INTO `oa_menu` VALUES (15, 2, '文档列表', '/oa/article/index/', 1, 0, '');

-- ----------------------------
-- Table structure for oa_role
-- ----------------------------
DROP TABLE IF EXISTS `oa_role`;
CREATE TABLE `oa_role`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `menus` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of oa_role
-- ----------------------------
INSERT INTO `oa_role` VALUES (1, '超级管理员', '1,5,14,2,6,7,15,3,4,13,10,11,12');
INSERT INTO `oa_role` VALUES (2, '主管', '1,5,14,2,6,7,15,3,4,13,10,11,12');
INSERT INTO `oa_role` VALUES (3, '员工', '1,5,14,2,6,7,15,3,4,13,10,11,12');

-- ----------------------------
-- Table structure for oa_user
-- ----------------------------
DROP TABLE IF EXISTS `oa_user`;
CREATE TABLE `oa_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '工号',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `sex` int(1) DEFAULT NULL,
  `birth` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `idcard` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `depart` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT 0,
  `status` int(4) DEFAULT NULL,
  `created_at` datetime(0) DEFAULT NULL,
  `updated_at` datetime(0) DEFAULT NULL,
  `phone` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of oa_user
-- ----------------------------
INSERT INTO `oa_user` VALUES (1, 'qh_0001', 'admin', 1, '2021-12-23T16:00:00.000Z', '', NULL, 1, 1, NULL, NULL, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '/static/upload/img/me.jpg');
INSERT INTO `oa_user` VALUES (2, 'qh_324516', 'song', 1, '2021-12-27T16:00:00.000Z', '', 2, 3, 1, NULL, NULL, '18153757335', 'e10adc3949ba59abbe56e057f20f883e', '');
INSERT INTO `oa_user` VALUES (3, 'qh_124365', '宋昱廷', 1, '2021-12-27T16:00:00.000Z', '', 5, 3, 1, NULL, NULL, '18153757336', 'e10adc3949ba59abbe56e057f20f883e', '/static/upload/img/2052098.jpg');
INSERT INTO `oa_user` VALUES (5, 'qh_654312', 'tang', 2, '2021-12-27T16:00:00.000Z', '', 6, 3, 1, NULL, NULL, '13112345678', 'e10adc3949ba59abbe56e057f20f883e', '');

SET FOREIGN_KEY_CHECKS = 1;
