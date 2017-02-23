<?php
/**
 * Created by PhpStorm.
 * User: jgrubb
 * Date: 8/10/16
 * Time: 4:27 PM
 */

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello ' . $app->escape($name);
});

$app->run();