<?php

include '../inc/auxFct.php';
require "../inc/DB.php";

validateSession();

if (isset($_GET['id']) && $_GET['id'] != null) {
    addProductToCart($_GET['id']);
    header('Location: /src/consultCart.php');
} else {
    header('Location: /src/consultProducts.php');
}

function addProductToCart($product_id)
{
    if( isset($_SESSION['email']) && $_SESSION != null ){
        $email = $_SESSION['email'];
    } else{
        header('Location: /src/consultProducts.php');
    }

    //Connection to DataBase
    $db = DB::getInstance();
    if ($db == null)
        echo '<p class="text-danger">base de donnée inaccessible</p>';
    else {
        try {
            $user = $db->getUserByEmail( $email );
            $cart = $db->getCartByUserId($user->getId());

            $db->insertProductCart(null, $product_id, $cart->getId());
        } catch (Exception $e) {
            $e->getMessage();
        }
        $db->close();
    }
}
