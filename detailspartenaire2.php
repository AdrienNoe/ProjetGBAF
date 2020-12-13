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
        if(isset($_POST['pseudo'], $_POST['commentaire']) AND !empty($_POST['pseudo']) AND !empty($_POST['commentaire']))
        {
            $pseudo = htmlspecialchars($_POST['pseudo']);
            $commentaire = htmlspecialchars($_POST['commentaire']);
           	
           	$ins=$bdd->prepare('INSERT INTO comments (firstname, comment, id_partner, comment_date) VALUES (?,?,?,NOW())');
            $ins->execute(array($pseudo, $commentaire, $getid));
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
		<title>GBAF | Protectpeople</title>
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
				<img src="images/protectpeople.png">
				<h2>Protectpeople</h2>
				<div class="details">
					<p>Protectpeople finance la solidarité nationale.
					Nous appliquons le principe édifié par la Sécurité sociale française en 1945 ; permettre à
					chacun de bénéficier d'une protection sociale.</p>
					<p>Chez Protecpeople, chacun cotise selon ses moyens et reçoit selon ses besoins.
					Protectpeople est ouvert à tous, sans considération d'âge ou d'état de santé.<br />
					Noius garantissons un accès aux soins et une retraite.
					Chaque année, nous collections et répartissons 300 milliards d'euros.
					Notre mission est double :</p>
					<div class="list">
						<ul>
							<li>sociale : nous garantissons la fiabilité des données sociales ;</li>
							<li>économique : nous apportons une contribution aux activités économiques.</li>
						</ul>
					</div>
				</div>
			</div>

			<div id="comments_section">
				<div class="rating">
					<div class="title_comments">
		    			<h2>Poster un commentaire</h2>
		    		</div>
		    		<div class="likes_dislikes">
				    	<a href="action.php?t=1&id=<?= $id ?>">J'aime</a> (<?= $likes ?>)
		   				<br />
		   				<a href="action.php?t=2&id=<?= $id ?>">Je n'aime pas</a> (<?= $dislikes ?>)
		   			</div>
		   			<div class="comments_form">
				        <form method="POST" action="">
				            <input type="text" name="pseudo" placeholder="Votre prénom"><br />
				            <textarea name="commentaire" placeholder="Votre commentaire"></textarea><br />
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