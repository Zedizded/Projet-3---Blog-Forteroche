<?php

namespace Projet3BlogForteroche\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Projet3BlogForteroche\Domain\Comment;
use Projet3BlogForteroche\Domain\Article;
use Projet3BlogForteroche\Domain\User;
use Projet3BlogForteroche\DAO\CommentDAO;
use Projet3BlogForteroche\DAO\UserDAO;
use Projet3BlogForteroche\Form\Type\CommentType;
use Projet3BlogForteroche\Form\Type\ArticleType;
use Projet3BlogForteroche\Form\Type\UserType;


class HomeController {

    /**
     * Home page controller.
     *
     * @param Application $app Silex application
     */
    public function indexAction(Application $app) {
        $articles = $app['dao.article']->findAll();
        return $app['twig']->render('index.html.twig', array('articles' => $articles));
    }
    
    
    /**
     * All comments by article details controller.
     *
     * @param integer Article id by ajax
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function allCommentAction(Request $request, Application $app) {
        $articleID = $request->query->get('id');
        $article = $app['dao.article']->find($articleID);
        $comments = $app['dao.comment']->findAllByArticle($articleID);

        return $app['twig']->render('comment.html.twig', array(
            'article' => $article, 
            'comments' => $comments));
    }
    
    /**
     * Add comment controller.
     *
     * @param integer Article id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function addCommentAction(Request $request, Application $app) {
        $author = $request->request->get('author');
        $email = $request->request->get('email');
        $content = $request->request->get('content');
        $articleID = $request->request->get('id');
        $parentId = $request->request->get('parent');
        $comment = new Comment();
        $newCommentDate = date('Y-m-d H:i:s');
        $article = $app['dao.article']->find($articleID);
        $comment->setArticle($article);
        $comment->setAuthor($author);
        $comment->setEmail($email);
        $comment->setContent($content);
        $comment->setDate($newCommentDate);
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
    }
    
    /**
     * Flag comment controller.
     *
     * @param integer Article id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function flagCommentAction(Request $request, Application $app) {
        $commentId = $request->request->get('id');
        $comment = $app['dao.comment']->find($commentId);
        $app['dao.comment']->updateFlag($commentId, 1);

        return $app->json(200);    
    }
    
    /**
     * User login controller.
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function loginAction(Request $request, Application $app) {
        return $app['twig']->render('login.html.twig', array(
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }
}
