<?php

include '../../inc/auxFct.php';
if( !isAdmin() ){
    header('Location: /');
}

page_head("Add new product");
page_navbar();
page_content();
page_footer();

function page_content(){
?>

    <h1>Add product:</h1>

    <form class="my-5" action="DBAddProduct.php" method="POST">
<!-- Display error message -->
<?php if( isset($_GET['error']) && $_GET['error'] != null ){ ?>
        <p class="text-danger"> <?php echo $_GET['error']; ?> </p>
<?php } ?>

        <div class="form-group">
            <label for="inputName">Name</label>
            <input type="text" class="form-control" name="inputName" placeholder="Doggo paw toy">
        </div>
        <div class="form-group">
            <label for="inputDescription">Description</label>
            <input type="textarea" class="form-control" name="inputDescription" placeholder="So cuuute">
        </div>
        <div class="form-group">
            <label for="inputPrice">Price</label>
            <input type="number" step="0.01" class="form-control" name="inputPrice" placeholder="19.99">
        </div>
        <button type="submit" class="btn btn-success">Add</button>
    </form>

<?php
}