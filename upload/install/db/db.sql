SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `admin_perm`;
CREATE TABLE `admin_perm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `color_prefix` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `icon` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `edit_razdel` int(11) NULL,
  `edit_kat` int(11) NULL,
  `del_razdel` int(11) NULL,
  `del_kat` int(11) NULL,
  `create_razdel` int(11) NULL,
  `create_kat` int(11) NULL,
  `edit_users` int(11) NULL,
  `ban_users` int(11) NULL,
  `del_them` int(11) NULL,
  `top_them` int(11) NULL,
  `close_them` int(11) NULL,
  `del_post` int(11) NULL,
  `edit_post` int(11) NULL,
  `move_them` int(11) NULL,
  `panel` int(11) NULL,
  `add_ras` int(11) NULL,
  `add_ads` int(11) NULL,
  `edit_banner` int(11) NULL,
  `set_group` int(11) NULL,
  `set_prev` int(11) NULL,
  `set_faq` int(11) NULL,
  `edit_rules` int(11) NULL,
  `edit_tooltip` int(11) NULL,
  `edit_style` int(11) NULL,
  `edit_autch` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `admin_perm` (`id`, `name`, `color_prefix`, `icon`, `edit_razdel`, `edit_kat`, `del_razdel`, `del_kat`, `create_razdel`, `create_kat`, `edit_users`, `ban_users`, `del_them`, `top_them`, `close_them`, `del_post`, `edit_post`, `move_them`, `panel`, `add_ras`, `add_ads`, `edit_banner`, `set_group`, `set_prev`, `set_faq`, `edit_rules`, `edit_tooltip`, `edit_style`, `edit_autch`) VALUES
(1,	'Пользователь',	'background: #2d2d2d',	'',	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0,	0, 0),
(2,	'ROOT',	'background: #c33a',	'<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"#fff\" width=\"800px\" height=\"800px\" viewBox=\"0 0 24 24\" id=\"settings-alt-2\" data-name=\"Flat Color\" class=\"flat-color\"><path id=\"primary\" d=\"M21.42,21.42h0a2,2,0,0,1-2.82,0l-7.18-7.18A6.48,6.48,0,0,1,2,9.05a7.07,7.07,0,0,1,.1-1.85A1,1,0,0,1,3.8,6.74L7,10l2.49-.5L10,7,6.74,3.8A1,1,0,0,1,7.2,2.12,7.07,7.07,0,0,1,9.05,2a6.48,6.48,0,0,1,5.19,9.4l7.18,7.18A2,2,0,0,1,21.42,21.42Z\"></path></svg>',	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1, 1);

DROP TABLE IF EXISTS `ads`;
CREATE TABLE `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `time_srok` varchar(255) DEFAULT NULL,
  `kogda` varchar(255) DEFAULT NULL,
  `color` varchar(244) CHARACTER SET utf8 NULL,
  `b` int(11) NOT NULL DEFAULT '0',
  `i` int(11) NOT NULL DEFAULT '0',
  `tip` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS `antispam`;
CREATE TABLE `antispam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news` varchar(244) NULL,
  `stena` varchar(244) NULL,
  `chat` varchar(244) NULL,
  `forum_tema` varchar(255) NULL,
  `forum_post` varchar(255) NULL,
  `guest` varchar(255) NULL,
  `blog` varchar(244) NULL,
  `mes` varchar(244) NULL,
  `down` varchar(244) NULL,
  `repa` varchar(255) NULL,
  `friends` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `antispam` (`id`, `news`, `stena`, `chat`, `forum_tema`, `forum_post`, `guest`, `blog`, `mes`, `down`, `repa`, `friends`) VALUES
(1,	'1',	'1',	'1',	'1',	'1',	'1',	'1',	'1',	'1',	'60',	'1');

DROP TABLE IF EXISTS `ban_list`;
CREATE TABLE `ban_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` varchar(255) DEFAULT NULL,
  `about` varchar(255) DEFAULT NULL,
  `time_play` varchar(255) DEFAULT NULL,
  `time_end` varchar(255) DEFAULT NULL,
  `add_ban` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `style` varchar(244) NOT NULL DEFAULT 'style',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `config` (`id`, `style`) VALUES
(1,	'default');

DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NULL,
  `text_col` mediumtext COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `forum_file`;
CREATE TABLE `forum_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NULL,
  `name_file` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `forum_kat`;
CREATE TABLE `forum_kat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razdel` varchar(244) NULL,
  `name` varchar(244) NULL,
  `opisanie` varchar(255) NULL,
  `background` varchar(255) NULL,
  `icon` mediumtext NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `forum_like`;
CREATE TABLE `forum_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post` varchar(255) CHARACTER SET utf8 NULL,
  `us` varchar(255) CHARACTER SET utf8 NULL,
  `tema` varchar(255) CHARACTER SET utf8 NULL,
  `themus` varchar(255) CHARACTER SET utf8 NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `forum_post`;
CREATE TABLE `forum_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razdel` int(11) NULL,
  `kat` int(11) NULL,
  `tema` int(11) NULL,
  `text_col` mediumtext NULL,
  `us` varchar(255) NULL,
  `time_up` varchar(255) NULL,
  `citata` mediumtext NULL,
  `citata_us` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `forum_prefix`;
CREATE TABLE `forum_prefix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NULL,
  `style` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `forum_razdel`;
CREATE TABLE `forum_razdel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(244) NULL,
  `opis` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `forum_tema`;
CREATE TABLE `forum_tema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razdel` int(11) NULL,
  `kat` int(11) NULL,
  `text_col` mediumtext NULL,
  `name` varchar(244) NULL,
  `us` varchar(255) NULL,
  `time_up` varchar(255) NULL,
  `status` int(11) NULL,
  `up` varchar(255) NULL,
  `top_them` varchar(255) NULL,
  `level_us` varchar(2) NULL,
  `usup` varchar(255) DEFAULT NULL,
  `prosm` varchar(255) NULL,
  `color` varchar(255) NULL,
  `select_them` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `forum_them_prefix`;
CREATE TABLE `forum_them_prefix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `them_id` int(11) NULL,
  `prefix_id` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `forum_tooltips`;
CREATE TABLE `forum_tooltips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` text COLLATE utf8mb4_unicode_ci NULL,
  `tooltip` text COLLATE utf8mb4_unicode_ci NULL,
  `definition` text COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `forum_zaklad`;
CREATE TABLE `forum_zaklad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tema` varchar(244) NULL,
  `us` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `complaints`;
CREATE TABLE `complaints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `us` varchar(244) NULL,
  `time_up` varchar(244) NULL,
  `complaint` varchar(244) NULL,
  `opis` varchar(244) NULL,
  `status` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `excludedKat`;
CREATE TABLE `excludedKat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kat` varchar(244) NULL,
  `us` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `friends`;
CREATE TABLE `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `us_a` int(11) NULL,
  `us_b` int(11) NULL,
  `status` int(11) NULL,
  `time_up` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `guest`;
CREATE TABLE `guest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg` text NULL,
  `avtorlogin` varchar(244) NULL,
  `avtor` varchar(244) NULL,
  `time_up` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `guests`;
