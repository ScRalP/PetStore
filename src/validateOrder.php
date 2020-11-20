<?php

include '../inc/auxFct.php';
require '../inc/DB.php';

validateSession();

if( isset($_SESSION['email']) && $_SESSION['email'] != null ){
    $email = $_SESSION['email'];
} else{
    header('Location: /src/consultCart.php');
}

//Fetch in database user
$db = DB::getInstance();
if( $db == null ){
    echo "<p>Please try again later</p>";
}
else{
    try{
        $user = $db->getUserByEmail($email);
        $cart = $db->getCartByUserId($user->getId());

        //Verification
        $products = $db->getProductsByCartId($cart->getId());
        if( $products == 0 ){
            $db->close();
            header('Location: /src/consultCart.php');
        }

        $date = new DateTime();
        $db->insertOrder(null, $date->format('Y-m-d H:i:s'), $cart->getId(), $user->getId()); //commande crée

        $db->deleteCart( $cart->getId() );  //Supprimer le panier de l'utilisateur
        $db->insertCart( null, $user->getId() );//Recréer un panier vide
    }
    catch( Exception $e ) { $e->getMessage(); }
    
    $db->close();
}

header('Location: /src/consultOrders.php');