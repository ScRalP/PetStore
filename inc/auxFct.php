<?php

session_start();

function page_head($titre = "My Shop"){
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $titre; ?></title>

    <!-- Style -->
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/style.css">
    
    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/7a789c345e.js" crossorigin="anonymous"></script>

    <!-- Bootstrap js -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <!-- JS -->
    <script src="/js/rotate.js"></script>
</head>

<?php
}

/**
 * Ajout de la barre de navigation
 */
function page_navbar(){
?>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-3">
    <a class="navbar-brand" href="/">PetStore</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_main" aria-controls="navbar_main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbar_main">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/"><i class="fas fa-home"></i> Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/src/consultProducts.php"><i class="fas fa-shopping-bag"></i> Products</a>
            </li>
        </ul>
        <ul class="navbar-nav">
<?php if( isset($_SESSION['username']) && $_SESSION['username'] != null ) { ?>
            <li class="nav-item">
                <a class="nav-link" href="/src/consultOrders.php"><i class="fas fa-list"></i> Previous orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/src/consultCart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/src/logout.php"><i class="fas fa-sign-out-alt"></i> Disconnect</a>
            </li>
<?php } else{ ?>
            <li class="nav-item">
                <a class="nav-link" href="/src/register.php"><i class="fab fa-wpforms"></i> Register</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/src/login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
            </li>
<?php } ?>
        </ul>
    </div>
</nav>

<div class="container">

<?php
}

function page_footer(){
?>

</div> <!-- Fin du container -->

<footer class="text-center mt-5 bg-primary ">

    <div class="py-3 text-white">© 2020 Copyright: Quentin ROBARD</div>
    
</footer>

</body>
</html>
    
<?php
}

/**
 * Session utilisateur
 * Le validate session doit etre appele lors d'une redirection
 * vers une page qu'un utilisateur peut accéder que lorsqu'il est
 * connecté.
 */
function validateSession(){
	if( !isset($_SESSION['username']) ){
		header('Location: login.php'); //Redirect to login page
    }
}

/**
 * Verifie que l'utilisateur est admin
 */
function isAdmin(){
    if( isset($_SESSION['role']) ){
        if( $_SESSION['role'] == "ROLE_ADMIN" ){
            return true;
        }
    }

    return false;
}