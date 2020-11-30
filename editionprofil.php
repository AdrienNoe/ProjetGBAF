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

if(isset($_SESSION['id']))
{
	$requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = ?");
	$requser->execute(array($_SESSION['id']));
	$user = $requser->fetch();

	if(isset($_POST['newusername']) AND !empty($_POST['newusername']))
	{
		$newusername = htmlspecialchars($_POST['newusername']);
		$insertusername = $bdd->prepare("UPDATE utilisateurs SET username = ? WHERE id = ?");
		$insertusername->execute(array($newusername, $_SESSION['id']));
		header('Location: profil.php?id='.$_SESSION['id']);
	}

	if(isset($_POST['newpassword']) AND !empty($_POST['newpassword']) AND isset($_POST['newpassword2']) AND !empty($_POST['newpassword2']))
	{
		$password = sha1($_POST['newpassword']);
		$password2 = sha1($_POST['newpassword2']);

		if($password == $password2)
		{
			$insertpassword = $bdd->prepare("UPDATE utilisateurs SET password = ? WHERE id = ?");
			$insertpassword->execute(array($password, $_SESSION['id']));
			header('Location: profil.php?id='.$_SESSION['id']);
		}
		else
		{
			$msg = "Vos deux mots de passe ne correspondent pas !";
		}
	}

	if(isset($_POST['newquestion_secrete']) AND !empty($_POST['newquestion_secrete']))
	{
		$newquestion_secrete = htmlspecialchars($_POST['newquestion_secrete']);
		$insertquestion_secrete = $bdd->prepare("UPDATE utilisateurs SET question_secrete = ? WHERE id = ?");
		$insertquestion_secrete->execute(array($newquestion_secrete, $_SESSION['id']));
		header('Location: profil.php?id='.$_SESSION['id']);
	}

	if(isset($_POST['newreponse_secrete']) AND !empty($_POST['newreponse_secrete']))
	{
		$newreponse_secrete = htmlspecialchars($_POST['newreponse_secrete']);
		$insertreponse_secrete = $bdd->prepare("UPDATE utilisateurs SET reponse_secrete = ? WHERE id = ?");
		$insertreponse_secrete->execute(array($newreponse_secrete, $_SESSION['id']));
		header('Location: profil.php?id='.$_SESSION['id']);
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>GBAF | Paramètres du compte</title>
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
						<h3>Edition du profil</h3>
						<form method="POST" action="">
							<p><label for="newusername">Nouveau nom d'utilisateur: </label><br />
								<input type="text" name="newusername" id="newusername" placeholder="Votre nouveau nom d'utilisateur...">
							</p>
							<p><label for="newpassword">Nouveau mot de passe: </label><br />
								<input type="password" name="newpassword" id="newpassword" placeholder="Votre nouveau mot de passe...">
							</p>
							<p><label for="newpassword2">Confirmation du mot de passe: </label><br />
								<input type="password" name="newpassword2" id="newpassword2" placeholder="Confirmez votre nouveau mot de passe...">
							</p>
							<p><label for="newquestion_secrete">Nouvelle question secrète: </label><br />
								<input type="text" name="newquestion_secrete" id="newquestion_secrete" placeholder="Votre nouvelle question secrète...">
							</p>
							<p><label for="newreponse_secrete">Nouvelle réponse à la question secrète: </label><br />
								<input type="text" name="newreponse_secrete" id="newreponse_secrete" placeholder="La réponse à votre nouvelle question secrète...">
							</p>
							<input type="submit" name="formconnexion" value="Mettre à jour">
						</form>
						<?php
							if(isset($msg))
							{
								echo $msg;
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
else
{
	header("Location: connexion.php");
}
?>