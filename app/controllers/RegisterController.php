<?php

include "../vendor/autoload.php";

/**
 * Twig template loader
 * 
 */
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);  //Add cache folder on production!





echo $twig->render('register.html.twig',["var"=>"Hello"]);

