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

if(isset($_POST['formconnexion']))
{
	$usernameconnect = htmlspecialchars($_POST['usernameconnect']);
	$passwordconnect = sha1($_POST['passwordconnect']);
	if(!empty($usernameconnect) AND !empty($passwordconnect))
	{
		$requsername = $bdd->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
		$requsername->execute(array($usernameconnect, $passwordconnect));
		$usernameexist = $requsername->rowCount();
		if($usernameexist == 1)
		{
			$userinfo = $requsername->fetch();
			$_SESSION['id'] = $userinfo['id'];
			$_SESSION['username'] = $userinfo['username'];
			header("Location: partenaires.php?id=".$_SESSION['id']);
		}
		else
		{
			$erreur = "Mauvais nom d'utilisateur ou mot de passe !";
		}
	}
	else
	{
		$erreur = "Tous les champs doivent-être complétés !";
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>GBAF | Connexion</title>
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
				<!-- <div align="center"> -->
					<div id="form">
						<h3>Connexion</h3>
						<form method="POST" action="">
							<div>
							<label for="usernameconnect">Nom d'utilisateur: </label><br />
							<input type="text" name="usernameconnect" id="usernameconnect" placeholder="Votre nom d'utilisateur...">
							</div>
							<div>
							<label for="passwordconnect">Mot de passe: </label><br />
							<input type="password" name="passwordconnect" id="passwordconnect" placeholder="Votre mot de passe...">
							</div>
							<input type="submit" name="formconnexion" value="Se connecter">
						</form>
						<p>Pas encore inscrit ? <a class="subscribe" href="inscription.php">S'inscrire</a></p>
						<p>Mot de passe oublié ? <a class="create" href="recuperation1.php">Créer un nouveau mot de passe</a></p>
					</div>	
					<?php
					if (isset($erreur))
					{
						echo $erreur;
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