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

if(isset($_GET['id_partners']) AND !empty($_GET['id_partners'])) {
   $get_id = htmlspecialchars($_GET['id_partners']);
   $article = $bdd->prepare('SELECT * FROM partners WHERE id = ?');
   $article->execute(array($get_id));
   if($article->rowCount() == 1) {
      $article = $article->fetch();
      $id = $article['id'];
      $name = $article['name'];
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
   die('Erreur.');
}

if(isset($_GET['id_partners']) AND !empty($_GET['id_partners']))
{
    $getid = htmlspecialchars($_GET['id_partners']);
    $billet = $bdd->prepare('SELECT * FROM partners WHERE id = ?');
    $billet->execute(array($getid));
    $billet = $billet->fetch();

    if(isset($_POST['submit_comment']))
    {
        if(isset($_POST['firstname'], $_POST['comment']) AND !empty($_POST['firstname']) AND !empty($_POST['comment']))
        {
            $firstname = htmlspecialchars($_POST['firstname']);
            $comment = htmlspecialchars($_POST['comment']);
            
            $ins=$bdd->prepare('INSERT INTO comments (firstname, comment, id_partner, comment_date) VALUES (?,?,?,NOW())');
            $ins->execute(array($firstname, $comment, $getid));
            $c_msg = "Votre commentaire a bien été posté !";
        }
        else
        {
            $c_msg = "Tous les champs doivent-être complétés !";
        }
    }

    $commentaires = $bdd->prepare('SELECT * FROM comments WHERE id_partner = ? ORDER BY id DESC');
    $commentaires->execute(array($getid));

if(isset($_GET['id']) AND $_GET['id'] > 0)
{
	$getid = intval($_GET['id']);
	$requser = $bdd->prepare("SELECT * FROM users WHERE id = ?");
	$requser->execute(array($getid));
	$userinfo = $requser->fetch();

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>GBAF | Formation&co</title>
		<link rel="stylesheet" type="text/css" href="style/style_detailspartenaire.css">
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
					<ul>
						<li><a href="profil.php?id=<?php echo $_SESSION['id'];?>"><?php echo$userinfo['firstname']?> <?php echo$userinfo['name']?></a></li>
						<li><a href="deconnexion.php"><input type="button" name="disconnect" value="Se déconnecter"></a></li>
					</ul>
				</div>
			<?php
			}
			?>
		</header>
		<section>
			<div id="partner_section">
				<img src="images/formation_co.png">
				<h2>Formation&co</h2>
				<div class="details">
					<p>Formation&co est une association française présente sur tout le territoire.
					Nous proposons à des personnes issues de tout milieu de devenir entrepreneur
					grâce à un crédit et un accompagnement professionnel et personnalisé.
					Notre proposition:</p>
					<div class="list">
						<ul>
							<li>un financement jusqu'à 30 000€ ;</li>
							<li>un suivi personnalisé et gratuit ;</li>
							<li>une lutte acharnée contre les freins sociétaux et les stéréotypes.</li>
						</ul>
					</div>
					<p>Le financement est possible, peu importe le métier : coiffeur, banquier, éleveur de
					chèvres... Nous collaborons avec des personnes talentueuses et motivées.
					Vous n'avez pas de diplômes ? Ce n'est pas un problème pour nous !
					Nos financements s'adressent à tous.</p>
				</div>
			</div>

			<div id="comments_section">
		    	<div class="rating">
		    		<div class="title_comments">
		    			<h2>Poster un commentaire</h2>
		    		</div>
			    	<div class="likes_dislikes">
				    	<a href="action.php?t=1&id=<?= $id ?>">J'aime (<?= $likes ?>)</a><br />
		   				<a href="action.php?t=2&id=<?= $id ?>">Je n'aime pas (<?= $dislikes ?>)</a>
		   			</div>
		   			<div class="comments_form">
				        <form method="POST" action="">
				            <input type="text" name="firstname" placeholder="Votre prénom"><br />
				            <textarea class="comment" name="comment" placeholder="Votre commentaire"></textarea><br />
				            <input type="submit" value="Poster mon commentaire" name="submit_comment">
				        </form>
				    </div>
				</div>
		        <?php 
		            if(isset($c_msg))
		            {
		                echo $c_msg;
		            }
		        ?>
		        <br />
		        <?php
		            while($c = $commentaires->fetch())
		            {
		                ?>
		                <div class="comments_list">
		                    <p class="auteur"><b><?= $c['firstname'] ?></b></p>
		                    <p class="date_com">Posté le : <?= $c['comment_date'] ?></p>
		                    <p class="commentaire"><?= $c['comment']; ?></p>
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