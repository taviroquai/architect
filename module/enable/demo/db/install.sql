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
-- Table `demo_forum`
--
CREATE TABLE IF NOT EXISTS `demo_forum` (
  `id` int(11) NOT NULL,
  `title` varchar(80) NOT NULL,
  `alias` varchar(80) NOT NULL,
  `description` varchar(80) NOT NULL,
  `keywords` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `demo_forum` MODIFY COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `demo_forum` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE `demo_forum` ENGINE=InnoDB;

--
-- Table `demo_topic`
--
CREATE TABLE IF NOT EXISTS `demo_topic` (
  `id` int(11) NOT NULL,
  `title` varchar(80) NOT NULL,
  `alias` varchar(80) NOT NULL,
  `keywords` varchar(80) NOT NULL,
  `datetime` DATETIME NOT NULL,
  `id_forum` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `sticky` int(1) NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `demo_topic` MODIFY COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `demo_topic` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE `demo_topic` ENGINE=InnoDB;
CREATE INDEX `forum_fk_idx` ON `demo_topic` (`id_forum`);
CREATE INDEX `user_fk_idx` ON `demo_topic` (`id_user`);
ALTER TABLE `demo_topic` ADD CONSTRAINT `topic_forum_fk` FOREIGN KEY (`id_forum`) REFERENCES `demo_forum` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `demo_topic` ADD CONSTRAINT `topic_user_fk` FOREIGN KEY (`id_user`) REFERENCES `demo_user` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;
--
-- Table `demo_post`
--
CREATE TABLE IF NOT EXISTS `demo_post` (
  `id` int(11) NOT NULL,
  `body` TEXT,
  `datetime` DATETIME NOT NULL,
  `id_topic` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `demo_post` MODIFY COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `demo_post` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE `demo_post` ENGINE=InnoDB;
CREATE INDEX `topic_fk_idx` ON `demo_post` (`id_topic`);
CREATE INDEX `user_fk_idx` ON `demo_post` (`id_user`);
ALTER TABLE `demo_post` ADD CONSTRAINT `post_topic_fk` FOREIGN KEY (`id_topic`) REFERENCES `demo_topic` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `demo_post` ADD CONSTRAINT `post_user_fk` FOREIGN KEY (`id_user`) REFERENCES `demo_user` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;
--
-- Table `demo_revision`
--
CREATE TABLE IF NOT EXISTS `demo_revision` (
  `id` int(11) NOT NULL,
  `body` TEXT,
  `datetime` DATETIME NOT NULL,
  `id_post` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `demo_revision` MODIFY COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `demo_revision` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE `demo_revision` ENGINE=InnoDB;
CREATE INDEX `post_fk_idx` ON `demo_revision` (`id_post`);
CREATE INDEX `user_fk_idx` ON `demo_revision` (`id_user`);
ALTER TABLE `demo_revision` ADD CONSTRAINT `revision_post_fk` FOREIGN KEY (`id_post`) REFERENCES `demo_post` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `demo_revision` ADD CONSTRAINT `revision_user_fk` FOREIGN KEY (`id_user`) REFERENCES `demo_user` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

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

INSERT INTO `demo_forum` (`id`, `title`, `alias`, `description`, `keywords`) VALUES
(1, 'Category 1', 'category-1', 'The very first category', 'hello');

INSERT INTO `demo_topic` (`id`, `title`, `alias`, `keywords`, `datetime`, `id_forum`, `id_user`) VALUES
(1, 'Welcome Users', 'welcome-users', 'welcome', NOW(), 1, 1);

INSERT INTO `demo_post` (`id`, `body`, `datetime`, `id_topic`, `id_user`) VALUES
(1, '<p>First post by Architect Demo<p>', NOW(), 1, 1);

INSERT INTO `demo_revision` (`id`, `body`, `datetime`, `id_post`, `id_user`) VALUES
(1, '<p>First post by Architect Demo<p>', NOW(), 1, 1);
