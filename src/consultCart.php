<?php

include "../inc/auxFct.php";
require "../inc/DB.php";

validateSession();

page_head("PetStore - Your cart");
page_navbar();
page_content();
page_footer();

function page_content(){
?>

    <h1>Your cart:</h1>

<!-- Message success -->
<?php if( isset($_GET['success']) && $_GET['success'] != null ){ ?>
	<div class="alert alert-dismissible alert-success">
		<button type="button" class="close" data-dismiss="alert"><i class="fas fa-times"></i></button>
		<?php echo $_GET['success'] ?>
	</div>
<?php } ?>

<?php
    if( isset($_SESSION['email']) && $_SESSION['email'] ){
        $email = $_SESSION['email'];    
    } else{
        header('Location: /src/consultProduct.php');
    }

    //Connection to DataBase
    $db = DB::getInstance();
    if( $db == null )
        echo '<p class="text-danger">base de donnée inaccessible</p>';
    else{
        try{
            $user = $db->getUserByEmail($email);

            $cart = $db->getCartByUserId($user->getId());
            
            $products = $db->getProductsByCartId($cart->getId());
        }
        catch( Exception $e ) { $e->getMessage(); }
        $db->close();
    
        if( count($products) != 0 ){
            displayProducts($products);
        }
        else{
            echo "<p>Empty cart</p>";
        }
    }
}


/**
 * Génère le tableau de produits à afficher
 */
function displayProducts($products)
{
?>

<div class="container-fluid border shadow">
    <table class="table table-striped table-hover">
        <thead>
            <tr class="row">
                <th class="col-3" scope="col">Name</th>
                <th class="col-6" scope="col">Description</th>
                <th class="col-2" scope="col">Price</th>
                <th class="col-1" scope="col"></th>
            </tr>
        </thead>
        <tbody>

<?php
$total = 0;
foreach($products as $product){ ?>
            <tr class="row">
                <th class="col-3" scope="row"><?php echo $product->getName(); ?></th>
                <td class="col-6"><?php echo $product->getDescription(); ?></td>
                <td class="col-2"><?php echo $product->getPrice(); ?>€</td>
                <td class="col-1"><a class="btn btn-danger" href="/src/deleteProductCart.php?id=<?php echo $product->getId(); ?>"><i class="fas fa-trash"></i></a></td>
            </tr>
<?php
$total += $product->getPrice();
} ?>

            <tr class="row">
                <td class="col-1 offset-8"><strong>Total :</strong></td>
                <td class="col-1"><?php echo $total; ?>€</td>
                <td class="col-2"><a class="btn btn-success" href="/src/validateOrder.php">Proceed to order</a></td>
            </tr>

        </tbody>
    </table>
</div> <!-- end container -->



<?php
}
