<?php

// Return all articles
function getArticles() {
    $bdd = new PDO('mysql:host=localhost;dbname=p3_blog_jforteroche;charset=utf8', 'jforteroche_admin', 'fHt4lvhk2SVwEkAB');
    $articles = $bdd->query('select * from t_article order by art_id');
    return $articles;
}