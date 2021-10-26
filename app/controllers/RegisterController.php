<?php

include "../vendor/autoload.php";

/**
 * Twig template loader
 * 
 */
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);  //Add cache folder on production!


if (empty($_POST)) {
    echo $twig->render('register.html.twig', ["var" => "Hello"]);
} else {
    $username = $_POST['username'];
    $email = $_POST['mail'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
}
