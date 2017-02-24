<?php
/**
 * Created by PhpStorm.
 * User: jgrubb
 * Date: 8/10/16
 * Time: 4:27 PM
 */
define('APP_ROOT', realpath(__DIR__ . '/../'));
require_once APP_ROOT . '/vendor/autoload.php';

$app = new Silex\Application();

$app->get('/{name}', function ($name) use ($app) {
    $loader = new Twig_Loader_Filesystem(APP_ROOT . '/templates');
    $twig = new Twig_Environment($loader, [
        'cache' => FALSE
    ]);
    return $twig->render('index.html', [
        'name' => $name
    ]);
});

$app->run();