<?php

include "../vendor/autoload.php";
require "/services/database.php";

/**
 * Twig template loader
 * 
 */
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);  //Add cache folder on production!


//Database conexion instance
$data = new \App\Services\Database();
$data->connect();


//If the petition is not for loging in the login view is shown
if(empty($_POST)){
  echo $twig->render('login.html.twig',["var"=>"Hello"]);
}
//This block of code executes when the user makes a post to the login form
else{
    
    echo "Loging....";


}
