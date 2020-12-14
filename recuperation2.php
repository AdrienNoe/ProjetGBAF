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
	$afficher = $bdd->prepare("SELECT * FROM users WHERE id = ?");
	$afficher->execute(array($getid));
	$resultat=$afficher->fetch();
}

if(isset($_POST['formpasschange']))
{
	$secretanswerpasschange = htmlspecialchars($_POST['secretanswerpasschange']);
	if(!empty($secretanswerpasschange))
	{
		$reqsecretanswer = $bdd->prepare("SELECT * FROM users WHERE secret_answer = ?");
		$reqsecretanswer->execute(array($secretanswerpasschange));
		$secretanswerexist = $reqsecretanswer->rowCount();
		if($secretanswerexist == 1)
		{
			$userinfo = $reqsecretanswer->fetch();
			$_SESSION['id'] = $userinfo['id'];
			$_SESSION['secret_answer'] = $userinfo['secret_answer'];
			header("Location: recuperation3.php?id=".$_SESSION['id']);
		}
		else
		{
			$erreur = "Mauvaise réponse secrète !";
		}
	}
	else
	{
		$erreur = "Le champ doit-être complété !";
	}
}

?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>GBAF | Récupération MDP</title>
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel="icon" type="image/png" href="images/logo_gbaf.png">
	</head>
	<body>
		<div id="bloc_page">
			<header>
					<a href="connexion.php">
						<img id="logo" src="images/logo_gbaf.png" alt="logo de GBAF">
					</a>
					<h1>Le Groupement Banque-Assurance Français.</h1>
			</header>
			<section>
				<div id="form">
					<h3>Changez votre mot de passe</h3>
					<p><strong>Votre question secrète : </strong><br />
						<?php
						if (isset($_SESSION['id']) AND ($resultat['id'] == $_SESSION['id']))
						{
							echo $resultat['secret_question'];
						}
						?>
					 </p>
					<form method="POST" action="">
						<label for="secretanswerpasschange">Saisissez votre réponse secrète: </label><br />
						<input type="text" name="secretanswerpasschange" id="secretanswerpasschange" placeholder="Votre réponse secrète..."><br />
						<input type="submit" name="formpasschange" value="Envoyer">
					</form>
				</div>
				<?php
				if (isset($erreur))
				{
					echo $erreur;
				}
				?>
			</section>
			<footer>
				<p>| <a href="#">Mentions légales</a> | <a href="#">Contact</a> |</p>
			</footer>
		</div>
	</body>
</html>