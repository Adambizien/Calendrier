<?php
  //connexion bdd
  $dsn = "mysql:host=localhost;dbname=calendar";
  $connexion = new PDO($dsn, "root", "");
  if (isset($_GET['mail'])) {
    $mail=$_GET['mail'];
    $title=$_GET['title'];
    $requete = "SELECT * FROM event WHERE mailuser = '$mail' AND title='$title'";
    $res = $connexion->query($requete);
    while ($ligne = $res->fetch()) {
      $date=$ligne['dd'];
      $start=$ligne['hd'];
      $end=$ligne['ha'];
    }
    $requete = "SELECT * FROM user WHERE mail = '$mail'";
    $res = $connexion->query($requete);
    while ($ligne = $res->fetch()) {
      $nom=$ligne['nom'];
      $prenom=$ligne['prenom'];
    }
    //modificateur des événements:
    if (isset($_POST['modifier'])) {
      $m_title=$_POST['title'];
      $m_date=$_POST['date'];
      $m_start=$_POST['start'];
      $m_end=$_POST['end'];
      $requete = "UPDATE event SET title='$m_title', dd='$m_date', hd='$m_start', ha='$m_end' WHERE mailuser = '$mail' AND title='$title'";
      $retour = $connexion->exec($requete);
      $title=$m_title;
      $date=$m_date;
      $start=$m_start;
      $end=$m_end;
    }
  }
  //modificateur de profil:
  if (isset($_GET['user'])) {
    $mail=$_GET['user'];
    $requete = "SELECT * FROM user WHERE mail = '$mail'";
    $res = $connexion->query($requete);
    while ($ligne = $res->fetch()) {
      $nom=$ligne['nom'];
      $prenom=$ligne['prenom'];
      $mail=$ligne['mail'];
      $mdp=$ligne['mdp'];
    }
    if (isset($_POST['valider'])) {
      $mdp1=$_POST['mdp1'];
      $mdp2=$_POST['mdp2'];
      $mdp=$_POST['mdp'];
      $requete = "SELECT * FROM user WHERE mail = '$mail'";
      $res = $connexion->query($requete);
      while ($etu = $res->fetch()) {
        $mdp3=$etu['mdp'];
        if (($mdp1 == $mdp2) && ($mdp2 == $mdp3)) {
          $requete = "UPDATE user SET mdp='$mdp' WHERE mail = '$mail' ";
          $retour = $connexion->exec($requete);
        }else{
          header("location: modifier.php?user=$mail&m=10");
        }
      }
    }
  }
  unset($connexion);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" type="text/css" href="../CSS/projet.css" />
  <script src = "../JS/projet.js"></script>
  <title>CALENDRIER</title>
</head>
<body>
  <div class="conteneur">
    <!--menu navigation-->
    <div class="m"><h2><a href="Inscription.php">Inscription</a></h2></div><div class="m"><h2><a href="acceuil.php">Se Deconnecter</a></h2></div><div class="m"><h2><a href="useracceuil.php?inscrit=<?php echo$nom; ?>&prenom=<?php echo$prenom; ?>">Retour à l'acceuil</a></h2></div>
  </div>
  <!--si il y a user dans le lien on affiche l'interface de modification de profil sinon
      on affiche l'interface de modification des événements
  -->
  <?php if (isset($_GET['user'])) {  ?>
    <br> <br> <br>
  <div id="block_today">
  <?php
    // infos perso:
    echo '<h1>'.$nom.' '.$prenom.'</h1> ';
    echo"<h2>Adresse mail : ".$mail."</h2>";
  ?>
  </div>
  <?php
    if (isset($_GET['m'])) {
      echo'<br><br><div id="error"><strong>veuillez saisir un mot de passe correct !</strong></div>';
    }
  ?>
  <br> <br> <br>
  <!--modification du mot de passe dans le profil:-->
  <form id="block_today" action="modifier.php?user=<?php echo $mail; ?>" method="post">
    <h2>changer de mot de passe</h2>
    <br />
    <input type="password" name="mdp1"  placeholder=" Ancien mot de passe " required>
    <br /><br>
    <input type="password" name="mdp2" placeholder=" Ancien mot de passe " required>
    <br /><br>
    <input type="password" name="mdp" placeholder=" Nouveau mot de passe " required>
    <br /><br>
    <input type="submit" name="valider" value="valider">
    <br />
  </form>
  <?php }else{ ?>
  <!--modificateur des événements-->
  <br> <br> <br>
  <div id="block_today">
  <?php
    echo '<h1>'.$title.'</h1> ';
    $dd = date_create($date);
    $mois = ($dd->format('m'));
    $annee = ($dd->format('Y'));
    $jour = ($dd->format('d'));
    echo"<h2>LE ".$jour."/". $mois."/". $annee."</h2>";
    $hd = date_create($start);
    $d= date_format($hd, 'H');
    $md= date_format($hd, 'm');
    $ha = date_create($end);
    $a= date_format($ha, 'H');
    $ma= date_format($ha, 'm');
    echo '<h2>DE '.$d.":".$md." A ".$a.":".$ma.'</h2>';
  ?>
  </div>
  <br> <br> <br>
  <div id="month_event" >
  </div>
  <br> <br> <br>
  <form id="block_today" action="modifier.php?mail=<?php echo $mail; ?>&title=<?php echo $title; ?>" method="post">
    <h2>Modifier l'evenement</h2>       
    Titre:<input type="text" name="title" required>
    <br> <br>
    Le:<input type="date" name="date"   required> <br> <br>
    De <input type="time" name="start" required> A <input type="time" name="end" required>
    <br> <br>
    <input type="submit" name="modifier" value="modifier">
    <br>
  </form>
  <script>
    jour_restant(<?php echo $annee; ?>,<?php echo $mois; ?>,<?php echo $jour; ?>,<?php echo $d; ?>,<?php echo $md; ?>);
  </script>
  <?php } ?>
</body>
</html>    