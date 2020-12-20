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
	$requser = $bdd->prepare("SELECT * FROM users WHERE id = ?");
	$requser->execute(array($_SESSION['id']));
	$user = $requser->fetch();

	if(isset($_POST['newusername']) AND !empty($_POST['newusername']))
	{
		$newusername = htmlspecialchars($_POST['newusername']);
		$insertusername = $bdd->prepare("UPDATE users SET username = ? WHERE id = ?");
		$insertusername->execute(array($newusername, $_SESSION['id']));
		header('Location: profil.php?id='.$_SESSION['id']);
	}

	if(isset($_POST['newpassword']) AND !empty($_POST['newpassword']) AND isset($_POST['newpassword2']) AND !empty($_POST['newpassword2']))
	{
		$password = sha1($_POST['newpassword']);
		$password2 = sha1($_POST['newpassword2']);

		if($password == $password2)
		{
			$insertpassword = $bdd->prepare("UPDATE users SET password = ? WHERE id = ?");
			$insertpassword->execute(array($password, $_SESSION['id']));
			header('Location: profil.php?id='.$_SESSION['id']);
		}
		else
		{
			$msg = "Vos deux mots de passe ne correspondent pas !";
		}
	}

	if(isset($_POST['newsecret_question']) AND !empty($_POST['newsecret_question']))
	{
		$newsecret_question = htmlspecialchars($_POST['newsecret_question']);
		$insertsecret_question = $bdd->prepare("UPDATE users SET secret_question = ? WHERE id = ?");
		$insertsecret_question->execute(array($newsecret_question, $_SESSION['id']));
		header('Location: profil.php?id='.$_SESSION['id']);
	}

	if(isset($_POST['newsecret_answer']) AND !empty($_POST['newsecret_answer']))
	{
		$newsecret_answer = htmlspecialchars($_POST['newsecret_answer']);
		$insertsecret_answer = $bdd->prepare("UPDATE users SET secret_answer = ? WHERE id = ?");
		$insertsecret_answer->execute(array($newsecret_answer, $_SESSION['id']));
		header('Location: profil.php?id='.$_SESSION['id']);
	}

?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<title>GBAF | Paramètres du compte</title>
		<link rel="stylesheet" type="text/css" href="style/style.css">
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
				<div id="form">
					<h3>Edition du profil</h3>
					<form method="POST" action="#">
						<label for="newusername">Nouveau nom d'utilisateur: </label><br />
						<input type="text" name="newusername" id="newusername" placeholder="Votre nouveau nom d'utilisateur..."><br />
							
						<label for="newpassword">Nouveau mot de passe: </label><br />
						<input type="password" name="newpassword" id="newpassword" placeholder="Votre nouveau mot de passe..."><br />
							
						<label for="newpassword2">Confirmation du mot de passe: </label><br />
						<input type="password" name="newpassword2" id="newpassword2" placeholder="Confirmez votre nouveau mot de passe..."><br />
							
						<label for="newsecret_question">Nouvelle question secrète: </label><br />
						<input type="text" name="newsecret_question" id="newsecret_question" placeholder="Votre nouvelle question secrète..."><br />
							
						<label for="newsecret_answer">Nouvelle réponse à la question secrète: </label><br />
						<input type="text" name="newsecret_answer" id="newsecret_answer" placeholder="La réponse à votre nouvelle question secrète..."><br />
							
						<input type="submit" name="formconnexion" value="Mettre à jour">
					</form>
					<?php
						if(isset($msg))
						{
							echo $msg;
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
else
{
	header("Location: connexion.php");
}
?>