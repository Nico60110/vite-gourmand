<?php

session_start();

if(
    !isset($_SESSION['user'])
    || (
        $_SESSION['user']['idRole'] != 1
        && $_SESSION['user']['idRole'] != 2
    )
){

    header("Location: ../../index.php");

    exit;
}
