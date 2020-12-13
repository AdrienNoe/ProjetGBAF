<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Mon blog</title>
    <link href="style.css" rel="stylesheet" /> 
    </head>
        
    <body>
        <h1>Mon super blog !</h1>
        <p><a href="index.php">Retour à la liste des billets</a></p>
        <a href="action.php?t=1&id_partners=<?= $id ?>">J'aime</a> (15)
        <br />
        <a href="action.php?t=2&id_partners=<?= $id ?>">Je n'aime pas</a> (1)
 
<?php
// Connexion à la base de données
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=gbaf;charset=utf8', 'root', '');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

// Récupération du billet
$req = $bdd->prepare('SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr FROM billets WHERE id = ?');
$req->execute(array($_GET['billet']));
$donnees = $req->fetch();
?>

<div class="news">
    <h3>
        <?php echo htmlspecialchars($donnees['titre']); ?>
        <em>le <?php echo $donnees['date_creation_fr']; ?></em>
    </h3>
    
    <p>
    <?php
    echo nl2br(htmlspecialchars($donnees['contenu']));
    ?>
    </p>
</div>

<?php

if(isset($_GET['id']) AND !empty($_GET['id']))
{
    $getid = htmlspecialchars($_GET['id']);
    $billet = $bdd->prepare('SELECT * FROM billets WHERE id = ?');
    $billet->execute(array($getid));
    $billet = $billet->fetch();

    if(isset($_POST['submit_comment']))
    {
        if(isset($_POST['pseudo'], $_POST['commentaire']) AND !empty($_POST['pseudo']) AND !empty($_POST['commentaire']))
        {

        }
        else
        {
            $c_erreur = "Tous les champs doivent-être complétés !";
        }
    }

?>

<div id="comments_section">
    <h2>Poster un commentaire</h2>
         <form method="POST" action="">
            <input type="text" name="pseudo" placeholder="Votre prénom"><br />
            <textarea name="commentaire" placeholder="Votre commentaire"></textarea><br />
            <input type="submit" value="Poster mon commentaire" name="submit_comment">
        </form>
        <?php 
            if(isset($c_erreur))
            {
                echo $c_erreur;
            }
        ?>
</div>
<?php
}
?>

<h2>Commentaires</h2>

<?php
$req->closeCursor(); // Important : on libère le curseur pour la prochaine requête

// Récupération des commentaires
$req = $bdd->prepare('SELECT auteur, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%imin%ss\') AS date_commentaire_fr FROM commentaires WHERE id_billet = ? ORDER BY date_commentaire');
$req->execute(array($_GET['billet']));

while ($donnees = $req->fetch())
{
?>
<p><strong><?php echo htmlspecialchars($donnees['auteur']); ?></strong> le <?php echo $donnees['date_commentaire_fr']; ?></p>
<p><?php echo nl2br(htmlspecialchars($donnees['commentaire'])); ?></p>
<?php
} // Fin de la boucle des commentaires
$req->closeCursor();
?>
</body>
</html>