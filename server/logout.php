<?php 
    session_start();
    session_unset();
    session_destroy();

    //Head to homepage
    header("Location: ../index.php");
    exit();

?>