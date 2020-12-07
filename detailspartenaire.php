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
	$getidpartner = htmlspecialchars($_GET['id_partners']);
	$partners = $bdd->prepare('SELECT * FROM partners WHERE id_partners = ?');
	$partners->execute(array($getidpartner));
	$partners = $partners->fetch();

	if(isset($_POST['submit_comment']))
	{
		if(isset($_POST['firstname'], $_POST['comment']) AND !empty($_POST['firstname']) AND !empty($_POST['comment']))
		{
			$firstname = htmlspecialchars($_POST['firstname']);
			$comment = htmlspecialchars($_POST['comment']);

			$insert = $bdd->prepare('INSERT INTO comments (firstname, comment, id_partner, comment_date) VALUES (?,?,?,NOW())');
			$insert->execute(array($firstname, $comment, $getidpartner));
			$comment_message = "Votre commentaire a bien été posté !";
		}
		else
		{
			$comment_message = "Tous les champs doivent-être complétés !";
		}
	}


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
					<p>Formation&co est une association française présente sur tout le territoire.<br />
					Nous proposons à des personnes issues de tout milieu de devenir entrepreneur<br />
					grâce à un crédit et un accompagnement professionnel et personnalisé.<br />
					Notre proposition:</p>
					<div class="list">
						<ul>
							<li>un financement jusqu'à 30 000€ ;</li>
							<li>un suivi personnalisé et gratuit ;</li>
							<li>une lutte acharnée contre les freins sociétaux et les stéréotypes.</li>
						</ul>
					</div>
					<p>Le financement est possible, peu importe le métier : coiffeur, banquier, éleveur de<br />
					chèvres... Nous collaborons avec des personnes talentueuses et motivées.<br />
					Vous n'avez pas de diplômes ? Ce n'est pas un problème pour nous !<br />
					Nos financements s'adressent à tous.</p>
				</div>
			</div>
			<div id="comments_section">
				<h2>Commentaires</h2>
				<form method="POST" action="">
					<input type="text" name="firstname" placeholder="Votre prénom"><br />
					<textarea name="comment" placeholder="Votre commentaire"></textarea><br />
					<input type="submit" value="Poster mon commentaire" name="submit_comment">
				</form>
				<?php
					if(isset($comment_message))
					{
						echo $comment_message;
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