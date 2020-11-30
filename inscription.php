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
	$nom = htmlspecialchars($_POST['nom']);
	$prenom = htmlspecialchars($_POST['prenom']);
	$username = htmlspecialchars($_POST['username']);
	$password = sha1($_POST['password']);
	$password2 = sha1($_POST['password2']);
	$question_secrete = htmlspecialchars($_POST['question_secrete']);
	$reponse_secrete = htmlspecialchars($_POST['reponse_secrete']);

	if (!empty($_POST['nom']) AND !empty($_POST['prenom']) AND !empty($_POST['username']) AND !empty($_POST['password']) AND !empty($_POST['password2']) AND !empty($_POST['question_secrete']) AND !empty($_POST['reponse_secrete']))
	{
		$nomlength = strlen($nom);
		$prenomlength = strlen($prenom);
		$usernamelength = strlen($username);
		$question_secretelength = strlen($question_secrete);
		$reponse_secretelength = strlen($reponse_secrete);
		if($nomlength <= 10)
		{
			if($prenomlength <= 10)
			{
				if($usernamelength <= 10)
				{
					$requsername = $bdd->prepare("SELECT * FROM utilisateurs WHERE username = ?");
					$requsername->execute(array($username));
					$usernameexist = $requsername->rowCount();
					if ($usernameexist == 0) 
					{
						if($password == $password2)
						{
							if($question_secretelength <= 20)
							{
								if($reponse_secretelength <= 20)
								{
									$insertutl = $bdd->prepare("INSERT INTO utilisateurs(nom, prenom, username, password, question_secrete, reponse_secrete) VALUES (?, ?, ?, ?, ?, ?)"); /* insertutl pour insert utilisateur */
									$insertutl->execute(array($nom, $prenom, $username, $password, $question_secrete, $reponse_secrete));
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
<html>
	<head>
		<meta charset="utf-8">
		<title>GBAF | Inscription</title>
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
						<h3>INSCRIPTION</h3>
						<form method="POST" action="">
							<p><label for="nom">Nom: </label><br />
								<input type="text" name="nom" id="nom" placeholder="Votre nom...">
							</p>
							<p><label for="prenom">Prénom: </label><br />
								<input type="text" name="prenom" id="prenom" placeholder="Votre prénom...">
							</p>
							<p><label for="username">Nom d'utilisateur: </label><br />
								<input type="text" name="username" id="username" placeholder="Votre nom d'utilisateur...">
							</p>
							<p><label for="password">Mot de passe: </label><br />
								<input type="password" name="password" id="password" placeholder="Votre mot de passe...">
							</p>
							<p><label for="password2">Confirmation du mot de passe: </label><br />
								<input type="password" name="password2" id="password2" placeholder="Confirmez votre mot de passe...">
							</p>
							<p><label for="question_secrete">Question secrète: </label><br />
								<input type="text" name="question_secrete" id="question_secrete" placeholder="Votre question secrète...">
							</p>
							<p><label for="reponse_secrete">Réponse à la question secrète: </label><br />
								<input type="text" name="reponse_secrete" id="reponse_secrete" placeholder="La réponse à votre question secrète...">
							</p>
							<input type="submit" name="forminscription" value="Envoyer">
						</form>
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