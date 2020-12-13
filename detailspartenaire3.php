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

if(isset($_GET['id_partners']) AND !empty($_GET['id_partners']))
{
    $getid = htmlspecialchars($_GET['id_partners']);
    $billet = $bdd->prepare('SELECT * FROM partners WHERE id = ?');
    $billet->execute(array($getid));
    $billet = $billet->fetch();

    if(isset($_POST['submit_comment']))
    {
        if(isset($_POST['pseudo'], $_POST['commentaire']) AND !empty($_POST['pseudo']) AND !empty($_POST['commentaire']))
        {
            $pseudo = htmlspecialchars($_POST['pseudo']);
            $commentaire = htmlspecialchars($_POST['commentaire']);
            if(strlen($pseudo) < 25)
            {
                $ins=$bdd->prepare('INSERT INTO comments (firstname, comment, id_partner, comment_date) VALUES (?,?,?,NOW())');
                $ins->execute(array($pseudo, $commentaire, $getid));
                $c_msg = "Votre commentaire a bien été posté !";
            }
            else
            {
                $c_msg = "Le pseudo doit faire moins de 25 caractères !";
            }
        }
        else
        {
            $c_msg = "Tous les champs doivent-être complétés !";
        }
    }

    $commentaires = $bdd->prepare('SELECT * FROM comments WHERE id_partner = ? ORDER BY id DESC');
    $commentaires->execute(array($getid));

if(isset($_GET['id']) AND $_GET['id'] > 0)
{
	$getid = intval($_GET['id']);
	$requser = $bdd->prepare("SELECT * FROM users WHERE id = ?");
	$requser->execute(array($getid));
	$userinfo = $requser->fetch();

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>GBAF | DSA France</title>
		<link rel="stylesheet" type="text/css" href="style/style_detailspartenaire.css">
		<link rel="icon" type="image/png" href="images/logo_gbaf.png">
	</head>
	<body>
		<div id="bloc_page">
		<header>
			<a href="connexion.php">
				<img id="logo" src="images/logo_gbaf.png" alt="logo de GBAF">
			</a>
			<?php
			if(isset($_SESSION['id']) AND ($userinfo['id'] == $_SESSION['id']))
			{
			?>
				<div class="edit">
					<ul>
						<li><a href="profil.php?id=<?php echo $_SESSION['id'];?>"><?php echo$userinfo['firstname']?> <?php echo$userinfo['name']?></a></li>
						<li><a href="deconnexion.php"><input type="button" name="disconnect" value="Se déconnecter"></a></li>
					</ul>
				</div>
			<?php
			}
			?>
		</header>
		<section>
			<div id="partner_section">
				<img src="images/Dsa_france.png">
				<h2>DSA France</h2>
				<div class="details">
					<p>DSA France accélère la croissance du territoire et s'engage avec les collectivités territoriales.<br />
					Nous accompagnons les entreprises dans les étapes clés de leur évolution.<br />
					Notre philosophie: s'adapter à chaque entreprise.<br />
					Nous les accompagnons pour voir plus grand et plus loin et proposons des solutions de<br />
					financement adaptées à chaque étape de la vie des entreprises.</p>
				</div>
			</div>

			<div id="comments_section">
		    <h2>Poster un commentaire</h2>
		         <form method="POST" action="">
		            <input type="text" name="pseudo" placeholder="Votre prénom"><br />
		            <textarea name="commentaire" placeholder="Votre commentaire"></textarea><br />
		            <input type="submit" value="Poster mon commentaire" name="submit_comment">
		        </form>
		        <?php 
		            if(isset($c_msg))
		            {
		                echo $c_msg;
		            }
		        ?>
		        <br />
		        <?php
		            while($c = $commentaires->fetch())
		            {
		                ?>
		                <div class="commentaires">
		                    <p class="auteur"><b><?= $c['firstname'] ?></b></p>
		                    <p class="date_com">Posté le : <?= $c['comment_date'] ?></p>
		                    <p class="commentaire"><?= $c['comment']; ?></p>
		                </div>
		                <?php
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
}
?>