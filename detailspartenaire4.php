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

if(isset($_GET['id_partners']) AND !empty($_GET['id_partners']))
{
   $get_id = htmlspecialchars($_GET['id_partners']);
   $partner = $bdd->prepare('SELECT * FROM partners WHERE id = ?');
   $partner->execute(array($get_id));
   if($partner->rowCount() == 1)
   {
      $partner = $partner->fetch();
      $id = $partner['id'];
      $name = $partner['name'];
      $likes = $bdd->prepare('SELECT id FROM likes WHERE id_partner = ?'); //prepare protège les injections SQL
      $likes->execute(array($id));
      $likes = $likes->rowCount();
      $dislikes = $bdd->prepare('SELECT id FROM dislikes WHERE id_partner = ?');
      $dislikes->execute(array($id));
      $dislikes = $dislikes->rowCount();
   } else
   {
      die('Cet article n\'existe pas !');
   }
}
else
{
   die('Erreur.');
}

if(isset($_GET['id_partners']) AND !empty($_GET['id_partners']))
{
    $getid = htmlspecialchars($_GET['id_partners']);
    $partner = $bdd->prepare('SELECT * FROM partners WHERE id = ?');
    $partner->execute(array($getid));
    $partner = $partner->fetch();

    if(isset($_POST['submit_comment']))
    {
        if(isset($_POST['firstname'], $_POST['comment']) AND !empty($_POST['firstname']) AND !empty($_POST['comment']))
        {
            $firstname = htmlspecialchars($_POST['firstname']);
            $comment = htmlspecialchars($_POST['comment']);
            $check_comment = $bdd->prepare('SELECT id FROM comments WHERE id_partner = ? AND firstname = ?');
            $check_comment->execute(array($getid,$firstname));
            $delete = $bdd->prepare('DELETE FROM comments WHERE id_partner = ? AND firstname = ?');
            $delete->execute(array($getid,$firstname));
            $commentexist = $check_comment->rowCount();

            if($commentexist == 1)
            {
                $delete = $bdd->prepare('DELETE FROM comments WHERE id_partner = ? AND firstname = ?');
                $delete->execute(array($getid,$firstname));
                $ins=$bdd->prepare('INSERT INTO comments (firstname, comment, id_partner, comment_date) VALUES (?,?,?,NOW())');
                $ins->execute(array($firstname, $comment, $getid));
                $comment_message = "Votre commentaire a bien été posté !";
            }
            else
            {
                $ins=$bdd->prepare('INSERT INTO comments (firstname, comment, id_partner, comment_date) VALUES (?,?,?,NOW())');
                $ins->execute(array($firstname, $comment, $getid));
                $comment_message = "Votre commentaire a bien été posté !";
            }
        }
        else
        {
            $comment_message = "Tous les champs doivent-être complétés !";
        }
    }

    $comments = $bdd->prepare('SELECT * FROM comments WHERE id_partner = ? ORDER BY id DESC');
    $comments->execute(array($getid));

if(isset($_GET['id']) AND $_GET['id'] > 0)
{
	$getid = intval($_GET['id']);
	$requser = $bdd->prepare("SELECT * FROM users WHERE id = ?");
	$requser->execute(array($getid));
	$userinfo = $requser->fetch();

?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<title>GBAF | CDE</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="style/style_detailspartenaire.css">
		<link rel="stylesheet" media="screen and (max-width: 812px)" type="text/css" href="style/smartphone_details.css">
		<link rel="stylesheet" media="screen and (min-width: 813px) and (max-width: 1366px)" type="text/css" href="style/tablette_details.css">
		<link rel="icon" type="image/png" href="images/logo_gbaf.png">
	</head>
	<body>
		<div id="bloc_page">
		<header>
			<a href="connexion.php">
				<img id="logo" src="images/logo_gbaf.png" alt="logo de GBAF">
			</a>
			<?php
			if(isset($_SESSION['id']) AND ($userinfo['id'] == $_SESSION['id']))
			{
			?>
				<div class="edit">
					<p><a href="profil.php?id=<?php echo $_SESSION['id'];?>"><?php echo$userinfo['firstname']?> <?php echo$userinfo['name']?></a></p>
					<p><a href="deconnexion.php"><input type="button" name="disconnect" value="Se déconnecter"></a></p>
				</div>
			<?php
			}
			?>
		</header>
		<section>
			<div id="partner_section">
				<img src="images/CDE.png" alt="logo CDE">
				<h2>DSA France</h2>
				<div class="details">
					<p>La CDE (Chambre des Entrepreneurs) accompagne les entreprises dans leurs démarches de formation.<br />
					Son président est élu pour 3 ans par ses pairs, chefs d'enteprises et présidents des CDE.</p>
				</div>
			</div>

			<div id="comments_section">
				<div class="title_comments">
		    		<h2>Poster un commentaire</h2>
		    	</div>
		    	<div class="rating">
		   			<div class="comments_form">
				        <form method="POST" action="#">
				            <input type="text" name="firstname" placeholder="Votre prénom"><br />
				            <textarea class="comment_area" name="comment" placeholder="Votre commentaire"></textarea><br />
				            <input type="submit" value="Poster mon commentaire" name="submit_comment">
				        </form>
				    </div>
				    <div class="likes_dislikes">
				    	<a href="avis.php?t=1&id=<?= $id ?>">J'aime (<?= $likes ?>)</a><br />
		   				<a href="avis.php?t=2&id=<?= $id ?>">Je n'aime pas (<?= $dislikes ?>)</a>
		   			</div>
				</div>
		        <?php 
		            if(isset($comment_message))
		            {
		                echo $comment_message;
		            }
		        ?>
		        <?php
		            while($comment = $comments->fetch())
		            {
		                ?>
		                <div class="comments_list">
		                    <p class="firstname"><b><?= $comment['firstname'] ?></b></p>
		                    <p class="comment_date">Posté le : <?= $comment['comment_date'] ?></p>
		                    <p class="comment"><?= $comment['comment']; ?></p>
		                </div>
		                <?php
		            }
		        ?>
			</div>
		</section>
		<footer>
			<p>| <a href="#">Mentions légales</a> | <a href="#">Contact</a> |</p>
		</footer>
		</div>
	</body>
</html>
<?php
}
}
?>