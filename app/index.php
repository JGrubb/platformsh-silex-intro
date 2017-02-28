<?php
/**
 * Created by PhpStorm.
 * User: jgrubb
 * Date: 8/10/16
 * Time: 4:27 PM
 */
use Symfony\Component\HttpFoundation\Response;

define('APP_ROOT', realpath(__DIR__ . '/../'));

require_once APP_ROOT . '/vendor/autoload.php';
require_once APP_ROOT . '/inc/registration.php';
require_once APP_ROOT . '/inc/filters.php';

$app->get('/', function() use ($app) {
    $sql = "SELECT * from posts ORDER BY pub_date DESC LIMIT 5";
    $posts = $app['db']->fetchAll($sql);
    $content = $app['twig']->render('home.twig', [
        'posts' => $posts
    ]);
    return new Response($content, 200, [
        'cache-control' => 'public, max-age=30'
    ]);
});

$app->get('/posts/{id}-{slug}', function($id, $slug) use ($app) {
    $sql = "SELECT * from posts where id = ?";
    $post = $app['db']->fetchAssoc($sql, [(int) $id]);
    if ($post['slug'] !== $slug) {
        return $app->redirect("/posts/{$post['id']}-{$post['slug']}");
    }
    $content = $app['twig']->render('posts/show.twig', [
        'post' => $post
    ]);
    return new Response($content, 200, [
        'cache-control' => 'public, max-age=30'
    ]);
});

$app->get('/posts', function() use ($app) {
    $sql = "SELECT * from posts order by pub_date DESC";
    $posts = $app['db']->fetchAll($sql);
    $content = $app['twig']->render('posts/index.twig', [
        'posts' => $posts
    ]);
    return new Response($content, 200, [
        'cache-control' => 'public, max-age=30'
    ]);
});

$app['http_cache']->run();