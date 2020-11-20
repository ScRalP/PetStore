<?php

include '../../inc/auxFct.php';
if( !isAdmin() ){
    header('Location: /');
}

require '../../inc/DB.php';

$name  = $_REQUEST['inputName'];
$price = $_REQUEST['inputPrice'];

if( isset($_REQUEST['inputDescription']) && $_REQUEST['inputDescription'] != null ){
    $description = $_REQUEST['inputDescription'];
} else{
    $description = null;
}


$errorMessage = "";

if ( checkProduct($name, $price, $description, $errorMessage) ){
    header('Location: /src/consultProducts.php');
}
else{
    if( empty($errorMessage) ){
        header('Location: /src/admin/formAddProduct.php');
    }
    else{
        header('Location: /src/admin/formAddProduct.php?error='.$errorMessage);
    }
}

function checkProduct($name, $price, $description, &$errorMessage){
    if(strlen($name) > 4){
        if(strlen($name) < 255){
            if(strlen($description) < 1000){
                if( $price > 0){
                    addProductToDB($name, $price, $description);
                    return true;
                } else{
                    $errorMessage = "The price must be positive";
                }
            } else{
                $errorMessage = "The description is too long (1000 character max)";
            }
        } else{
            $errorMessage = "Name too long (255 character max)";
        }
    } else{
        $errorMessage = "Name too short (4 character min)";
    }

    return false;
}

function addProductToDB($name, $price, $description){
    //Connection to DataBase
	$db = DB::getInstance();
	if( $db == null )
		echo '<p class="text-danger">Try later...</p>';
	else{
		try{
			$db->insertProduct(null, $name, $price, $description);
		}
		catch( Exception $e ) { $e->getMessage(); }
		$db->close();
	}
}