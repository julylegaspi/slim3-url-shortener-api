<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App([
    'settings' => [
        'baseUrl' => 'http://localhost:8080/dropbox/practice/php-slim/slim3-urlshortener-api/',
        'displayErrorDetails' => true,
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'slim-urlshortener',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => ''
        ]
    ],
    
]);


$container = $app->getContainer();

// LOAD ILLUMINATE DATABASE SUPPORT
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

$container['HomeController'] = function($container) {
    return new \App\Controllers\HomeController($container);
};
require __DIR__ . '/../app/routes.php';