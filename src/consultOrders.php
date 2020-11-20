<?php

include '../inc/auxFct.php';
require '../inc/DB.php';

validateSession();

page_head("PetStore - Previous orders");
page_navbar();
page_content();
page_footer();

function page_content(){
?>
	<h1>Orders history:</h1>

<?php
	if( isset($_SESSION['email']) && $_SESSION['email'] != null ){
		$email = $_SESSION['email'];
	} else{
		header('Location: /src/consultProduct.php?error=Could not display previous orders');
	}

    //Connection to DataBase
	$db = DB::getInstance();
	if( $db == null )
		echo '<p class="text-danger">Try later</p>';
	else{
		try{
			$user = $db->getUserByEmail($email);
			$orders = $db->getOrdersByUserId($user->getId());

			if( count($orders) != 0 ){
				displayOrders($orders, $db);
			} else{
				echo "<p>You don't have any orders yet</p>";
			}
		}
		catch( Exception $e ) { $e->getMessage(); }
		$db->close();
	}
}

function displayOrders($orders, $db){
?>

<!-- Affichage de toute les commandes -->
<?php foreach($orders as $order){ ?>
	<div class="container border mb-2">
		<div class="row py-2">
			<div class="col-2">n°<?php echo $order->getId(); ?></div>
			<div class="col-4">Date : <?php echo $order->getDate(); ?></div>
			<div class="col-1 offset-5"><a class="btn btn-primary btn-turn-icon" data-toggle="collapse" data-target="#products_<?php echo $order->getId(); ?>"><i class="fas fa-chevron-right rotate"></i></a></div>
		</div>
		<div class="row collapse" id="products_<?php echo $order->getId(); ?>">
			<?php displayProducts( $db->getProductsByCartId( $order->getCartId() ) ); ?>
		</div>
	</div>
<?php } ?>

<?php
}


/**
 * Génère le tableau de produits à afficher
 */
function displayProducts($products)
{
?>

<table class="table table-striped mb-0">
	<thead>
		<tr>
			<th class="col-3">Name</th>
			<th class="col-7">Description</th>
			<th class="col-2">Price</th>
		</tr>
	</thead>
	<tbody>

<?php
$total = 0;
foreach($products as $product){ ?>
		<tr>
			<td><?php echo $product->getName(); ?></td>
			<td><?php echo $product->getDescription(); ?></td>
			<td><?php echo $product->getPrice(); ?>€</td>
		</tr>
<?php
$total += $product->getPrice();
} ?>

		<tr>
			<td></td>
			<td><strong class="m-auto">Total :</strong> </td>
			<td><?php echo $total; ?>€</td>
		</tr>

	</tbody>
</table>



<?php
}
