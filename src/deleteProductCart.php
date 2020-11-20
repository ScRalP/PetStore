<?php

include "../inc/auxFct.php";
require "../inc/DB.php";

validateSession();

if( isset($_GET['id']) && $_GET['id'] != null ){
    $product_id = $_GET['id'];
} else{
    header('Location: /src/consultCart.php');
}

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

        $productsCart = $db->getProductsCart( $product_id, $cart->getId() );

        if( count($productsCart) != 0 ){
            $db->deleteProductCart( $productsCart[0]->getId() );
        } else{
            header('Location: /src/consultCart.php');
        }
    }
    catch( Exception $e ) { $e->getMessage(); }
    $db->close();
}

header('Location: /src/consultCart.php?success=You successfully deleted 1 item');