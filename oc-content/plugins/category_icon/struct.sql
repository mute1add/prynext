CREATE TABLE /*TABLE_PREFIX*/bs_theme_category_icon (
  bs_key_id int(11) NOT NULL,
  bs_image_name varchar(255) NOT NULL,
  pk_i_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE /*TABLE_PREFIX*/bs_theme_category_icon
  ADD PRIMARY KEY (bs_key_id);
ALTER TABLE /*TABLE_PREFIX*/bs_theme_category_icon
  MODIFY bs_key_id int(11) NOT NULL AUTO_INCREMENT;