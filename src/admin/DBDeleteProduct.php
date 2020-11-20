<?php

include '../../inc/auxFct.php';
if( !isAdmin() ){
    header('Location: /');
}

require '../../inc/DB.php';

if( isset($_GET['id']) && $_GET['id'] != null ){
    $id = $_GET['id'];
} else{
    $id = null;
}

if( productExist($id) ){
    header('Location: /src/consultProducts.php?success=Product sucessfully deleted');
} else{
    header('Location: /src/consultProducts.php');
}

function productExist($id){
    if( $id != null ){
        //Connection to DataBase
        $db = DB::getInstance();
        if( $db == null )
            echo '<p class="text-danger">Try later...</p>';
        else{
            try{
                $db->deleteProduct($id);
                return true;
            }
            catch( Exception $e ) { $e->getMessage(); }
            $db->close();
        }
    }

    return false;
}