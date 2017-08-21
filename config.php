<?php
define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/tutorial/');
define('CART_COOKIE', 'SBwi72UCKfdskjewr2');
define('CART_COOKIE_EXPIRE', time() + (86400 * 30));

define('CURRENCY', 'dkk');
define('CHECKOUTMODE','TEST');

if(CHECKOUTMODE == 'TEST'){
	define('STRIPE_PRIVATE','sk_test_JLT3sTudtNHFRX5iQadeMX9J');
	define('STRIPE_PUBLIC','pk_test_8FuX49FmTJo4MfDAPeLcCvpR');
}

if(CHECKOUTMODE == 'LIVE'){
	define('STRIPE_PRIVATE','');
	define('STRIPE_PUBLIC','');
}