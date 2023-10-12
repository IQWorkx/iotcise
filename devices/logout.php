<?php
session_start();
//// unset all $_SESSION variables
session_regenerate_id();
session_unset();
if (session_destroy()) {
    header("Location: index.php");
}
?>