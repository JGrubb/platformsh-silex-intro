<?php
/**
 * Created by PhpStorm.
 * User: jgrubb
 * Date: 2/28/17
 * Time: 11:12 AM
 */

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => APP_ROOT . '/templates',
]);

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'dbname' => 'larablog',
        'user' => 'jgrubb',
        'password' => '',
        'host' => 'localhost',
        'driver' => 'pdo_pgsql',
    ]
]);

$app->register(new Silex\Provider\HttpCacheServiceProvider(), [
    'http_cache.cache_dir' => APP_ROOT . '/cache',
    'http_cache.esi' => null,
]);