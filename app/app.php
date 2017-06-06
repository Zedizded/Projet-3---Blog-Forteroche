<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1'
));
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());

// Register services
$app['dao.article'] = function ($app) {
    return new Projet3BlogForteroche\DAO\ArticleDAO($app['db']);
};
$app['dao.comment'] = function ($app) {
    $commentDAO = new Projet3BlogForteroche\DAO\CommentDAO($app['db']);
    $commentDAO->setArticleDAO($app['dao.article']);
    return $commentDAO;
};
