<?php

namespace Projet3BlogForteroche\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Projet3BlogForteroche\Domain\Comment;
use Projet3BlogForteroche\Domain\Article;
use Projet3BlogForteroche\DAO\CommentDAO;
use Projet3BlogForteroche\Form\Type\CommentType;
use Projet3BlogForteroche\Form\Type\ArticleType;

class AdminController {

    /**
     * Admin home page controller.
     *
     * @param Application $app Silex application
     */
    public function indexAction(Application $app) {
        $articles = $app['dao.article']->findAll();
        $comments = $app['dao.comment']->findAll();
        return $app['twig']->render('admin.html.twig', array(
            'articles' => $articles,
            'comments' => $comments));
    }

    /**
     * Add article controller.
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function addArticleAction(Request $request, Application $app) {
        $article = new Article();
        $content = ' ';
        $article->setContent($content);
        $newArticleDate = date('Y-m-d H:i:s');
        $article->setDate($newArticleDate);
        $articleForm = $app['form.factory']->create(ArticleType::class, $article);
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $app['dao.article']->save($article);
            $app['session']->getFlashBag()->add('success', 'Le nouvel article a bien été créé');
        }

        return $app['twig']->render('article_form.html.twig', array(
            'title' => 'Nouvel article',
            'articleForm' => $articleForm->createView()));
    }

    /**
     * Edit article controller.
     *
     * @param integer $id Article id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function editArticleAction($id, Request $request, Application $app) {
        $article = $app['dao.article']->find($id);
        $newArticleDate = date('Y-m-d H:i:s');
        $article->setDate($newArticleDate);
        $articleForm = $app['form.factory']->create(ArticleType::class, $article);
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $app['dao.article']->save($article);
            $app['session']->getFlashBag()->add('success', 'L\'article a bien été mis à jour');
        }

        return $app['twig']->render('article_form.html.twig', array(
            'title' => 'Éditer l\'article',
            'articleForm' => $articleForm->createView()));
    }

    /**
     * Delete article controller.
     *
     * @param integer $id Article id
     * @param Application $app Silex application
     */
    public function deleteArticleAction($id, Application $app) {
        // Delete all associated comments
        $app['dao.comment']->deleteAllByArticle($id);

        // Delete the article
        $app['dao.article']->delete($id);
        $app['session']->getFlashBag()->add('success', 'L\'article a bien été supprimé');

        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    /**
     * Edit comment controller.
     *
     * @param integer $id Comment id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function editCommentAction($id, Request $request, Application $app) {
        $comment = $app['dao.comment']->find($id);
        $newCommentDate = date('Y-m-d H:i:s');
        $comment->setDate($newCommentDate);
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
    }

    /**
     * Delete comment controller.
     *
     * @param integer $id Comment id
     * @param Application $app Silex application
     */
    public function deleteCommentAction($id, Application $app) {
        $app['dao.comment']->delete($id);
        $app['session']->getFlashBag()->add('success', 'Le commentaire a bien été supprimé');

        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }
}
