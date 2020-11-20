<?php

require '../inc/DB.php';

$username = $_REQUEST['inputUsername'];
$email    = $_REQUEST['inputEmail'];
$password = $_REQUEST['inputPassword'];

$errorMessage = "";

if ( checkRegister($username, $email, $password, $errorMessage) ){
    header('Location: /src/login.php');
}
else{
    if( empty($errorMessage) ){
        header('Location: /src/register.php');
    }
    else{
        header('Location: /src/register.php?error='.$errorMessage);
    }
}

function checkRegister($username, $email, $password, &$errorMessage){

    if( filter_var($email, FILTER_VALIDATE_EMAIL) ){
        if( strlen($password)>8 ){
            if( strlen($username)<255 ){
                //Fetch in database if user exist
                $db = DB::getInstance();
                if( $db == null ){
                    $errorMessage = "Please try again later";
                }
                else{
                    try{
                        $user = $db->getUserByEmail($email);

                    }
                    catch( Exception $e ) { $e->getMessage(); }
                    
                    if( $user != null ){
                        $errorMessage = "Email adress already use";
                        return false;
                    }
                    
                    addUserToDB($username, $email, $password, $db);
                    $db->close();
                    return true;
                }
            } else{
                $errorMessage = "Username too long";
            }
        } else{
            $errorMessage = "The password must be over 8 character";
        }
    } else{
        $errorMessage = "Invalid email input";
    }

    return false;
}

function addUserToDB($username, $email, $password, $db){

    try{
        $db->insertUser(null, $username, $email, $password, "ROLE_USER");

        $user = $db->getUserByEmail($email);

        $db->insertCart(null, $user->getId());
    }
    catch( Exception $e ) { $e->getMessage(); }

}