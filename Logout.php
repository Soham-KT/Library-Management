<?php
session_start();
unset($_SESSION["username"]);
// unset($_SESSION["password"]);
session_destroy();
include "index.php";
echo "<script>window.location.reload();</script>";
?>