after install this module, please edit the following files 

* setconfig.inc.php
* ezshopingcart/plugin/paypal/paypal.php
* ezshopingcart/plugin/paysbuy/paysbuy.php

(1) Edit 'setconfig.inc.php' insert th following line after 'session_start();'

	// EzShoping Cart
	if (($loadlang=="yes") OR (empty($loadlang))) {
		include_once('modules/ezshopingcart/cartsession.php');
	}

(2) Change 'paysbuy' recieve e-amil account
(3) Change 'paypal' recieve e-mail account
