<?php

namespace App\Controllers;

include "../vendor/autoload.php";

session_start();

//Redirect to homepage if user is not logged
if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true) {
    header('location: login');
}


/**
 * Twig template loader
 * 
 */
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);  //Add cache folder on production!

//Add user picture system
$_SESSION['user-pic'] = null;

echo $twig->render('home.html.twig', [
    "username" => $_SESSION['username'],
    "user_pic_uri" => $_SESSION['user-pic']
]);
