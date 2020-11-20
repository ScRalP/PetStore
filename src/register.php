<?php

include "../inc/auxFct.php";

page_head("Register - PetStore");
page_navbar();
page_content();
page_footer();

function page_content(){
?>

    <form class="my-5" action="checkRegister.php" method="POST">
<?php if( isset($_GET['error']) && $_GET['error'] != null ){ ?>
        <p class="text-danger"> <?php echo $_GET['error']; ?> </p>
<?php } ?>
        <div class="form-group">
            <label for="inputEmail">Username</label>
            <input type="text" class="form-control" name="inputUsername" placeholder="xX-DarkSasuke-Xx">
        </div>
        <div class="form-group">
            <label for="inputEmail">Email address</label>
            <input type="email" class="form-control" name="inputEmail" placeholder="jerome.delapoint@orange.com">
        </div>
        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input type="password" class="form-control" name="inputPassword" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

<?php
}