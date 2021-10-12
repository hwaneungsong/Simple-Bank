<?php
require(__DIR__."/../../partials/nav.php");
?>
<h1>Home</h1>
<?php
<<<<<<< HEAD
if(is_logged_in()){
    flash("Welcome, " . get_user_email());
}
else{
  flash("You're not logged in");
}
require(__DIR__ . "/../../partials/flash.php");
=======
if(isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])){
 echo "Welcome, " . $_SESSION["user"]["email"]; 
}
else{
  echo "You're not logged in";
}
>>>>>>> 9de322a41a1eade453295a88ed3ff2495558ace0
?>