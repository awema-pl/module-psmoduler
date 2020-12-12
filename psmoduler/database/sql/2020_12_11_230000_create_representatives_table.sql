CREATE TABLE `PREFIX_psmoduler_representative`
(
    `id_representative` INT unsigned NOT NULL AUTO_INCREMENT,
    `code`              VARCHAR(255) NOT NULL UNIQUE,
    `phone`             VARCHAR(255),
    `date_add`          DATETIME,
    `date_upd`          DATETIME,
    `id_employee`       INT unsigned,
    PRIMARY KEY (`id_representative`)
) ENGINE=InnoDB;
