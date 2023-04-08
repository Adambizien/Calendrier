<?php
    //connexion bdd   
    if (isset($_POST['valider'])){
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mail = $_POST['mail'];
        $mdp = $_POST['mdp'];
        $dsn = "mysql:host=localhost;dbname=calendar";
        $connexion = new PDO($dsn, "root", "");
        $requete = "SELECT * FROM user WHERE mail = '$mail' ";
        $requete1 = "SELECT * FROM user WHERE nom = '$nom' ";
        $req=$connexion->prepare($requete);
        $res=$connexion->prepare($requete1);
        $req->execute();
        $res->execute();
    if (($req->rowCount() == 0)&&($res->rowCount() == 0)){
        $requete1 = "INSERT INTO user VALUES ('$nom','$prenom','$mail','$mdp') ";
        $req=$connexion->prepare($requete1);
        $req->execute();
        header("location: useracceuil.php?inscrit=$nom&prenom=$prenom");
    }elseif (($req->rowCount() == 0)&&($res->rowCount() != 0)){
        header('location: inscription.php?n=10');
    }else{
        header('location: inscription.php?m=10');
    }
    unset($connexion);
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/projet.css" />
    <title>inscription</title>
</head>    
<body>
    <h1>Inscription : </h1>
    <!-- formulaire d'incription-->
    <form id="inscription" action="inscription.php" method="post">
        <div>
            <br />
            <input type="text" name="nom" placeholder="Nom" required>
            <br /><br>
            <input type="text" name="prenom" placeholder="Prenom" required>
            <br /><br>
            <input type="email" name="mail" placeholder="Adresse mail" required>
            <br /><br>
            <input type="password" name="mdp" placeholder="Mot De Passe" required>
            <br /><br>
            <input type="submit" name="valider" value="valider">
            <br />
        </div>
    </form>
    <?php 
        if (isset($_GET['m'])) {
            echo'<div id="error"><strong>cette adresse mail existe deja !</strong></div>';
        }elseif (isset($_GET['n'])) {
            echo'<div id="error"><strong>ce nom existe deja !</strong></div>';
        }
    ?>
</body>
</html>