<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
$full_name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['street']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$country = sanitize($_POST['country']);
$post_code = sanitize($_POST['post_code']);
$errors = array();
$required = array(
	'full_name' => 'Full name',
	'email'     => 'Email',
	'street'    => 'Street address',
	'city'      => 'City',
	'country'   => 'Country',
	'post_code' => 'Post code',
);

foreach($required as $f => $d){
	if(empty($_POST[$f]) || $_POST[$f] == ''){
		$errors[] = $d.' is required';
	}
}

if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
	$errors[] = 'Provide a valid email';
}

if(!empty($errors)){
	echo display_errors($errors);
}else{
	echo 'passed';
}

?>