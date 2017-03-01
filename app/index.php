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
require_once APP_ROOT . '/inc/random.php';

$app->get('/', function() use ($app) {
    $sql = "SELECT * from posts ORDER BY pub_date DESC LIMIT 5";
    $posts = $app['db']->fetchAll($sql);

    $posts = tag_posts($posts, $app);

    $content = $app['twig']->render('home.twig', [
        'posts' => $posts
    ]);
    return new Response($content, 200, [
        'cache-control' => 'public, max-age=30'
    ]);
});

$app->get('/posts/{id}-{slug}', function($id, $slug) use ($app) {
//    $sql = "SELECT * from posts where id = ?";
    $query = $app['db']->createQueryBuilder();
    $sql = $query->select('*')
        ->from('posts', 'p')
        ->where('id = :post_id');

    $post = $app['db']->fetchAssoc($sql, [(int) $id]);

    $sql = 'select "tags".*, "post_tag"."post_id", "post_tag"."tag_id" from "tags" inner join "post_tag" on "tags"."id" = "post_tag"."tag_id" where "post_tag"."post_id" in (?)';

    if ($post['slug'] !== $slug) {
        return $app->redirect("/posts/{$post['id']}-{$post['slug']}");
    }

    $post['tags'] = $app['db']->fetchAll($sql, [$id]);

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
    $posts = tag_posts($posts, $app);
    $content = $app['twig']->render('posts/index.twig', [
        'posts' => $posts
    ]);
    return new Response($content, 200, [
        'cache-control' => 'public, max-age=30'
    ]);
});

$app->get('/tags/{tag_slug}', function($tag_slug) use ($app) {
    $query = $app['db']->createQueryBuilder();
    $sql = $query->select('*')
        ->from('tags', 't')
        ->where('t.slug = ?');
    $tag = $app['db']->fetchAssoc($sql, [$tag_slug]);

    $stmt = $app['db']->executeQuery('SELECT p.* from posts p INNER JOIN post_tag pt on p.id = pt.post_id where pt.tag_id = ? ORDER BY p.pub_date DESC',
        [$tag['id']]);
    $posts = $stmt->fetchAll();
    $posts = tag_posts($posts, $app);
    $content = $app['twig']->render('posts/index.twig', [
        'posts' => $posts
    ]);
    return new Response($content, 200, [
        'cache-control' => 'public, max-age=30'
    ]);
});

$app['http_cache']->run();