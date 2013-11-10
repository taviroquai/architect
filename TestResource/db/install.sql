--
-- Table `test_table1`
--
CREATE TABLE IF NOT EXISTS `test_table1` (
  `id` int(11) NOT NULL,
  `field1` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `test_table1` MODIFY COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `test_table1` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE `test_table1` ENGINE=InnoDB;

--
-- Table `test_table2`
--
CREATE TABLE IF NOT EXISTS `test_table2` (
  `id` int(11) NOT NULL,
  `field1` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `test_table2` MODIFY COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `test_table2` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE `test_table2` ENGINE=InnoDB;

--
-- Table `test_nmrelation`
--
CREATE TABLE IF NOT EXISTS `test_nmrelation` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_table1` int(11) UNSIGNED NOT NULL,
  `id_table2` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `test_nmrelation` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE `test_nmrelation` ENGINE=InnoDB;
ALTER TABLE `test_nmrelation` MODIFY COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
CREATE INDEX `table1_fk_idx` ON `test_nmrelation` (`id_table1`);
CREATE INDEX `table2_fk_idx` ON `test_nmrelation` (`id_table2`);
ALTER TABLE `test_nmrelation` ADD CONSTRAINT `nmrelation_table1_fk` FOREIGN KEY (`id_table1`) REFERENCES `test_table1` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `test_nmrelation` ADD CONSTRAINT `nmrelation_table2_fk` FOREIGN KEY (`id_table2`) REFERENCES `test_table2` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

--
-- Insert default data
--
INSERT INTO `test_table1` (`id`, `field1`) VALUES
(1, 'table1_field1_value');
-- password = 123456

INSERT INTO `test_table2` (`id`, `field1`) VALUES
(1, 'table2_field1_value');

--INSERT INTO `test_nmrelation` (`id`, `id_table1`, `id_table2`) VALUES (1, 1, 1);
