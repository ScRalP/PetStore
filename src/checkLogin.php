<?php

require '../inc/DB.php';

$email    = $_REQUEST['inputEmail'];
$password = $_REQUEST['inputPassword'];

$errorMessage = "";

if ( checkLogin($email, $password, $errorMessage) ){
    header('Location: /index.php');
}
else{
    if( empty($errorMessage) ){
        header('Location: /src/login.php');
    }
    else{
        header('Location: /src/login.php?error='.$errorMessage);
    }
}

function checkLogin($email, $password, &$errorMessage){

    if( filter_var($email, FILTER_VALIDATE_EMAIL) ){
        if( strlen($password)>8 ){
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
                $db->close();

                if( $user != null ){

                    //Check if the password match the password in database
                    if( $user->getPassword() == $password ){
                        //All informations are correct
                        //Store the users information in the session
                        session_start();

                        $_SESSION['username'] = $user->getUsername();
                        $_SESSION['email']    = $user->getEmail();
                        $_SESSION['role']     = $user->getRole();

                        return true;
                    }
                    else{
                        $errorMessage = "The given password is incorrect";
                    }
                }
                else{
                    $errorMessage = "The email does not exist";
                }
            }
        } else{
            $errorMessage = "The password must be over 8 character";
        }
    } else{
        $errorMessage = "Invalid email input";
    }

    return false;
}
