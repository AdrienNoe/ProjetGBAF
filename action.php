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

if(isset($_GET['t'],$_GET['id']) AND !empty($_GET['t']) AND !empty($_GET['id']))
{
   $getid = (int) $_GET['id'];
   $gett = (int) $_GET['t'];
   $sessionid = $_SESSION['id'];
   $checksessionid = $bdd->prepare('SELECT id FROM users WHERE id = ?');
   $checksessionid->execute(array($sessionid));
   $check = $bdd->prepare('SELECT id FROM partners WHERE id = ?');
   $check->execute(array($getid));
   if($check->rowCount() == 1)
   {
      if($gett == 1)
      {
         $check_like = $bdd->prepare('SELECT id FROM likes WHERE id_partner = ? AND id_user = ?');
         $check_like->execute(array($getid,$sessionid));
         $del = $bdd->prepare('DELETE FROM dislikes WHERE id_partner = ? AND id_user = ?');
         $del->execute(array($getid,$sessionid));
         if($check_like->rowCount() == 1)
         {
            $del = $bdd->prepare('DELETE FROM likes WHERE id_partner = ? AND id_user = ?');
            $del->execute(array($getid,$sessionid));
         }
         else
         {
            $ins = $bdd->prepare('INSERT INTO likes (id_partner, id_user) VALUES (?, ?)');
            $ins->execute(array($getid, $sessionid));
         }
         
      }
      elseif($gett == 2)
      {
         $check_like = $bdd->prepare('SELECT id FROM dislikes WHERE id_partner = ? AND id_user = ?');
         $check_like->execute(array($getid,$sessionid));
         $del = $bdd->prepare('DELETE FROM likes WHERE id_partner = ? AND id_user = ?');
         $del->execute(array($getid,$sessionid));
         if($check_like->rowCount() == 1)
         {
            $del = $bdd->prepare('DELETE FROM dislikes WHERE id_partner = ? AND id_user = ?');
            $del->execute(array($getid,$sessionid));
         }
         else
         {
            $ins = $bdd->prepare('INSERT INTO dislikes (id_partner, id_user) VALUES (?, ?)');
            $ins->execute(array($getid, $sessionid));
         }
      }
      header("Location: detailspartenaire1.php?id=".$_SESSION['id']."&id_partners=".$_GET['id']);
   }
   else
   {
      exit('Erreur. <a href="http://localhost/P3_test/connexion.php">Revenir à l\'accueil</a>');
   }
}
else
{
   exit('Erreur. <a href="http://localhost/P3_test/connexion.php">Revenir à l\'accueil</a>');
}
?>