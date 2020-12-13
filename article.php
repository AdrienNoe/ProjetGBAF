<?php

session_start();

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=gbaf;charset=utf8', 'root', '');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

if(isset($_GET['id']) AND !empty($_GET['id'])) {
   $get_id = htmlspecialchars($_GET['id']);
   $article = $bdd->prepare('SELECT * FROM partners WHERE id = ?');
   $article->execute(array($get_id));
   if($article->rowCount() == 1) {
      $article = $article->fetch();
      $id = $article['id'];
      $name = $article['name'];
      //$contenu = $article['contenu'];//
      $likes = $bdd->prepare('SELECT id FROM likes WHERE id_article = ?');
      $likes->execute(array($id));
      $likes = $likes->rowCount();
      $dislikes = $bdd->prepare('SELECT id FROM dislikes WHERE id_article = ?');
      $dislikes->execute(array($id));
      $dislikes = $dislikes->rowCount();
   } else {
      die('Cet article n\'existe pas !');
   }
} else {
   die('Erreur');
}
?>
<!DOCTYPE html>
<html>
<head>
   <title>Accueil</title>
   <meta charset="utf-8">
</head>
<body>
   <img src="miniatures/<?= $id ?>.jpg" width="400" />
   <h1>Commentaire</h1>
   <a href="php/action.php?t=1&id=<?= $id ?>">J'aime</a> (<?= $likes ?>)
   <br />
   <a href="php/action.php?t=2&id=<?= $id ?>">Je n'aime pas</a> (<?= $dislikes ?>) 
</body>
</html>

?>