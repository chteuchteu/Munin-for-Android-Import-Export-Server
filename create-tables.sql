SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `importexport` (
  `id` int(11) NOT NULL,
  `version` int(16) NOT NULL DEFAULT '0',
  `exportDate` datetime NOT NULL,
  `password` varchar(16) NOT NULL,
  `dataString` mediumtext NOT NULL,
  `dataType` varchar(16) NOT NULL DEFAULT 'servers'
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

ALTER TABLE `importexport`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `importexport`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
