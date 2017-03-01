<?php
/**
 * Created by PhpStorm.
 * User: jgrubb
 * Date: 3/1/17
 * Time: 10:36 AM
 */

function tag_posts($posts, Silex\Application $app) {
    $stmt = $app['db']->executeQuery('select "tags".*, "post_tag"."post_id", "post_tag"."tag_id" from "tags" inner join "post_tag" on "tags"."id" = "post_tag"."tag_id" where "post_tag"."post_id" in (?)',
        [array_column($posts, 'id')],
        [Doctrine\DBAL\Connection::PARAM_INT_ARRAY]
    );
    $tags = $stmt->fetchAll();

    foreach ($posts as &$post) {
        foreach ($tags as $tag) {
            if ($post['id'] === $tag['post_id']) {
                $post['tags'][] = $tag;
            }
        }
    }
    return $posts;
}