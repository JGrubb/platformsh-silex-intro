<?php
/**
 * Created by PhpStorm.
 * User: jgrubb
 * Date: 2/28/17
 * Time: 2:52 PM
 */

use Michelf\MarkdownExtra;
use Urodoz\Truncate\TruncateService;

$filter = new Twig_Filter('markdown', function($string) {
    $parser = new MarkdownExtra();
    $parser->code_class_prefix = "language-";
    return $parser->transform($string);
}, ['is_safe' => ['html']]);

$app['twig']->addFilter($filter);

$filter = new Twig_Filter('truncate', function($string) {
    $truncator = new TruncateService();
    return $truncator->truncate($string, 500);
}, ['is_safe' => ['html']]);

$app['twig']->addFilter($filter);