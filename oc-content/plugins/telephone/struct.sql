CREATE TABLE /*TABLE_PREFIX*/t_item_telephone (
    fk_i_item_id INT UNSIGNED NOT NULL,
    s_telephone VARCHAR(60),
        PRIMARY KEY (fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';