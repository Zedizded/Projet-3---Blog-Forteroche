<?php

use Symfony\Component\HttpFoundation\Request;
use Projet3BlogForteroche\Domain\Comment;
use Projet3BlogForteroche\Domain\Article;
use Projet3BlogForteroche\DAO\CommentDAO;
use Projet3BlogForteroche\Form\Type\CommentType;
use Projet3BlogForteroche\Form\Type\ArticleType;

// Home page
$app->get('/', function () use ($app) {
    $articles = $app['dao.article']->findAll();
    
    return $app['twig']->render('index.html.twig', array('articles' => $articles));
})->bind('home');



// Comments details
$app->match('/allComments', function (Request $request) use ($app) {
    $articleID = $request->query->get('id');
    $article = $app['dao.article']->find($articleID);
    $comments = $app['dao.comment']->findAllByArticle($articleID);
    
    return $app['twig']->render('comment.html.twig', array(
        'article' => $article, 
        'comments' => $comments));
})->bind('comment');



// Add comment
$app->match('/addComment', function (Request $request) use ($app) {
    $author = $request->request->get('author');
    $email = $request->request->get('email');
    $content = $request->request->get('content');
    $articleID = $request->request->get('id');
    $parentId = $request->request->get('parent');
    $comment = new Comment();
    $newCommentDate = new DateTime('now');
    $article = $app['dao.article']->find($articleID);
    $comment->setArticle($article);
    $comment->setAuthor($author);
    $comment->setEmail($email);
    $comment->setContent($content);
    $comment->setDate($newCommentDate->format('Y-m-d H:i:s'));
    $comment->setFlagged(0);
    
    if($parentId){        
        $comment->setComParent($parentId);
    }
    
    $app['dao.comment']->save($comment);
    $app['session']->getFlashBag()->add('success', 'Le commentaire a bien été ajouté');
    $comments = $app['dao.comment']->findAllByArticle($articleID);
    
    return $app['twig']->render('comment.html.twig', array(
        'article' => $article, 
        'comments' => $comments));
})->bind('addComment');



// Flag a comment by Ajax
$app->post('/signal', function (Request $request) use ($app) {
    $commentId = $request->request->get('id');
    $comment = $app['dao.comment']->find($commentId);
    $app['dao.comment']->updateFlag($commentId, 1);
    
    return $app->json(200);    
})->bind('signal');



// Login form
$app->get('/login', function(Request $request) use ($app) {
    
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');



// Admin home page
$app->match('/admin', function() use ($app) {
    $articles = $app['dao.article']->findAll();
    $comments = $app['dao.comment']->findAll();
    
    return $app['twig']->render('admin.html.twig', array(
        'articles' => $articles,
        'comments' => $comments));
})->bind('admin');



// Add a new article
$app->match('/admin/article/add', function(Request $request) use ($app) {
    $article = new Article();
    $content = ' ';
    $article->setContent($content);
    $newArticleDate = new DateTime('now');
    $article->setDate($newArticleDate->format('Y-m-d H:i:s'));
    $articleForm = $app['form.factory']->create(ArticleType::class, $article);
    $articleForm->handleRequest($request);
    
    if ($articleForm->isSubmitted() && $articleForm->isValid()) {
        $app['dao.article']->save($article);
        $app['session']->getFlashBag()->add('success', 'Le nouvel article a bien été créé');
    }
    
    return $app['twig']->render('article_form.html.twig', array(
        'title' => 'Nouvel article',
        'articleForm' => $articleForm->createView()));
})->bind('admin_article_add');



// Edit an existing article
$app->match('/admin/article/{id}/edit', function($id, Request $request) use ($app) {
    $article = $app['dao.article']->find($id);
    $newArticleDate = new DateTime('now');
    $article->setDate($newArticleDate->format('Y-m-d H:i:s'));
    $articleForm = $app['form.factory']->create(ArticleType::class, $article);
    $articleForm->handleRequest($request);
    
    if ($articleForm->isSubmitted() && $articleForm->isValid()) {
        $app['dao.article']->save($article);
        $app['session']->getFlashBag()->add('success', 'L\'article a bien été mis à jour');
    }
    
    return $app['twig']->render('article_form.html.twig', array(
        'title' => 'Éditer l\'article',
        'articleForm' => $articleForm->createView()));
})->bind('admin_article_edit');



// Remove an article
$app->get('/admin/article/{id}/delete', function($id, Request $request) use ($app) {
    
    // Delete all associated comments
    $app['dao.comment']->deleteAllByArticle($id);
    
    // Delete the article
    $app['dao.article']->delete($id);
    $app['session']->getFlashBag()->add('success', 'L\'article a bien été supprimé');
    
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_article_delete');



// Edit an existing comment
$app->match('/admin/comment/{id}/edit', function($id, Request $request) use ($app) {
    $comment = $app['dao.comment']->find($id);
    $newCommentDate = new DateTime('now');
    $comment->setDate($newCommentDate->format('Y-m-d H:i:s'));
    $comment->setFlagged(0);
    $commentForm = $app['form.factory']->create(CommentType::class, $comment);
    $commentForm->handleRequest($request);
    
    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
        $app['dao.comment']->save($comment);
        $app['session']->getFlashBag()->add('success', 'Le commentaire a bien été mis à jour');
    }
    
    return $app['twig']->render('comment_form.html.twig', array(
        'title' => 'Éditer le commentaire',
        'commentForm' => $commentForm->createView()));
})->bind('admin_comment_edit');



// Remove a comment
$app->get('/admin/comment/{id}/delete', function($id, Request $request) use ($app) {
    $app['dao.comment']->delete($id);
    $app['session']->getFlashBag()->add('success', 'Le commentaire a bien été supprimé');
    
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_comment_delete');