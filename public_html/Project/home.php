<?php
require(__DIR__."/../../partials/nav.php");
?>
<h1>Welcome to Your Bank</h1>
<?php
if(is_logged_in(true)){
    flash("Welcome, " . get_username());
    //echo"<pre>" . var_export($_SESSION, true) . "<pre>";
}
?>
<?php
require(__DIR__ . "/../../partials/footer.php");
?>  