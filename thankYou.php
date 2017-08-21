<?php 
require_once 'core/init.php';

\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

$token = $_POST['stripeToken'];
$full_name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$city = sanitize($_POST['city']);
$post_code = sanitize($_POST['post_code']);
$country = sanitize($_POST['country']);
$delivery = sanitize($_POST['delivery']);
$sub_total = sanitize($_POST['sub_total']);
$grand_total = sanitize($_POST['grand_total']);
$cart_id = sanitize($_POST['cart_id']);
$description = sanitize($_POST['description']);
$charge_amount = number_format($grand_total,2) * 100;
$metadata = array(
	"cart_id"   => $cart_id,
	"delivery"  => $delivery,
	"sub_total" => $sub_total,
);

try{
	$charge = \Stripe\Charge::create(array(
		"amount"        => $charge_amount,
		"currency"      => CURRENCY,
		"source"        => $token,
		"description"   => $description,
		"receipt_email" => $email,
		"metadata"      => $metadata)
	);

	$itemQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
	$iresults = mysqli_fetch_assoc($itemQ);
	$items = json_decode($iresults['items'],true);
	foreach ($items as $item) {
		$newSizes = array();
		$item_id = $item['id'];
		$productQ = $db->query("SELECT sizes FROM products WHERE id = '{$item_id}'");
		$product = mysqli_fetch_assoc($productQ);
		$sizes = sizesToArray($product['sizes']);
		foreach($sizes as $size){
			if($size['size'] == $item['size']){
				$q = $size['quantity'] - $item['quantity'];
				$newSizes[] = array('size' => $size['size'], 'quantity' => $q);
			}else{
				$newSizes[] = array('size' => $size['size'], 'quantity' => $size['quantity']);
			}
		}
		$sizeString = sizesToString($newSizes);
		$db->query("UPDATE products SET sizes = '{$sizeString}' WHERE id = '{$item_id}'")
	}

	$db->query("UPDATE cart SET paid = 1 WHERE id = '{$cart_id}'");
	$db->query("INSERT INTO transactions 
		(charge_id,cart_id,full_name,email,street,street2,city,post_code,country,sub_total,delivery,grand_total,description,txn_type) VALUES 
		('$charge->id','$cart_id','$full_name','$email','$street','$street2','$city','$post_code','$country','$sub_total','$delivery','$grand_total','$description','$charge->object')");

	$domain = ($_SERVER['HTTP_HOST'] != 'localhost')? '.'.$_SERVER['HTTP_HOST']:false;
	setcookie(CART_COOKIE,'',1,"/",$domain,false);
	include 'includes/head.php';
	include 'includes/navigation.php';
	include 'includes/headerpartial.php';
	?>
    <h1 class="text-center text-success">Thank you!</h1>
    <p> Your payment is complete. Your card has been successfully charged <?=money($grand_total);?>. A receipt has been sent to your email.</p>
    <p> Your receipt number is: <strong><?=$cart_id;?></strong></p>
    <p> Your order will be shipped to the address below:</p>
    <address>
    	<?=$full_name;?><br>
    	<?=$street;?><br>
    	<?=(($street2 != '')?$street2.'<br>':'');?>
    	<?=$post_code. ' '.$city. ', '.$country;?>
    </address>
    <?php
	include 'includes/footer.php';
} catch(\Stripe\Error\Card $e){
	echo $e;
}


?>