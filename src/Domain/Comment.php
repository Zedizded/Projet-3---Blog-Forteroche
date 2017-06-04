<?php

namespace Projet3BlogForteroche\Domain;

class Comment 
{
    /**
     * Comment id.
     *
     * @var integer
     */
    private $id;

    /**
     * Comment author.
     *
     * @var string
     */
    private $author;

    /**
     * Comment email.
     *
     * @var string
     */
    private $email;

    /**
     * Comment content.
     *
     * @var integer
     */
    private $content;

    /**
     * Comment date.
     *
     * @var datetime
     */
    private $date;

    /**
     * Comment flagged.
     *
     * @var bolean
     */
    private $flagged;
    
    /**
     * Associated article.
     *
     * @var \Projet3BlogForteroche\Domain\Article
     */
    private $article;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    public function getFlagged() {
        return $this->flagged;
    }

    public function setFlagged($flagged) {
        $this->flagged = $flagged;
        return $this;
    }

    public function getArticle() {
        return $this->article;
    }

    public function setArticle(Article $article) {
        $this->article = $article;
        return $this;
    }
}