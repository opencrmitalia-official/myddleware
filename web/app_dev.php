<?php

//var_dump($headers['Authorization']);
//die();

//$headers = getallheaders();
//$_SERVER['HTTP_AUTHORIZATION'] = $headers['Authorization'];

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

ini_set('session.save_path', __DIR__.'/../var/sessions' );

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

require __DIR__.'/../app/autoload.php';
//require __DIR__.'/../vendor/autoload.php';
Debug::enable();


//var_dump($_POST);

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();

$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);