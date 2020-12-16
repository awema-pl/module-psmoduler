CREATE TABLE `PREFIX_psmoduler_representative`
(
    `id_representative` INT unsigned NOT NULL AUTO_INCREMENT,
    `code`              VARCHAR(255) NOT NULL UNIQUE,
    `phone`             VARCHAR(255),
    `date_add`          DATETIME,
    `date_upd`          DATETIME,
    `id_employee`       INT unsigned NOT NULL UNIQUE,
    PRIMARY KEY (`id_representative`),
    CONSTRAINT `fk_Y02VVUDD2PSZ`
    FOREIGN KEY (`id_employee`)
        REFERENCES `PREFIX_employee` (`id_employee`)
        ON DELETE CASCADE
) ENGINE=InnoDB;
