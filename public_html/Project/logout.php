<?php
session_start();
session_unset();
session_destroy();
session_start();
require(__DIR__ . "/../../lib/functions.php");
flash("You have been logged out");
<<<<<<< HEAD
header("Location: login.php");

=======
header("Location: login.php");
>>>>>>> 9de322a41a1eade453295a88ed3ff2495558ace0
