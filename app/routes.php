<?php

use Symfony\Component\HttpFoundation\Request;
use Projet3BlogForteroche\Domain\Comment;
use Projet3BlogForteroche\Domain\Article;
use Projet3BlogForteroche\Domain\User;
use Projet3BlogForteroche\DAO\CommentDAO;
use Projet3BlogForteroche\DAO\UserDAO;
use Projet3BlogForteroche\Form\Type\CommentType;
use Projet3BlogForteroche\Form\Type\ArticleType;
use Projet3BlogForteroche\Form\Type\UserType;

// Home page
$app->get('/', 'Projet3BlogForteroche\Controller\HomeController::indexAction')
->bind('home');



// Comments details
$app->match('/allComments', 'Projet3BlogForteroche\Controller\HomeController::allCommentAction')
->bind('comment');



// Add comment
$app->match('/addComment', 'Projet3BlogForteroche\Controller\HomeController::addCommentAction')
->bind('addComment');



// Flag a comment by Ajax
$app->post('/signal', 'Projet3BlogForteroche\Controller\HomeController::flagCommentAction')
->bind('signal');



// Login form
$app->get('/login', 'Projet3BlogForteroche\Controller\HomeController::loginAction')
->bind('login');



// Admin home page
$app->match('/admin', 'Projet3BlogForteroche\Controller\AdminController::indexAction')
->bind('admin');



// Add a new article
$app->match('/admin/article/add', 'Projet3BlogForteroche\Controller\AdminController::addArticleAction')
->bind('admin_article_add');



// Edit an existing article
$app->match('/admin/article/{id}/edit', 'Projet3BlogForteroche\Controller\AdminController::editArticleAction')
->bind('admin_article_edit');



// Remove an article
$app->get('/admin/article/{id}/delete', 'Projet3BlogForteroche\Controller\AdminController::deleteArticleAction')
->bind('admin_article_delete');



// Edit an existing comment
$app->match('/admin/comment/{id}/edit', 'Projet3BlogForteroche\Controller\AdminController::editCommentAction')
->bind('admin_comment_edit');



// Remove a comment
$app->get('/admin/comment/{id}/delete', 'Projet3BlogForteroche\Controller\AdminController::deleteCommentAction')
->bind('admin_comment_delete');