CREATE TABLE `guests` (
  `id` int(11) NULL,
  `ip` varchar(255) NULL,
  `ua` varchar(255) NULL,
  `time_up` int(11) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `jalob_ba`;
CREATE TABLE `jalob_ba` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `about` text NULL,
  `avtor` varchar(244) NULL,
  `komy` varchar(244) NULL,
  `time_up` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lenta`;
CREATE TABLE `lenta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text_col` text NULL,
  `kto` varchar(100) NULL,
  `komy` varchar(100) NULL,
  `time_up` varchar(100) NULL,
  `readlen` varchar(1) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `load_ban`;
CREATE TABLE `load_ban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_col_id` int(11) NULL,
  `addban_user` int(11) NULL,
  `cause` varchar(244) NULL,
  `time_up` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `load_com`;
CREATE TABLE `load_com` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time_up` varchar(244) NULL,
  `msg` text NULL,
  `avtorlogin` varchar(244) NULL,
  `file_col` varchar(244) NULL,
  `ip` varchar(244) NULL,
  `avtor` text NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `load_dir`;
CREATE TABLE `load_dir` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_dir` int(11) NOT NULL DEFAULT '0',
  `url` text,
  `name` varchar(100) DEFAULT NULL,
  `icon` varchar(244) NULL,
  `files` varchar(1) NULL,
  `elita` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `load_loads`;
CREATE TABLE `load_loads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `us` int(11) NULL,
  `time_up` int(11) NULL,
  `file_col` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `load_pass`;
CREATE TABLE `load_pass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NULL,
  `file_col` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `load_reit`;
CREATE TABLE `load_reit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_col` varchar(244) NULL,
  `r` varchar(244) NULL,
  `kto` varchar(100) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `load_screen`;
CREATE TABLE `load_screen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_col_id` int(11) NULL,
  `name_screen` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` varchar(244) NULL,
  `komy` varchar(244) NULL,
  `text_col` text NULL,
  `time_up` varchar(244) NULL,
  `readlen` varchar(1) NULL,
  `file_col` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `message_c`;
CREATE TABLE `message_c` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` varchar(244) NULL,
  `kogo` varchar(244) NULL,
  `time_up` varchar(244) NULL,
  `ignor` varchar(1) NULL,
  `posl_time` int(11) NULL,
  `del` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `message_save`;
CREATE TABLE `message_save` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text_col` text NULL,
  `kto` varchar(244) NULL,
  `ktoavtor` varchar(244) NULL,
  `uid_col` varchar(244) NULL,
  `time_up` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `mes_st`;
CREATE TABLE `mes_st` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text_col` varchar(255) NULL,
  `time_up` int(11) NULL,
  `kto` int(11) NULL,
  `adm` varchar(211) NULL,
  `file_col` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text_col` text NULL,
  `avtor` varchar(244) NULL,
  `time_up` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `news_com`;
CREATE TABLE `news_com` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time_up` varchar(244) NULL,
  `msg` text NULL,
  `avtor` int(244) NULL,
  `avtorlogin` varchar(244) NULL,
  `news` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `photo_album`;
CREATE TABLE `photo_album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `us` int(11) NULL,
  `name` varchar(244) NULL,
  `info` varchar(244) NULL,
  `tip` int(11) NULL,
  `time_up` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `photo_files`;
CREATE TABLE `photo_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NULL,
  `album_id` int(11) NULL,
  `time_up` varchar(100) NULL,
  `files` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `photo_reit`;
CREATE TABLE `photo_reit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `foto` varchar(244) NULL,
  `r` varchar(244) NULL,
  `kto` varchar(100) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `reg`;
CREATE TABLE `reg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `us` int(11) NULL,
  `kem` int(11) DEFAULT NULL,
  `dop` varchar(255) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rek`;
CREATE TABLE `rek` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NULL,
  `url` text NULL,
  `days` text NULL,
  `data_col` text NULL,
  `num` text NULL,
  `ok` text NULL,
  `color` text NULL,
  `sum_col` text NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `repa_user`;
CREATE TABLE `repa_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text_col` text NULL,
  `kto` varchar(244) NULL,
  `komy` varchar(244) NULL,
  `time_up` varchar(244) NULL,
  `repa` varchar(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rules`;
CREATE TABLE `rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kat` text NULL,
  `text_col` text NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NULL,
  `podname` text NULL,
  `key_col` text NULL,
  `des` text NULL,
  `reg_on` int(11) NULL,
  `load_mod` int(11) NULL,
  `forum_post_m` int(11) NULL,
  `forum_tem_m` int(11) NULL,
  `down_file_m` int(11) NULL,
  `guest_post_m` int(11) NULL,
  `help_vk` varchar(255) NULL,
  `help_tg` varchar(255) NULL,
  `help_email` varchar(255) NULL,
  `style` varchar(255) NULL,
  `nick_cena` int(11) NULL,
  `color_nick_cena` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`id`, `name`, `podname`, `key_col`, `des`, `reg_on`, `load_mod`, `forum_post_m`, `forum_tem_m`, `down_file_m`, `guest_post_m`, `help_vk`, `help_tg`, `help_email`, `style`, `nick_cena`, `color_nick_cena`) VALUES
(1,	'',	'',	'',	'',	0,	0,	0,	0,	0,	0,	'',	'',	'',	'default',	0,	0);

DROP TABLE IF EXISTS `smile`;
CREATE TABLE `smile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(244) NULL,
  `icon` varchar(244) NULL,
  `papka` varchar(30) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `smile_p`;
CREATE TABLE `smile_p` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `sps_user`;
CREATE TABLE `sps_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text_col` text NULL,
  `kto` varchar(244) NULL,
  `komy` varchar(244) NULL,
  `time_up` varchar(244) NULL,
  `repa` varchar(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `status_r`;
CREATE TABLE `status_r` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news` varchar(244) NULL,
  `r` varchar(244) NULL,
  `kto` varchar(100) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `stena`;
CREATE TABLE `stena` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg` text NULL,
  `avtor` varchar(244) NULL,
  `ukogo` varchar(244) NULL,
  `time_up` varchar(244) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tems_vievs_ip`;
CREATE TABLE `tems_vievs_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tema` int(11) NULL,
  `user_v` int(11) NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `themes`;
CREATE TABLE `themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder` varchar(255) CHARACTER SET utf8 NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `user_prevs`;
CREATE TABLE `user_prevs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NULL,
  `free_set_name` varchar(255) CHARACTER SET utf8 NULL,
  `free_set_des_name` varchar(255) CHARACTER SET utf8 NULL,
  `set_gif_ava` varchar(255) CHARACTER SET utf8 NULL,
  `set_background_user` varchar(255) CHARACTER SET utf8 NULL,
  `set_background_head_them` varchar(255) CHARACTER SET utf8 NULL,
  `color_them_title` varchar(255) CHARACTER SET utf8 NULL,
  `style_them_title` varchar(255) CHARACTER SET utf8 NULL,
  `icon_prev` mediumtext CHARACTER SET utf8 NULL,
  `color_icon_prev` mediumtext CHARACTER SET utf8 NULL,
  `cena_prev` int(11) NULL,
  `set_intercolor_user` int(11) NULL,
  `set_new_icon` int(11) NULL,
  `set_new_color` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NULL,
  `pass` varchar(255) NULL,
  `sex` varchar(1) NULL,
  `name` varchar(30) NULL,
  `email` varchar(40) NULL,
  `strana` varchar(255) NULL,
  `gorod` varchar(255) NULL,
  `stat` varchar(555) NULL,
  `skype` varchar(555) NULL,
  `icq` varchar(555) NULL,
  `url` varchar(555) NULL,
  `money_col` mediumint(100) NOT NULL DEFAULT '0',
  `allcoin` mediumint(255) NOT NULL DEFAULT '0',
  `style` varchar(255) DEFAULT 'default',
  `news` int(11) NULL,
  `datareg` varchar(255) NULL,
  `level_us` varchar(1) NULL,
  `ip` varchar(250) NULL,
  `viz` varchar(250) NULL,
  `online_us` varchar(250) NULL,
  `gde` varchar(255) NULL,
  `gdeon` varchar(255) NULL,
  `xstatus` varchar(255) NULL,
  `color_nick` mediumtext NULL,
  `max_us` int(30) NOT NULL DEFAULT '10',
  `avatar` varchar(255) NULL,
  `new_tem` int(11) NULL,
  `new_files` int(11) NULL,
  `rating` varchar(244) DEFAULT '0',
  `form_file` int(11) NOT NULL DEFAULT '1',
  `verified` enum('0','1','2') NOT NULL DEFAULT '0',
  `autor` enum('0','1','2') NOT NULL DEFAULT '0',
  `prev` varchar(255) NULL,
  `background` mediumtext NULL,
  `interface_color` mediumtext NULL,
  `vk` varchar(255) NULL,
  `icon_prev` mediumtext NULL,
  `color_prev` mediumtext NULL,
  `text_prev` mediumtext NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `visitors`;
CREATE TABLE `visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NULL,
  `visit_date` date NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `visits`;
CREATE TABLE `visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) unsigned NOT NULL DEFAULT '0',
  `counter` int(11) unsigned NULL,
  `date_col` date NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `vkautch`;
CREATE TABLE `vkautch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` varchar(255) COLLATE utf8mb4_unicode_ci NULL,
  `client_secret` varchar(255) COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `transactions`;
CREATE TABLE transactions (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_us` varchar(255) NULL,
  `to_us` varchar(255) NULL,
  `amount` mediumint NULL,
  `type_col` int(11) NULL,
  `timestamp_col` varchar(255) NULL,
  `status` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;