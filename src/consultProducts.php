<?php
include "../inc/auxFct.php";
require "../inc/DB.php";

page_head("PetStore - Products");
page_navbar();
page_content();
page_footer();

function page_content(){
?>

	<div class="row">
		<div class="col-10"><h1>Product list:</h1></div>
<?php if( isAdmin() ){ ?>
		<div class="col-2"><a class="btn btn-success fill-width" href="/src/admin/formAddProduct.php"><i class="fas fa-plus"></i> Add Product</a></div>
<?php } ?>
	</div>

<!-- Message success -->
<?php if( isset($_GET['success']) && $_GET['success'] != null ){ ?>
	<div class="alert alert-dismissible alert-success">
		<button type="button" class="close" data-dismiss="alert"><i class="fas fa-times"></i></button>
		<?php echo $_GET['success'] ?>
	</div>
<?php } ?>

<!-- Message error -->
<?php if( isset($_GET['error']) && $_GET['error'] != null ){ ?>
	<div class="alert alert-dismissible alert-danger">
		<button type="button" class="close" data-dismiss="alert"><i class="fas fa-times"></i></button>
		<?php echo $_GET['error'] ?>
	</div>
<?php } ?>

<?php
	//Connection to DataBase
	$db = DB::getInstance();
	if( $db == null )
		echo '<p class="text-danger">Try later...</p>';
	else{
		try{
			$tab = $db->getProducts();
		}
		catch( Exception $e ) { $e->getMessage(); }
		$db->close();

		if( count($tab) != 0){
			displayProducts($tab);
		}
		else{
			echo "<p>Aucune donnée a afficher</p>";
		}
	}
}

/**
 * Génère le tableau de produits à afficher
 */
function displayProducts($tab)
{
?>

	<div class="row">

<?php foreach($tab as $product){ ?>	

	<div class="col-3">
		<div class="card shadow my-2">
			<div class="card-header">
<?php if( isAdmin() ) { ?>
				<div class="row">
					<div class="col-6"><?php echo $product->getName(); ?></div>
					<div class="col-3"><a class="btn btn-warning" href="/src/admin/formEditProduct.php?id=<?php echo $product->getId() ?>"><i class="fas fa-edit"></i></a></div>
					<div class="col-3"><a class="btn btn-danger" href="/src/admin/DBdeleteProduct.php?id=<?php echo $product->getId() ?>"><i class="fas fa-trash"></i></a></div>
				</div>
<?php } else { ?>
				<?php echo $product->getName(); ?>
<?php } ?>
			</div>
			<div class="card-body">
				<?php echo $product->getDescription(); ?>
			</div>
			<div class="card-footer">
				<?php echo $product->getPrice(); ?>€
				<a href="/src/addToCart.php?id=<?php echo $product->getId(); ?>" class="btn btn-primary adjust-right"><i class="fas fa-cart-arrow-down"></i></a>
			</div>
		</div>
	</div>

<?php } //End foreach ?>

	</div>

<?php
}
