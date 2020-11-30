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

if(isset($_GET['id']) AND $_GET['id'] > 0)
{
	$getid = intval($_GET['id']);
	$requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = ?");
	$requser->execute(array($getid));
	$userinfo = $requser->fetch();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>GBAF | Profil</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="icon" type="image/png" href="images/logo_gbaf.png">
	</head>
	<body>
		<div id="bloc_page">
			<header>
					<a href="http://localhost/P3_test/connexion.php">
						<img id="logo" src="images/logo_gbaf.png" alt="logo de GBAF">
					</a>
					<h1>Le Groupement Banque-Assurance Français.</h1>
			</header>
			<section>
				<!-- <div align="center"> -->
					<div id="form">
						<h3>Profil de <?php echo $userinfo['prenom']?> <?php echo $userinfo['nom']?></h3>
						<strong>Nom</strong> : <?php echo $userinfo['nom']?>
						<br />
						<strong>Prénom</strong> : <?php echo $userinfo['prenom']?>
						<br />
						<strong>Nom d'utilisateur</strong> : <?php echo $userinfo['username']?>
						<br />
					</div>	
					<?php
					if (isset($_SESSION['id']) AND ($userinfo['id'] == $_SESSION['id']))
					{
					?>
					<div class="edit">
						<a href="editionprofil.php">Éditer mon profil</a>
						<a href="deconnexion.php">Se déconnecter</a>
					</div>
					<?php
					}
					?>
				<!-- </div> -->
			</section>
			<footer>
				<p>| <a href="#">Mentions légales</a> | <a href="#">Contact</a> |</p>
			</footer>
		</div>
	</body>
</html>
<?php
}
?>