<?php
session_start();
session_unset();
session_destroy();
flash("You have been logged out");
header("Location: login.php");
require(__DIR__ . "/../../partials/flash.php");