//horloge 
function horloge(){
  var date = new Date();
  var h = date.getHours();
  var m = date.getMinutes();
  var s = date.getSeconds();
  if( h < 10 ){ h = '0' + h; }
  if( m < 10 ){ m = '0' + m; }
  if( s < 10 ){ s = '0' + s; }
  document.getElementById('horloge').innerHTML = "il est : " + h +":"+ m +":"+ s + " (heure d'hiver) <br />";
}
//les jours ferier :
function its_ferier(an,m,j){
  var G = an%19;
  var C = Math.floor(an/100);
  var H = (C - Math.floor(C/4) - Math.floor((8*C+13)/25) + 19*G + 15)%30;
  var I = H - Math.floor(H/28)*(1 - Math.floor(H/28)*Math.floor(29/(H + 1))*Math.floor((21 - G)/11));
  var J = (an*1 + Math.floor(an/4) + I + 2 - C + Math.floor(C/4))%7;
  var L = I - J;
  var MoisPaques = 3 + Math.floor((L + 40)/44);
  var JourPaques = L + 28 - 31*Math.floor(MoisPaques/4);
  if (JourPaques==31) {
    var LundiPaques=1;
    var M_LundiPaques=MoisPaques+1;
  }else{
    var LundiPaques=JourPaques+1;
    var M_LundiPaques=MoisPaques;
  }
  if ( ((m==0 || m==4 || m==10)&&(j==1)) || (m==11 && (j==25||j==26) ) || (m==10&&j==11) || (m==4&&j==8) || (m==6&&j==14) || (m==7&&j==15)) {
    return true;
  }else if( ( (m==(MoisPaques-1)) && (j==JourPaques) )|| ( (m==(M_LundiPaques-1)) && (j==LundiPaques) ) ){
    return true;
  }else{
    return false;
  }
}

//calendrier classique:
function other_calendrier(mois,annes){
  if ((annes==0)&&(mois==0)) {
    var date = new Date();
    var jour = date.getDate();
    var moi = date.getMonth();
    var annee = date.getYear();
    var m = moi;
    var a = annee;
    if (a<=200) {
      a += 1900;
    }
  }else{
    var date = new Date(annes,mois,1);
    var moi = date.getMonth();
    var annee = date.getYear();
    var auj = new Date();
    var jour = auj.getDate();
    var m = auj.getMonth();
    var a = auj.getYear();
    if (a<=200){
      a += 1900;
    }
  }
  if(annee<=200){
    annee += 1900;
  }
  mois = new Array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
  jours_dans_moi = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
  if(annee%4 == 0 && annee!=1900){
    jours_dans_moi[1]=29;
  }
  total = jours_dans_moi[moi];
  var date_aujourdui = mois[moi]+' '+annee;
  dep_j = date;
  dep_j.setDate(1);
  if(dep_j.getDate()==2){
    dep_j=setDate(0);
  }
  dep_j = dep_j.getDay();
  //construction du calendrier en html:
  document.getElementById('date_auj').innerHTML = '<a href="acceuil.php?m='+moi+'&a='+annee+'"> << </a>'+date_aujourdui+'<a href="acceuil.php?p='+moi+'&a='+annee+'"> >> </a>' ;
  document.write('<table class="cal_calendrier" onload="opacite(document.getElementById(\'cal_body\'),20);"><tbody id="cal_body">');
  document.write('<tr class="cal_j_semaines"><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr><tr>');
  sem = 0;
  for(i=2;i<=dep_j;i++){
    //on regarde si il y a des jours qui ne fais pas partie du mois actuel (les jours en gris sur le reslutat final):
    if (moi==0) {
      document.write('<td class="cal_jours_av_ap">'+(jours_dans_moi[11]-dep_j+i)+'</td>');
      sem++;
    }else{
      document.write('<td class="cal_jours_av_ap">'+(jours_dans_moi[moi-1]-dep_j+i)+'</td>');
      sem++;
    }
  }
  for(i=1;i<=total;i++){
    //les jours celons qui est feriers,weekends ou jours classiques 
    if(sem==0){
      document.write('<tr>');
    }
    if (its_ferier(annee,moi,i)){
      document.write('<td class="cal_ferier">'+i+'</td>');
    }else{
      if((jour==i)&&(moi==m)&&(a==annee)){
        document.write('<td class="cal_aujourdhui">'+i+'</td>');
      }else{
        if (sem==5 || sem==6){
          document.write('<td class="cal_weekend">'+i+'</td>');
        }else{
          document.write('<td>'+i+'</td>');
        }
      }
    }
    sem++;
    //semaine de 7 jours
    if(sem==7){
      document.write('</tr>');
      sem=0;
    }
  }
  //on regarde encore une fois si il y a des jours qui ne font pas partie du mois actuelle.
  for(i=1;sem!=0;i++){
    document.write('<td class="cal_jours_av_ap">'+i+'</td>');
    sem++;
    if(sem==7){
      document.write('</tr>');
      sem=0;
    }
    }
    document.write('</tbody></table>');
    return moi;
}


