<?php 
require_once '../core/init.php';
if(!is_logged_in()){
	header('Location: login.php');
}
include 'includes/head.php';
include 'includes/navigation.php';

if(isset($_GET['complete']) && $_GET['complete'] == 1){
	$cart_id = sanitize((int)$_GET['cart_id']);
	$db->query("UPDATE cart SET shipped = 1 WHERE id = '{$cart_id}'");
	$_SESSION['success_flash'] = 'THE ORDER HAS BEEN COMPLETED';
	header('Location: index.php');
}

$transaction_id = sanitize((int)$_GET['txn_id']);
$transactionQ = $db->query("SELECT * FROM transactions WHERE id = '{$transaction_id}'");
$transaction = mysqli_fetch_assoc($transactionQ);
$cart_id = $transaction['cart_id'];
$cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
$cart = mysqli_fetch_assoc($cartQ);
$items = json_decode($cart['items'],true);
$idArray = array();
$products = array();
foreach($items as $item){
	$idArray[] = $item['id'];
}
$ids = implode(',',$idArray);
$productQ = $db->query("
	SELECT i.id as 'id', i.title as 'title', c.id as 'cid', c.category as 'child', p.category as 'parent'
	FROM products i 
	LEFT JOIN categories c ON i.categories = c.id
	LEFT JOIN categories p ON c.parent = p.id
	WHERE i.id IN ({$ids});
	");
	while($p = mysqli_fetch_assoc($productQ)){
		foreach($items as $item){
			if($item['id'] == $p['id']){
				$x = $item;
				continue;
			}
		}
		$products[] = array_merge($x, $p);
	}
?>
<h2 class="text-center">Items ordered</h2>
<table class="table table-condensed table-bordered table-striped">
<thead>
	<th>Quantity</th>
	<th>Title</th>
	<th>Category</th>
	<th>Size</th>
</thead>
<tbody>
<?php foreach($products as $product): ?>
<tr>
	<td><?=$product['quantity'];?></td>
	<td><?=$product['title'];?></td>
	<td><?=$product['parent'].' ~ '.$product['child'];?></td>
	<td><?=$product['size'];?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="row">
	<div class="col-md-6">
		<h3 class="text-center">Order details</h3>
		<table class="table table-condensed table-bordered table-striped">
			<tbody>
				<tr>
					<td>Sub total</td>
					<td><?=money($transaction['sub_total']);?></td>
				</tr>
				<tr>
					<td>Delivery</td>
					<td><?=money($transaction['delivery']);?></td>
				</tr>
				<tr>
					<td>Total</td>
					<td><?=money($transaction['grand_total']);?></td>
				</tr>
				<tr>
					<td>Order date</td>
					<td><?=date_form($transaction['txn_date']) ;?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6"></div>
	<h3 class="text-center">Shipping address</h3>
	<address>
		<?=$transaction['full_name'];?><br>
		<?=$transaction['street'];?><br>
		<?=($transaction['street2'] != '')?$transaction['street2'].'<br>':'';?>
		<?=$transaction['post_code'].' '.$transaction['city'];?><br>
		<?=$transaction['country'];?><br>
	</address>
</div>
<div class="pull-right">
	<a href="index.php" class="btn btn-lg btn-default">Cancel</a>
	<a href="orders.php?complete=1&cart_id=<?=$cart_id;?>" class="btn btn-primary btn-lg">Complete order</a>
</div>

<?php include 'includes/footer.php';?>