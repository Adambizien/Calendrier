<?php
  //connexion bdd
  if (isset($_POST['mail'])) {
    $mail = $_POST['mail'];
    $mdp = $_POST['mdp'];
    $dsn = "mysql:host=localhost;dbname=calendar";
    $connexion = new PDO($dsn, "root", "");
    $requete = "SELECT * FROM user WHERE mail = '$mail' ";
    $req=$connexion->prepare($requete);
    $req->execute();
    if ($req->rowCount() != 0) {
      while ($etu = $req->fetch()) {
        $nom =$etu['nom'];
        $prenom =$etu['prenom'];
        $db_mdp=$etu['mdp'];
      }
    if ($db_mdp==$mdp) {
      header("location: useracceuil.php?inscrit=$nom&prenom=$prenom");
    }else{
      header('location: acceuil.php?md=10'); 
    }
  }else{
    header('location: acceuil.php?ma=10');
  }
  unset($connexion);
  }
  //pour aller dans le modificateur de profil:
  if (isset($_POST['profil'])) {
    $mail=$_GET['mail'];
    header("location: modifier.php?user=$mail");
  }
  $timezone = date_default_timezone_get();
  $datyear = date('Y', time());
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
  <!--menu de navigation:-->
  <form id="head" action="acceuil.php" method="post">
    <label>
      <strong>connexion :</strong> Identifiant <input type="email" name="mail" required>
      Mot de passe <input type="password" name="mdp" required>
      <input type="submit"/>
    </label>  
    vous n'avez pas de compte ? <a href="inscription.php">Inscrivez-vous</a>  
    <br />
  </form>
  <!--horloge:-->
  <p id="horloge"></p>
  <!-- alerte d'erreurs:-->
  <?php 
    if (isset($_GET['ma'])) {
      echo'<div id="error"><strong>mauvaise adresse email !</strong></div>';
    }else if (isset($_GET['md'])) {
      echo'<div id="error"><strong>mauvais mot de passe !</strong></div>';
    }else if (isset($_GET['int'])) {
      echo'<br><br><div id="error"><strong>veuillez saisir une annee existante !</strong></div>';
    }
  ?>
  <!-- calendrier interface:-->
  <strong><div class="date" id="date_auj"></div></strong>
  <form class="change" action="acceuil.php" method="post">
    choisir une nouvelle date: 
    </br></br>
    <select name="month">
      <option value="0">Janvier</option>
      <option value="1">Fevrier</option>
      <option value="2">Mars</option>
      <option value="3">Avril</option>
      <option value="4">Mai</option>
      <option value="5">Juin</option>
      <option value="6">Juillet</option>
      <option value="7">Aout</option>
      <option value="8">Septembre</option>
      <option value="9">Octobre</option>
      <option value="10">Novembre</option>
      <option value="11">Decembre</option>
    </select>
    <input type="number" name="year" value="<?php echo($datyear); ?>">
    <input type="submit"/>
  </form>
  </br></br>
  <script>
    //appelle de la fonction horloge:
    horloge();
    setInterval("horloge()", 1000);
    //appelle de la fonction calendier sans événement:
    <?php
      if (isset($_POST['year'])) {
        $m = $_POST['month'];
        $a = $_POST['year'];
    ?>

      other_calendrier(<?php echo $m; ?>,<?php echo $a; ?>);
    <?php
      }elseif (isset($_GET['p']) && isset($_GET['a'])) {
        $m = $_GET['p'];
	      $a = $_GET['a'];
	      $m++;
	      if ($m==12) {
          $m=0;
          $a++;
        } 
    ?>
        other_calendrier(<?php echo $m; ?>,<?php echo $a; ?>);
    <?php 
      }elseif (isset($_GET['m']) && isset($_GET['a'])) {
	      $m = $_GET['m'];
	      $a = $_GET['a'];
	      if ($m==0) {
          $m=11;
          $a--;
        }else{ 
          $m--; 
          } 
    ?>
        other_calendrier(<?php echo $m; ?>,<?php echo $a; ?>);
    <?php 
      }else{
    ?>
        other_calendrier(0,0);
    <?php 
      } 
    ?>
  </script>    
</body>
</html>    