//même calendrier sauf avec des fonctionaliter de enregistrement d'événement:
function event_calendrier(mois,annes,nom,prenom){
 if ((annes==0)&&(mois==0)) {
    var date = new Date();
    var jour = date.getDate();
    var moi = date.getMonth();
    var annee = date.getYear();
    var m = moi;
    var a = annee;
    if (a<=200){
      a += 1900;
    }
  }else{
    var date = new Date(annes,mois,1);
    var moi = date.getMonth();
    var annee = date.getYear();
    var auj = new Date();
    var jour = auj.getDate();
    var m = auj.getMonth();
    var a = auj.getYear();
    if (a<=200) 
    {
      a += 1900;
    }
  }
  if(annee<=200){
    annee += 1900;
  }
  mois = new Array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
  jours_dans_moi = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
  if(annee%4 == 0 && annee!=1900){
    jours_dans_moi[1]=29;
  }
  total = jours_dans_moi[moi];
  var date_aujourdui = mois[moi]+' '+annee;
  dep_j = date;
  dep_j.setDate(1);
  if(dep_j.getDate()==2){
    dep_j=setDate(0);
  }
  dep_j = dep_j.getDay();
  document.getElementById('date_auj').innerHTML = '<a href="useracceuil.php?m='+moi+'&a='+annee+'&inscrit='+nom+'&prenom='+prenom+'"> << </a>'+date_aujourdui+'<a href="useracceuil.php?p='+moi+'&a='+annee+'&inscrit='+nom+'&prenom='+prenom+'"> >> </a>' ;
  document.write('<table class="cal_calendrier" onload="opacite(document.getElementById(\'cal_body\'),20);"><tbody id="cal_body">');
  document.write('<tr class="cal_j_semaines"><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr><tr>');
  sem = 0;
  for(i=2;i<=dep_j;i++){
    if (moi==0) {
      document.write('<td class="cal_jours_av_ap">'+(jours_dans_moi[11]-dep_j+i)+'</td>');
      sem++;
    }else{
      document.write('<td class="cal_jours_av_ap">'+(jours_dans_moi[moi-1]-dep_j+i)+'</td>');
      sem++;
    }
  }
  // les événemets :
  for(i=1;i<=total;i++){
    ++moi;
    var date_event = annee+'-'+moi+'-'+i;
    --moi;
    if(sem==0){
      document.write('<tr>');
    }
    if( i < 10 ){ 
      i = '0' + i; 
    }
    if (localStorage.hasOwnProperty(i)){ 
        document.write('<td class="cal_event"><a href="useracceuil.php?day='+date_event+'&inscrit='+nom+'&prenom='+prenom+'" ><abbr title="event"><strong>'+i+'</strong></abbr></a></td>');
    }else{
      if((jour==i)&&(moi==m)&&(a==annee)){
        document.write('<td class="cal_aujourdhui"><a href="useracceuil.php?day='+date_event+'&inscrit='+nom+'&prenom='+prenom+'" >'+i+'</a></td>');
      }else{
        if (its_ferier(annee,moi,i)) {
          document.write('<td class="cal_ferier"><a href="useracceuil.php?day='+date_event+'&inscrit='+nom+'&prenom='+prenom+'" ><abbr title="ferier">'+i+'</abbr></a></td>');
        }else{
          if (sem==5 || sem==6) {
            document.write('<td class="cal_weekend"><a href="useracceuil.php?day='+date_event+'&inscrit='+nom+'&prenom='+prenom+'" ><abbe title="weekend">'+i+'</abbr></a></td>');
          }else{
            document.write('<td><a href="useracceuil.php?day='+date_event+'&inscrit='+nom+'&prenom='+prenom+'" >'+i+'</a></td>');
          }
        }
      }
    }
    sem++;
    if(sem==7){
      document.write('</tr>');
      sem=0;
    }
  }
  for(i=1;sem!=0;i++){
    document.write('<td class="cal_jours_av_ap">'+i+'</td>');
    sem++;
    if(sem==7){
      document.write('</tr>');
      sem=0;
    }
  }
  document.write('</tbody></table>');
  localStorage.clear();
  return moi;
}

//les jours restant avant l'événemet
function jour_restant(annee,mois,jour,heure,minute){
  --mois;
  date1 = new Date(); 
  var date2 = new Date(annee,mois,jour,heure,minute); 
  var Diff_temps = date2.getTime() - date1.getTime(); 
  var Diff_jours = Diff_temps / (1000 * 3600 * 24); 
  var event = Math.round(Diff_jours);
  if (event<0) {
    alert("L'evenement est deja passer!!"); 
    document.getElementById('month_event').innerHTML = "<h1>L'evenement a eu lieu il y a environ "+(event*(-1))+" jours</h1>"; 
  }else if(event==0){
    alert("L'evenement est pour aujourdui !!");
    document.getElementById('month_event').innerHTML = "<h1>L'evenement a lieu aujourdui </h1>"; 
  }else{
    document.getElementById('month_event').innerHTML = "<h1>L'evenement auras lieu dans environ "+event+" jours </h1>"; 
  }
}