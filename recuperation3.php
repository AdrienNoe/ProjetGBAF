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
	$password1change = sha1($_POST['password1change']);
	$password2change = sha1($_POST['password2change']);
	if(!empty($_POST['password1change']) AND !empty($_POST['password2change']))
	{
		if($password1change == $password2change)
		{
			$insertnewpassword = $bdd->prepare("UPDATE users SET password = ? WHERE id = ?");
			$insertnewpassword->execute(array($password1change, $_SESSION['id']));
			echo "Votre mot de passe a bien été changé !";
			header('Refresh:5;url=connexion.php');
		}
		else
		{
			$erreur = "Vos deux mots de passe ne correspondent pas !";
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
						<label for="password1change">Votre nouveau mot de passe: </label><br />
						<input type="password" name="password1change" id="password1change" placeholder="Votre nouveau mot de passe..."><br />
						<label for="password2change">Confirmez votre nouveau mot de passe: </label><br />
						<input type="password" name="password2change" id="password2change" placeholder="Confirmez votre nouveau mot de passe..."><br />
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