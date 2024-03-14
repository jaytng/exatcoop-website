CREATE TABLE IF NOT EXISTS `#__maximenuck_menubuilder_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `styles` longtext NOT NULL,
  `params` longtext NOT NULL,
  `customid` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

