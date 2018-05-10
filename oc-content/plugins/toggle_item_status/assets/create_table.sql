CREATE TABLE /*TABLE_PREFIX*/t_item_toggle_status (
    ti_id INT(10) UNSIGNED NOT NULL,
    ti_status INT(1) NOT NULL,
        PRIMARY KEY (ti_id),
        FOREIGN KEY (ti_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';