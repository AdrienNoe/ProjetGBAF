<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=gbaf;charset=utf8', 'root', '');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

if(isset($_POST['forminscription']))
{
	$name = htmlspecialchars($_POST['name']);
	$firstname = htmlspecialchars($_POST['firstname']);
	$username = htmlspecialchars($_POST['username']);
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	$secret_question = htmlspecialchars($_POST['secret_question']);
	$secret_answer = htmlspecialchars($_POST['secret_answer']);

	if (!empty($_POST['name']) AND !empty($_POST['firstname']) AND !empty($_POST['username']) AND !empty($_POST['password']) AND !empty($_POST['password2']) AND !empty($_POST['secret_question']) AND !empty($_POST['secret_answer']))
	{
		$namelength = strlen($name);
		$firstnamelength = strlen($firstname);
		$usernamelength = strlen($username);
		$secret_questionlength = strlen($secret_question);
		$secret_answerlength = strlen($secret_answer);
		if($namelength <= 10)
		{
			if($firstnamelength <= 10)
			{
				if($usernamelength <= 10)
				{
					$requsername = $bdd->prepare("SELECT * FROM users WHERE username = ?");
					$requsername->execute(array($username));
					$usernameexist = $requsername->rowCount();
					if ($usernameexist == 0) 
					{
						if($_POST['password'] == $_POST['password2'])
						{
							$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
							if($secret_questionlength <= 20)
							{
								if($secret_answerlength <= 20)
								{
									$insertutl = $bdd->prepare("INSERT INTO users(name, firstname, username, password, secret_question, secret_answer) VALUES (?, ?, ?, ?, ?, ?)"); /* insertutl pour insert utilisateur */
									$insertutl->execute(array($name, $firstname, $username, $hashed_password, $secret_question, $secret_answer));
									$erreur = "Votre compte a bien été créé !";
									header("Location: connexion.php");
								}
								else
								{
									$erreur = "Votre réponse secrète ne doit pas dépasser 20 caractères !";
								}
							}
							else
							{
								$erreur = "Votre question secrète ne doit pas dépasser 20 caractères !";
							}
						}
						else
						{
							$erreur = "Vos mots de passe ne correspondent pas !";
						}
					}
					else
					{
						$erreur = "Nom d'utilisateur déjà utilisé !";
					}
				}
				else
				{
					$erreur = "Votre nom d'utilisateur ne doit pas dépasser 10 caractères !";
				}
			}
			else
			{
				$erreur = "Votre prénom ne doit pas dépasser 10 caractères !";
			}
		}
		else
		{
			$erreur = "Votre nom ne doit pas dépasser 10 caractères !";
		}
	}
	else
	{
		$erreur = "Tous les champs doivent-être complétés !";
	}
}

?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<title>GBAF | Inscription</title>
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
					<h3>Inscription</h3>
					<form method="POST" action="#">
						<label for="name">Nom: </label><br />
						<input type="text" name="name" id="name" placeholder="Votre nom..."><br />

						<label for="firstname">Prénom: </label><br />
						<input type="text" name="firstname" id="firstname" placeholder="Votre prénom..."><br />

						<label for="username">Nom d'utilisateur: </label><br />
						<input type="text" name="username" id="username" placeholder="Votre nom d'utilisateur..."><br />

						<label for="password">Mot de passe: </label><br />
						<input type="password" name="password" id="password" placeholder="Votre mot de passe..."><br />

						<label for="password2">Confirmation du mot de passe: </label><br />
						<input type="password" name="password2" id="password2" placeholder="Confirmez votre mot de passe..."><br />

						<label for="secret_question">Question secrète: </label><br />
						<input type="text" name="secret_question" id="secret_question" placeholder="Votre question secrète..."><br />

						<label for="secret_answer">Réponse à la question secrète: </label><br />
						<input type="text" name="secret_answer" id="secret_answer" placeholder="La réponse à votre question secrète..."><br />

						<input type="submit" name="forminscription" value="Envoyer">
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