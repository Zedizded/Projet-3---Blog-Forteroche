<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="blog.css" rel="stylesheet" />
    <title>Billet simple pour l'Alaska | Accueil</title>
</head>
<body>
    <header>
        <h1>Billet simple pour l'Alaska</h1>
    </header>
    <?php foreach ($articles as $article): ?>
    <article>
        <h2><?php echo $article['art_title'] ?></h2>
        <p><?php echo nl2br($article['art_content']) ?></p>
        <small class="pull-right"><?php echo 'Publié le ', $article['art_date'], ' par Jean Forteroche' ?></small><br>
    </article>
    <?php endforeach ?>
    <footer class="footer">
        <p>Billet simple pour l'Alaska - Jean Forteroche - Tous droits réservés.</p>
        <p>Création originale <a href="http://zcomdezigne.com">ZcOmDeZign ©</a></p>
    </footer>
    
</body>
</html>