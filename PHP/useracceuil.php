<?php 
  //recuperer les fonctions de function.php
  include "function.php";
  if (isset($_GET['inscrit'])) {
    $nom = $_GET['inscrit'];
    $prenom = $_GET['prenom'];
    $dsn = "mysql:host=localhost;dbname=calendar";
    $connexion = new PDO($dsn, "root", "");
    $requete = "SELECT mail FROM user WHERE nom = '$nom' ";
    $res = $connexion->query($requete);
    while ($etu = $res->fetch()) {
      $mail =$etu['mail'];
      break;
    }
    unset($connexion);
  }
  if (isset($_POST['ajouter'])) {
    $date = $_POST['date'];
    $heure_debut = $_POST['start'];
    $heure_fin = $_POST['end'];
    $title = $_POST['title'];
    $dsn = "mysql:host=localhost;dbname=calendar";
    $connexion = new PDO($dsn, "root", "");
    $sql = "INSERT INTO event (title, dd, hd, ha, mailuser)
    VALUES('$title', '$date', '$heure_debut', '$heure_fin', '$mail')";
    $reqprep = $connexion->prepare($sql);
    $reqprep->execute();
    unset($connexion);
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
<!--même chose que accuil avec les événements du client connecté -->
<body>  
  <form id="head" action="acceuil.php?mail=<?php echo $mail; ?>" method="post">
    Vous etes connecter en tant que <strong><?php echo $nom; ?> <?php echo $prenom; ?></strong>
    <input type="submit" name="profil" value="profil">
    <input type="submit" name="deconnexion" value="deconnexion">
  </form>
  <p id="horloge"></p>
  <strong><div class="date" id="date_auj"></div></strong> 
  <form class="change" action="useracceuil.php?inscrit=<?php echo $nom; ?>&prenom=<?php echo $prenom; ?>" method="post">
    choisir une nouvelle date: <br><br>
    <select name="month" required>
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
    <input type="number" name="year" value="<?php echo($datyear); ?>" required>
  </form>
  <br><br>
  <script>
    horloge();
    setInterval("horloge()", 1000);
    <?php 
      if (isset($_GET['day'])) {
        $dat_cal = date_create($_GET['day']);
        $m = ($dat_cal->format('m'));
        --$m;
        $a = ($dat_cal->format('Y'));
      }elseif (isset($_POST['year'])) {
        $m = $_POST['month'];
        $a = $_POST['year'];
      }elseif (isset($_GET['p']) && isset($_GET['a'])) {
        $m = $_GET['p'];
        $a = $_GET['a'];
        $m++;
        if ($m==12) {
          $m=0;
          $a++;
        }
      }elseif (isset($_GET['m']) && isset($_GET['a'])) {
        $m = $_GET['m'];
        $a = $_GET['a'];
        if ($m==0) {
          $m=11;
          $a--;
        }else{ 
          $m--; }
      }else{
        $timezone = date_default_timezone_get();
        $da = date('Y-m-d ', time());
        $dat_cal = date_create($da);
        $m = ($dat_cal->format('m'));
        --$m;
        $a = ($dat_cal->format('Y'));
      } 
      $dsn = "mysql:host=localhost;dbname=calendar";
      $connexion = new PDO($dsn, "root", "");
      $requete = "SELECT * FROM event WHERE mailuser = '$mail' ";
      $res = $connexion->query($requete);
      $x=0;
      while ($ligne = $res->fetch()) {
        $d = $ligne['dd'];
        $t = $ligne['title'];
        $dd = date_create($d);
        $mois_cal = ($dd->format('m'));
        $annee_cal = ($dd->format('Y'));
        $jour_cal = ($dd->format('d'));
        ++$m;
        if (($mois_cal==$m)&&($annee_cal==$a)) {
    ?>
          localStorage.setItem("<?php echo($jour_cal); ?>",<?php echo($x); ?>);
    <?php
          ++$x;
        }
        --$m;
      }
      unset($connexion);
      if (isset($_POST['year'])||isset($_GET['day'])||( (isset($_GET['p'])||isset($_GET['m'])) && isset($_GET['a'])) || isset($_GET['day']) ) {
    ?>
      event_calendrier(<?php echo $m; ?>,<?php echo $a; ?>,"<?php echo $nom; ?>","<?php echo $prenom; ?>");
    <?php
      }else{
    ?>
        event_calendrier(0,0,"<?php echo $nom; ?>","<?php echo $prenom; ?>");
    <?php 
      } 
    ?>
  </script> 
  <?php
    if (isset($_GET['int'])) {
      echo'<br><br><div id="error"><strong>veuillez saisir une annee existante !</strong></div>';
    }
  ?>
  <br> <br> <br>
  <div id="month_event">
    <h2>evenement du mois</h2>
    <ul>
      <?php
      affiche_event($mail,$a,++$m);
      ?>
    </ul>
  </div>
  <br> <br> <br>
  <div id="block_today">
    <?php 
      if (isset($_GET['day'])) {
        $d_cal=$_GET['day'];
        affiche_event_today($mail,$d_cal);
      }else{
        $timezone = date_default_timezone_get();
        $d_cal = date('Y-m-d ', time());
        affiche_event_today($mail,$d_cal);
      }
    ?>
    </ul>
  </div>
  <br> <br> <br>
  <form class="add_event" action="useracceuil.php?inscrit=<?php echo $nom; ?>&prenom=<?php echo $prenom; ?>" method="post">
    <h2>ajouter un evenement</h2>       
    Titre:<input type="text" name="title" required>
    <br> <br>
    Le:<input type="date" name="date"   required> <br> <br>
    De <input type="time" name="start" required> A <input type="time" name="end" required>
    <br> <br>
    <input type="submit" name="ajouter">
    <br>
  </form>
</body>
</html>    