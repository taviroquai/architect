
DROP TABLE IF EXISTS `test_dummy`;
DROP TABLE IF EXISTS `test_nmrelation`;
DROP TABLE IF EXISTS `test_table1`;
DROP TABLE IF EXISTS `test_table2`;

--
-- Table `test_table1`
--
CREATE TABLE IF NOT EXISTS `test_table1` (
  `id` INTEGER PRIMARY KEY,
  `field1` VARCHAR(60) NOT NULL
);

--
-- Table `test_table2`
--
CREATE TABLE IF NOT EXISTS `test_table2` (
  `id` INTEGER PRIMARY KEY,
  `field1` VARCHAR(60) NOT NULL
);

--
-- Table `test_nmrelation`
--
CREATE TABLE IF NOT EXISTS `test_nmrelation` (
  `id` INTEGER PRIMARY KEY,
  `id_table1` INTEGER NOT NULL,
  `id_table2` INTEGER NOT NULL,
  FOREIGN KEY(`id_table1`) REFERENCES test_table1(id),
  FOREIGN KEY(`id_table2`) REFERENCES test_table2(id)
);

CREATE TABLE IF NOT EXISTS `test_dummy` (
  `id` INTEGER PRIMARY KEY,
  `dummy` VARCHAR(60) NOT NULL
);

--
-- Insert default data
--
INSERT INTO `test_table1` (`id`, `field1`) VALUES
(1, 'table1_field1_value');
-- password = 123456

INSERT INTO `test_table2` (`id`, `field1`) VALUES
(1, 'table2_field1_value');

--INSERT INTO `test_nmrelation` (`id`, `id_table1`, `id_table2`) VALUES (1, 1, 1);

INSERT INTO `test_dummy` (`id`, `dummy`) VALUES
(1, 'value');
