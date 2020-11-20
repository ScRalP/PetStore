<?php

include '../../inc/auxFct.php';
if( !isAdmin() ){
    header('Location: /');
}

require '../../inc/DB.php';

//Récupération du produit avec l'id
if( isset($_GET['id']) && $_GET['id'] != null ){
    $id = $_GET['id'];
} else{
    header('Location: /src/consultProducts.php');
}

//Connection to DataBase
$db = DB::getInstance();
if( $db == null )
    echo '<p class="text-danger">Try later...</p>';
else{
    try{
        $product = $db->getProductById($id);
    }
    catch( Exception $e ) { $e->getMessage(); }
    $db->close();
}

//Affichage
page_head("Add new product");
page_navbar();
page_content($product);
page_footer();

function page_content($product){
?>

    <h1>Edit product:</h1>

    <form class="my-5" action="DBEditProduct.php?id=<?php echo $product->getId(); ?>" method="POST">
<!-- Display error message -->
<?php if( isset($_GET['error']) && $_GET['error'] != null ){ ?>
        <p class="text-danger"> <?php echo $_GET['error']; ?> </p>
<?php } ?>

        <div class="form-group">
            <label for="inputName">Name</label>
            <input type="text" class="form-control" name="inputName" placeholder="Doggo paw toy" value="<?php echo $product->getName(); ?>">
        </div>
        <div class="form-group">
            <label for="inputDescription">Description</label>
            <input type="textarea" class="form-control" name="inputDescription" placeholder="So cuuute" value="<?php echo $product->getDescription(); ?>">
        </div>
        <div class="form-group">
            <label for="inputPrice">Price</label>
            <input type="number" step="0.01" class="form-control" name="inputPrice" placeholder="19.99" value="<?php echo $product->getPrice(); ?>">
        </div>
        <button type="submit" class="btn btn-warning">Edit</button>
    </form>

<?php
}