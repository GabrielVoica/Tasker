<?php

namespace App\Controllers;

include "../vendor/autoload.php";

session_start();

//Redirect to homepage if user is not logged
if(!isset($_SESSION['logged']) || $_SESSION['logged'] != true ){
    header('location: login');
}


/**
 * Twig template loader
 * 
 */
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);  //Add cache folder on production!


echo $twig->render('home.html.twig',["var"=>"Hello"]);





