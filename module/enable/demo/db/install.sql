--
-- Table `demo_user`
--
CREATE TABLE IF NOT EXISTS `demo_user` (
  `id` int(11) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `demo_user` MODIFY COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `demo_user` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE `demo_user` ENGINE=InnoDB;

--
-- Table `demo_group`
--
CREATE TABLE IF NOT EXISTS `demo_group` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `demo_group` MODIFY COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `demo_group` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE `demo_group` ENGINE=InnoDB;

--
-- Table `user_group`
--
CREATE TABLE IF NOT EXISTS `demo_usergroup` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `id_group` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `demo_usergroup` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE `demo_usergroup` ENGINE=InnoDB;
ALTER TABLE `demo_usergroup` MODIFY COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
CREATE INDEX `user_fk_idx` ON `demo_usergroup` (`id_user`);
CREATE INDEX `group_fk_idx` ON `demo_usergroup` (`id_group`);
ALTER TABLE `demo_usergroup` ADD CONSTRAINT `usergroup_user_fk` FOREIGN KEY (`id_user`) REFERENCES `demo_user` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `demo_usergroup` ADD CONSTRAINT `usergroup_group_fk` FOREIGN KEY (`id_group`) REFERENCES `demo_group` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

--
-- Insert default data
--
INSERT INTO `demo_user` (`id`, `email`, `password`) VALUES
(1, 'admin@domain.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92');
-- password = 123456

INSERT INTO `demo_group` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'guest');

INSERT INTO `demo_usergroup` (`id`, `id_user`, `id_group`) VALUES
(1, 1, 1);