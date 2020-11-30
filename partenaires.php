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
		<title>GBAF | Présentation des acteurs</title>
		<title>GBAF | Connexion</title>
		<link rel="stylesheet" type="text/css" href="style_partenaires.css">
		<link rel="icon" type="image/png" href="images/logo_gbaf.png">
	</head>
	<body>
		<div id="bloc_page">
		<header>
			<a href="">
				<img id="logo" src="images/logo_gbaf.png" alt="logo de GBAF">
			</a>
			<?php
			if (isset($_SESSION['id']) AND ($userinfo['id'] == $_SESSION['id']))
			{
			?>
				<div class="edit">
					<ul>
						<li><a href="profil.php?id=<?php echo $_SESSION['id'];?>"><?php echo$userinfo['prenom']?> <?php echo$userinfo['nom']?></a></li>
						<li><a href="deconnexion.php"><input type="button" name="disconnect" value="Se déconnecter"></a></li>
					</ul>
				</div>
			<?php
			}
			?>
		</header>
		<section>
			<div id="section_presentation">
				<h1>Présentation du Groupement Banque Assurance Français (GBAF)</h1>
				<p><strong>Le Groupement Banque Assurance Français</strong> (GBAF) est une fédération représentant les 6 grands groupes français :</p>
				<div class="liste">
					<ul>
						<li>BNP Paribas</li>
						<li>BPCE</li>
						<li>Crédit Agricole</li>
					</ul>
					<ul>
						<li>Crédit Mutuel-CIC</li>
						<li>Société Générale</li>
						<li>La Banque Postale</li>
					</ul>
				</div>
				<div class="presentation">
					<p>Même s'il existe une forte concurrence entre ces entités, elles vont toutes travailler
					de la même façon pour gérer près de 80 millions de comptes sur le territoire national.
					Le GBAF est le représentant de la profession bancaire et des assureurs sur tous les axes
					de la réglementation financière française. Sa mision est de promouvoir l'activité bancaire à
					l'échelle nationale. C'est aussi un interlocuteur privilégié des pouvoirs publics.</p>
				</div>
				<img src="images/la_defense.jpg">
			</div>
			<div id="section_acteurs">
				<h2>Présentation des différents partenaires</h2>
				<p>texte acteurs et partenaires<br />
				...</p>
				<div class="liste_partenaires">
					<div class="premier_partenaire">
						<div class="logo">
							<img src="images/formation_co.png">
						</div>
						<div class="description">
							<h3>Formation&Co</h3>
							<p>Formation&co est une association française présente sur tout le territoire.</p>
						</div>
						<div class="suite">
							<a href=""><input type="button" value="Afficher la suite" name="Next"></input></a>
						</div>
					</div>
					<div class="deuxieme_partenaire">
						<img src="images/protectpeople.png">
						<h3>Protectpeople</h3>
						<p>Protectpeople finance la solidarité nationale.</p>
						<a href=""><input type="button" value="Afficher la suite" name="Next"></input></a>
					</div>
					<div class="troisieme_partenaire">
						<img src="images/Dsa_france.png">
						<h3>DSA France</h3>
						<p>Dsa France accélère la croissance du territoire et s’engage avec les collectivités territoriales.</p>
						<a href=""><input type="button" value="Afficher la suite" name="Next"></input></a>
					</div>
					<div class="quatrieme_partenaire">
						<img src="images/CDE.png">
						<h3>CDE</h3>
						<p>La CDE (Chambre Des Entrepreneurs) accompagne les entreprises dans leurs démarches de formation.</p>
						<a href=""><input type="button" value="Afficher la suite" name="Next"></input></a>
					</div>
				</div>	
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
?>