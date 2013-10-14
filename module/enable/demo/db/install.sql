--
-- Table `user`
--
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Set keys and relations
-- 
ALTER TABLE `user` MODIFY COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Insert default data
--
INSERT INTO `user` (`id`, `email`, `password`) VALUES
(1, 'admin@domain.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92');
-- password = 123456