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

if(isset($_POST['formpasschange']))
{
	$usernamepasschange = htmlspecialchars($_POST['usernamepasschange']);
	if(!empty($usernamepasschange))
	{
		$requsername = $bdd->prepare("SELECT * FROM users WHERE username = ?");
		$requsername->execute(array($usernamepasschange));
		$usernameexist = $requsername->rowCount();
		if($usernameexist == 1)
		{
			$userinfo = $requsername->fetch();
			$_SESSION['id'] = $userinfo['id'];
			$_SESSION['username'] = $userinfo['username'];
			header("Location: recuperation2.php?id=".$_SESSION['id']);
		}
		else
		{
			$erreur = "Mauvais nom d'utilisateur !";
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
					<form method="POST" action="">
						<label for="usernamepasschange">Saisissez votre nom d'utilisateur: </label><br />
						<input type="text" name="usernamepasschange" id="usernamepasschange" placeholder="Votre nom d'utilisateur..."><br />
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