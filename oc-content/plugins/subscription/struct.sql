CREATE TABLE IF NOT EXISTS  /*TABLE_PREFIX*/t_packages (
  `package_id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(100) NOT NULL,
  `package_description` text NOT NULL,
  `package_cost` float(10,2) NOT NULL,
  `currency_code` varchar(20) NOT NULL,
  `post_allow` int(11) NOT NULL,
  `expiry_days` int(11) NOT NULL,
  `period_type` enum('days','month','year') NOT NULL DEFAULT 'days',
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;



CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_user_subscription (
  `subscription_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `package_name` varchar(100) NOT NULL,
  `package_cost` float(10,2) NOT NULL,
  `currency_code` varchar(20) NOT NULL,
  `post_allow` int(11) NOT NULL,
  `remaining_post` int(11) NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `transaction_type` enum('paypal','offline') NOT NULL,
  `expiry_date` date NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_status` enum('pending','confirm') NOT NULL DEFAULT 'confirm',
  `status` enum('active','expired') NOT NULL,
  PRIMARY KEY (`subscription_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

