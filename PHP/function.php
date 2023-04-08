<?php
     //afficher les événements : 
     function affiche_event($mail,$annee,$mois){
          //$connexion = mysqli_connect("localhost", "root", "","calendar");
          //echo mysqli_connect_error();    // affiche un message d'erreur
          $dsn = "mysql:host=localhost;dbname=calendar";
          $connexion = new PDO($dsn, "root", "");
          $requete = "SELECT * FROM event WHERE mailuser = '$mail' ";
          $res = $connexion->query($requete);
          while ($ligne = $res->fetch()) {
               $d = $ligne['dd'];
               $dd = date_create($d);
               $mois_cal = ($dd->format('m'));
               $annee_cal = ($dd->format('Y'));
               if (($mois_cal==$mois)&&($annee_cal==$annee)) {
                    $hd = date_create($ligne['hd']);
                    $d= date_format($hd, 'H');
                    $md= date_format($hd, 'i');
                    $ha = date_create($ligne['ha']);
                    $a= date_format($ha, 'H');
                    $ma= date_format($ha, 'i');
                    $title=$ligne['title'];
                    //un lien qui envoi dans le modificateur d'événements.
                    echo '<a href="modifier.php?mail='.$mail.'&title='.$title.'">';
                    $jour = ($dd->format('d'));
                    echo "<li><strong>".$ligne['title']." le ".$jour."/". $mois."/". $annee." de ".$d.":".$md." a ".$a.":".$ma.'</strong></li></a>';
               }
          }
          //mysqli_close($connexion);
          unset($connexion);
     }
     //les evenements d'aujourd'hui: 
     function affiche_event_today($mail,$date){
          $dd = date_create($date);
          $mois = ($dd->format('m'));
          $annee = ($dd->format('Y'));
          $jour = ($dd->format('d'));
          echo"<h2>les evenement du ".$jour."/". $mois."/". $annee."</h2> \n <ul>";
          $dsn = "mysql:host=localhost;dbname=calendar";
          $connexion = new PDO($dsn, "root", "");
          $requete = "SELECT * FROM event WHERE dd = '$date' ";
          $res = $connexion->query($requete);
          while ($ligne = $res->fetch()) {
               $m = $ligne['mailuser'];
               if ($m == $mail) {
                    $hd = date_create($ligne['hd']);
                    $d= date_format($hd, 'H');
                    $md= date_format($hd, 'i');
                    $ha = date_create($ligne['ha']);
                    $a= date_format($ha, 'H');
                    $ma= date_format($ha, 'i');
                    echo '<a href="modifier.php?mail='.$mail.'&title='.$ligne['title'].'">';
                    echo "<li><strong>".$ligne['title']." le ".$jour."/". $mois."/". $annee." de ".$d.":".$md." a ".$a.":".$ma."</strong></li></a>";
               }
          }
          unset($connexion);
     }
?>