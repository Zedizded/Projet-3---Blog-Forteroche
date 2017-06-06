<?php

use Symfony\Component\HttpFoundation\Request;
use Projet3BlogForteroche\Domain\Comment;
use Projet3BlogForteroche\Form\Type\CommentType;

// Home page
$app->get('/', function () use ($app) {
    $articles = $app['dao.article']->findAll();
    return $app['twig']->render('index.html.twig', array('articles' => $articles));
})->bind('home');

// Comments details with add comments
$app->match('/article/{id}', function ($id, Request $request) use ($app) {
    $article = $app['dao.article']->find($id);
    $commentFormView = null;
    $comment = new Comment();
    $comment->setArticle($article);
    $newDate = new DateTime('now');
    $comment->setDate($newDate->format('Y-m-d H:i:s'));
    $comment->setFlagged(0);
    $commentForm = $app['form.factory']->create(CommentType::class, $comment);
    $commentForm->handleRequest($request);
    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
        $app['dao.comment']->save($comment);
        }
    $commentFormView = $commentForm->createView();
    $comments = $app['dao.comment']->findAllByArticle($id);

    return $app['twig']->render('comment.html.twig', array(
        'article' => $article, 
        'comments' => $comments,
        'commentForm' => $commentFormView));
})->bind('article');