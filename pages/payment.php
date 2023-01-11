
<?php 


session_start();

if (!empty($_SERVER['HTTP_CLIENT_IP']))   
  {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
  }
//whether ip is from proxy
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
  {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
  }
//whether ip is from remote address
else
  {
    $ip_address = $_SERVER['REMOTE_ADDR'];
  }

if ( !isset($_SESSION['login'])) {
  header('Location: login.php');
}

if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {

    $dateTIMEnow = date("Y-m-d H:i:s");
    $nameUSER = $_SESSION['login'][2];
    $prenomeUSER =  $_SESSION['login'][3];

include("../Connect/cnx.php");
ob_start();
if (isset($_GET['id'])) {

  $ID_student = $_GET['id'];
  
  }

  if (isset($_GET['ids'])) {

    $ID_student = $_GET['ids'];
    
    }

    $Query = "SELECT * FROM etudiant WHERE ID=$ID_student;";

  
$result = mysqli_query($conn,$Query);

$row = mysqli_fetch_assoc($result);

$PrenomFORspy = $row["Prenom"];
$NomFORspy = $row["Nom"];

$thisYEAR = date("Y");
$yearAND1 = $thisYEAR+1;

// annee scolaire  Begin
$query_anee_setting_Begin = "SELECT Valuess FROM setting WHERE Namess = 'Anne_Begin'";
$result_annee_setting_Begin = mysqli_query($conn,$query_anee_setting_Begin);
$row_annee_setting_Begin = mysqli_fetch_assoc($result_annee_setting_Begin);

$value_annee_setting_Begin = $row_annee_setting_Begin["Valuess"];
// annee scolaire  Begin

// annee scolaire  End
$query_anee_setting_End = "SELECT Valuess FROM setting WHERE Namess = 'Anne_End'";
$result_annee_setting_End = mysqli_query($conn,$query_anee_setting_End);
$row_annee_setting_End = mysqli_fetch_assoc($result_annee_setting_End);

$value_annee_setting_End = $row_annee_setting_End["Valuess"];
// annee scolaire  End
$Annee_scolaireSQL ="$value_annee_setting_Begin-$value_annee_setting_End";




// table etudiant
$Query_description_class = "SELECT etudiant.ID as etudiants_id,etudiant.Prenom,etudiant.Remise,etudiant.Nom,etudiant.Photo,etudiant.Langues_parlees_par_les_parents,inscription.Etu_ID,inscription.grop_ID,groupe.ID,groupe.Niveau_ID,groupe.Nom AS nomgroup,niveau.ID AS id_niveusqr,niveau.Annee,niveau.Price FROM etudiant JOIN inscription ON etudiant.ID = inscription.Etu_ID JOIN groupe ON groupe.ID = inscription.grop_ID JOIN niveau ON niveau.ID = groupe.Niveau_ID WHERE etudiant.ID=$ID_student";
  $result_Query_description_class = mysqli_query($conn,$Query_description_class);
  $row_result_Query_description_class = mysqli_fetch_assoc($result_Query_description_class);

  $Query_inscription = "SELECT `ID`, `Etu_ID`, `grop_ID` FROM `inscription` WHERE `Etu_ID`= $ID_student AND inscription.Annee_scolaire = '$Annee_scolaireSQL'";

  $result_inscription = mysqli_query($conn,$Query_inscription);
  
  $row_inscription = mysqli_fetch_assoc($result_inscription);
  
  $ID_inscription = $row_inscription["ID"];

  $id_niveusqrdrt = $row_result_Query_description_class["id_niveusqr"];
  
  if (isset($_GET['ids'])) {



    $Query_activites = "SELECT inscription_activites.ID,inscription_activites.statut,activites.Descriptions from inscription_activites
    join inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on activites.ID = inscription_niveau_activites.activ_ID 
    where inscription_activites.statut = 1 and inscription_niveau_activites.Annee_scolaire = '$Annee_scolaireSQL' and inscription_activites.inscr_ID = $ID_inscription and inscription_niveau_activites.nive_ID=$id_niveusqrdrt";
    
    $result_activites = mysqli_query($conn,$Query_activites);
    
    if (isset($_POST["submit"])) {
      
      $Mode_de_paiement = $_POST["Mode_de_paiement"];
      $activites_ID = $_POST["activites"];
      $dates = date("Y-m-d H:i:s");
      $Debut_de_validite = $_POST["Debut_de_validite"];
      $Fin_de_validite = $_POST["Fin_de_validite"];
    
      
      
      // $ID_PAYEMENT_ACTIVITI = $row_activitesspy["ID"];
   
    
      $Query_payment = "INSERT INTO `paiement_activite`(`insactivites_ID`,`insc_ID`,`Date_de_paiement`, `Mode_de_paiement`, `Debut_de_validite`, `Fin_de_validite`) VALUES ($activites_ID,$ID_inscription,'$dates','$Mode_de_paiement','$Debut_de_validite','$Fin_de_validite')";
    
    mysqli_query($conn,$Query_payment);

    $Query_activitesspy = "SELECT inscription_niveau_activites.Tarif,activites.Descriptions from inscription_activites join inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on inscription_niveau_activites.activ_ID = activites.ID where inscription_activites.ID = $activites_ID";
    
      $result_activitesspy = mysqli_query($conn,$Query_activitesspy);
      
      $row_activitesspy = mysqli_fetch_assoc($result_activitesspy);
      $Tarif = $row_activitesspy["Tarif"];
      $Descriptions = $row_activitesspy["Descriptions"];

    $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Insérer','$dateTIMEnow','(Paiement Activités) Étudiant $PrenomFORspy $NomFORspy','Des activités $Descriptions ont été payées, prix($Tarif DH),Date($Debut_de_validite - $Fin_de_validite)','$ip_address')";
    mysqli_query($conn,$Query_spy);
    
    }
    
    
      }

if (isset($_GET['id'])) {



if (isset($_POST["submit"])) {
  
    $Montant = $_POST["Montant"];
    $Mode_de_paiement = $_POST["Mode_de_paiement"];
    $Debut_de_validite = $_POST["Debut_de_validite"];
    $Fin_de_validite = $_POST["Fin_de_validite"];
    $date = date("Y-m-d H:i:s");
    $Query_payment = "INSERT INTO `paiement`(`Ins_ID`, `Montant`, `Mode_de_paiement`, `Date_de_paiement`, `Debut_de_validite`, `Fin_de_validite`) VALUES
     ($ID_inscription,'$Montant','$Mode_de_paiement','$date','$Debut_de_validite','$Fin_de_validite')";

mysqli_query($conn,$Query_payment);

$Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Insérer','$dateTIMEnow','(Paiement Mensuel) Étudiant $PrenomFORspy $NomFORspy','Paiement $Debut_de_validite - $Fin_de_validite ont été payées, prix($Montant DH)','$ip_address')";
mysqli_query($conn,$Query_spy);

}
}
///mather
$Query_join_Mather = "SELECT etudiant_parent_tuteur.Etudiant_ID,  parent_tuteur.ID, parent_tuteur.Nom, parent_tuteur.Cin, parent_tuteur.Prenom, parent_tuteur.Tel_personnel, parent_tuteur.Tel_travail
FROM etudiant_parent_tuteur
INNER JOIN parent_tuteur ON etudiant_parent_tuteur.Mather_id=parent_tuteur.ID WHERE etudiant_parent_tuteur.Etudiant_ID=$ID_student;";

$result_join_Mather = mysqli_query($conn,$Query_join_Mather);

//father

$Query_join_Father = "SELECT etudiant_parent_tuteur.Etudiant_ID,  parent_tuteur.ID, parent_tuteur.Nom, parent_tuteur.Cin, parent_tuteur.Prenom, parent_tuteur.Tel_personnel, parent_tuteur.Tel_travail
FROM etudiant_parent_tuteur
INNER JOIN parent_tuteur ON etudiant_parent_tuteur.Father_id=parent_tuteur.ID WHERE etudiant_parent_tuteur.Etudiant_ID=$ID_student;";

$result_join_Father = mysqli_query($conn,$Query_join_Father);


///responable

$Query_join_Responsible = "SELECT etudiant_parent_tuteur.Etudiant_ID,  parent_tuteur.ID, parent_tuteur.Nom, parent_tuteur.Cin, parent_tuteur.Prenom, parent_tuteur.Tel_personnel, parent_tuteur.Tel_travail
FROM etudiant_parent_tuteur
INNER JOIN parent_tuteur ON etudiant_parent_tuteur.Parent_Tuteur_ID=parent_tuteur.ID WHERE etudiant_parent_tuteur.Etudiant_ID=$ID_student;";

$result_join_Responsible = mysqli_query($conn,$Query_join_Responsible);


$row_Father = mysqli_fetch_assoc($result_join_Father);
$row_Mather = mysqli_fetch_assoc($result_join_Mather);
$row_Responsible = mysqli_fetch_assoc($result_join_Responsible);


$Father_Responsible = "";
$Mather_Responsible = "";

if ($row_Father['Prenom'] == $row_Responsible['Prenom'] && $row_Father['Nom'] == $row_Responsible['Nom']  ) {
    $Father_Responsible = "(responsible)";
   
}

if ($row_Mather['Prenom'] == $row_Responsible['Prenom'] && $row_Mather['Nom'] == $row_Responsible['Nom'] ) {
    $Mather_Responsible = "(responsible)";
   
}

//test for if have any problem

//End test for if have any problem

// print_r($Problem);

if (isset($_POST["deletPAYEMENT"])) {

  $idpayements = $_POST["deletPAYEMENT"];
 

  $Query_deletpayemntSPY = "SELECT * FROM `paiement` WHERE `ID`= $idpayements";

$result_Query_deletpayemntSPY = mysqli_query($conn,$Query_deletpayemntSPY);

$row_Query_deletpayemntSPY = mysqli_fetch_assoc($result_Query_deletpayemntSPY);

$pymt_Montant = $row_Query_deletpayemntSPY["Montant"];
$pymt_Debut_de_validite = $row_Query_deletpayemntSPY["Debut_de_validite"];
$pymt_Fin_de_validite = $row_Query_deletpayemntSPY["Fin_de_validite"];


  $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Supprimer','$dateTIMEnow','(Paiement Mensuel) Étudiant $PrenomFORspy $NomFORspy','Paiement $pymt_Debut_de_validite - $pymt_Fin_de_validite Il a été supprimé, prix($pymt_Montant DH)','$ip_address')";
  mysqli_query($conn,$Query_spy);

  $dletPAYEMENTqr = "DELETE FROM paiement WHERE ID = $idpayements;";
  mysqli_query($conn,$dletPAYEMENTqr);


  
}

if (isset($_POST["deletPAYEMENTactive"])) {

  $idpayements = $_POST["deletPAYEMENTactive"];
  $activites_quryes = "SELECT paiement_activite.ID,paiement_activite.Date_de_paiement,inscription_niveau_activites.Tarif,activites.Descriptions,paiement_activite.Debut_de_validite,paiement_activite.Fin_de_validite,inscription_activites.Remise from paiement_activite join inscription_activites on inscription_activites.ID = paiement_activite.insactivites_ID join inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on inscription_niveau_activites.activ_ID = activites.ID where paiement_activite.ID = $idpayements";
  
  $result_activites_quryes = mysqli_query($conn,$activites_quryes);
  
  $row_Query_deletpayemntSPY = mysqli_fetch_assoc($result_activites_quryes);
  
  $pymt_Montant = $row_Query_deletpayemntSPY["Tarif"] - $row_Query_deletpayemntSPY["Remise"];
  $pymt_datepayements = $row_Query_deletpayemntSPY["Date_de_paiement"];
  $pymt_Fin_de_validite = $row_Query_deletpayemntSPY["Descriptions"];
  $pymt_Debut_de_validite = $row_Query_deletpayemntSPY["Debut_de_validite"];
$pymt_Fin_de_validiteGFGFG = $row_Query_deletpayemntSPY["Fin_de_validite"];

  $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Supprimer','$dateTIMEnow','(Paiement Activités  ) Étudiant $PrenomFORspy $NomFORspy','Paiement $pymt_Debut_de_validite - $pymt_Fin_de_validiteGFGFG Il a été supprimé, prix($pymt_Montant DH)','$ip_address')";
  mysqli_query($conn,$Query_spy);

  $dletPAYEMENTqr = "DELETE FROM paiement_activite WHERE ID = $idpayements;";
  mysqli_query($conn,$dletPAYEMENTqr);


}

include("../include/nav.php");
?>

<link rel="stylesheet" href="../css\student.css">
<style>
.hm-gradient {
    background-image: linear-gradient(to top, #f3e7e9 0%, #e3eeff 99%, #e3eeff 100%);
}
.darken-grey-text {
    color: #2E2E2E;
}

@media print {
#printito img{
     display:flex;
     justify-content:center;
     align-items:center;
     
     }
    html, body{
      height:100%;
      width:100%;
    }
}

</style>
<title><?php echo "$Titre"; ?> Admin - Paiement</title>
    <!-- MAIN -->
    <div class="col">
        
        <!-- <h1>
            
            <small class="text-muted"></small>
        </h1> -->
        
       
<!-- For demo purpose -->

<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
        <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="<?php if ( !$row['Photo']==null|| !$row['Photo']=="" ) {echo "../uploads/". $row['Photo']; }else echo "https://cdn-icons-png.flaticon.com/512/183/183760.png";   ?>">
          <span class="namestudents" class="font-weight-bold"><?php echo $row['Prenom'] ." ". $row['Nom']. "<a href='student.php?id=".$row['ID']."'>"." ( #". $row['ID']." )"."</a>"; ?></span>
          <?php echo $row_result_Query_description_class["Annee"] . "/" .$row_result_Query_description_class["nomgroup"] ?>
            <span class="text-black-50"><?php echo $row['email']; ?></span>
            <br>
            <div class="card text-white bg-danger mb-3" style="max-width: 18rem;width: 100%;">
            
  <div class="card-header">information de Pére <br> <?php echo $Mather_Responsible ?></div> 
  <div class="card-body">
  
  <p class="card-text">

    <i class="fa fa-user fa-fw mr-1" aria-hidden="true"></i> Nom&prenom: <br><?php echo $row_Father['Prenom'] . " ". $row_Father['Nom']; ?> <br> <div></div> 

<i class="fa fa-mobile mr-2" style="font-size: 20px;" aria-hidden="true"></i>Tel personnel: <br><?php echo $row_Father['Tel_personnel']; ?> <br> 
<?php if(!$row_Father['Tel_travail'] ==""){ echo  '<br><i class="fa fa-phone mr-1" style="font-size: 18px;" aria-hidden="true"></i>  Tel travail:<br>' .$row_Father['Tel_travail']; }?></p>
<br> <i class="fa fa-id-card mr-2" style="font-size: 20px;" aria-hidden="true"></i>CIN Card: <br><?php echo $row_Father['Cin']; ?> <br>
<br> <br> ID: <?php  echo "<a href='./parent.php?id=" . $row_Father['ID'] . "' target='_blank'>" . $row_Father['ID'] ."(Plus..) </a>";  ?> <br> <br>
  </div>
</div>
<div class="card text-white bg-Secondary  mb-3" style="max-width: 18rem;width: 100%;">
  <div class="card-header">information de Mére <br> <?php echo $Mather_Responsible ?></div>
  <div class="card-body">
 
    <p class="card-text">
    <i class="fa fa-user fa-fw mr-1" aria-hidden="true"></i>   Nom&prenom: <br><?php echo $row_Mather['Prenom'] . " ". $row_Mather['Nom']; ?> <br><br>
    <i class="fa fa-mobile mr-2" style="font-size: 20px;" aria-hidden="true"></i> Tel personnel:<br><?php echo $row_Mather['Tel_personnel']; ?> <br> 
    <?php if(!$row_Mather['Tel_travail'] ==""){ echo "<br> <i class='fa fa-phone mr-1' style='font-size: 18px;' aria-hidden='true'></i> Tel travail: <br>" . $row_Mather['Tel_travail'];} ?></p>
    <br> <i class="fa fa-id-card mr-2" style="font-size: 20px;" aria-hidden="true"></i>CIN Card: <br><?php echo $row_Mather['Cin']; ?> <br> 

    <br> <br> ID: <?php  echo "<a href='./parent.php?id=" . $row_Mather['ID'] . "' target='_blank'>" . $row_Mather['ID'] ." (Plus..)</a>";  ?> <br> <br>
     
  </div>
</div>
<?php if ($row_Responsible['ID'] !== $row_Father['ID'] ) {  ?>

<div class="card text-white bg-Info mb-3" style="max-width: 18rem;width: 100%;">
  <div class="card-header">information  Responsable</div>
  <div class="card-body">
   
    <p class="card-text">
    <i class="fa fa-user fa-fw mr-1" aria-hidden="true"></i>   Nom&prenom: <br><?php echo $row_Responsible['Prenom'] . " ". $row_Responsible['Nom']; ?> <br><br>
    <i class="fa fa-mobile mr-2" style="font-size: 20px;" aria-hidden="true"></i>  Tel personnel: <br><?php echo $row_Responsible['Tel_personnel']; ?>  <br>    <?php if(!$row_Responsible['Tel_travail'] ==""){ echo  "<br> <i class='fa fa-phone mr-1' style='font-size: 18px;' aria-hidden='true'></i> Tel travail: <br>" . $row_Responsible['Tel_travail'];} ?>
    <br> <br><i class="fa fa-id-card mr-2" style="font-size: 20px;" aria-hidden="true"></i>CIN Card: <br><?php echo $row_Responsible['Cin']; ?> <br>    <br>  <br> ID: <?php  echo "<a href='./parent.php?id=" . $row_Responsible['ID'] . "' target='_blank'>" . $row_Responsible['ID'] ." (Plus..)</a>";  ?> <br> <br>

  
   
</p>
  </div>
</div>

<?php  }

$activites_quryes = "SELECT paiement_activite.ID, paiement_activite.Etu_ID, paiement_activite.Act_ID, activites.ID, activites.Descriptions
FROM paiement_activite
INNER JOIN activites ON paiement_activite.Act_ID = activites.ID WHERE paiement_activite.Etu_ID=$ID_student GROUP BY paiement_activite.Act_ID";

$result_activites_quryes = mysqli_query($conn,$activites_quryes);



?>
            </div>
        </div>
        <div class="col-md-5 border-right">
        
            <div>
                <div>
               <h4 class="mb-4 mt-5" style="text-align: center;"><?php if (isset($_GET['id'])) {echo "Paiement Mensuel"; } if (isset($_GET['ids'])) { echo "Paiement Activités";}?> </h4>  
                </div>
                <form method="POST" onsubmit="return confirm ('Êtes-vous sûr?')">
                <div class="row mt-2">
                <?php if (isset($_GET['id'])) {?>
                    <div class="col-md-6"><label class="labels">Montant</label><input name="Montant" value="<?php echo $row_result_Query_description_class["Price"] - $row_result_Query_description_class["Remise"] ?>" type="number" step="0.01" class="form-control" placeholder="Montant" required></div>
                    <?php } if (isset($_GET['ids'])) {?>
                      <div class="col-md-6"><label class="labels">Activites</label><select name="activites" class="form-control" aria-label="Mode de paiement">
                   <!-- <option disabled class="disable" selected>Choisir...</option> -->
                   <?php if (isset($_GET['ids'])) { while ($row_activites = mysqli_fetch_assoc($result_activites)) {

                    $Descriptions = $row_activites["Descriptions"];
                    $ID = $row_activites["ID"];

                    echo "<option value='$ID'>$Descriptions</option>";

                   } echo '</select>  ';


                  }

                  
                    
                    ?>
                    
                
              
               </div>
               
                <?php }?>

                    <div class="col-md-6"><label class="labels">Mode de paiement</label><select name="Mode_de_paiement" class="form-control" aria-label="Mode de paiement" required>
                    <option value="espèces">Espèces</option>
                    <option value="chèque">Chèque</option>
                    <option value="Paiement bancaire">Paiement bancaire</option>
                  </select>
                </div>
                   </div>
                 
                <div class="row mt-3">
                    <div class="col-md-6"><label class="labels">Debut de validite</label><input name="Debut_de_validite" type="date" class="form-control" value="<?php $newDate = date('Y-m-d'); echo $newDate; ?>" placeholder="country" required ></div>
                    <div class="col-md-6"><label class="labels">Fin de validite</label><input type="date" name="Fin_de_validite" class="form-control" value="<?php $newDate = date('Y-m-d', strtotime(' + 1 months')); echo $newDate; ?>" placeholder="state" required></div>
            
                  </div>
                  <?php  if (isset($_GET['ids'])) {?>
              <div class="accordion mt-5" id="accordionExample">
                  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        Ajouter un nouveau Activités
    </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
                <div id="group">
                <h4 class="text-center mb-5">Ajouter un nouveau Activités   </h4>
              
                
 <!-- checkbox -->
                
                        <?php 
                        
                        $id_student_neveu = $row_result_Query_description_class["id_niveusqr"];


                        $inscription_activites_quryes = "SELECT inscription_niveau_activites.Tarif,inscription_niveau_activites.ID AS niv_acti_ID,activites.Descriptions from inscription_niveau_activites 
                        JOIN activites ON activites.ID = inscription_niveau_activites.activ_ID WHERE inscription_niveau_activites.Annee_scolaire = '$Annee_scolaireSQL' AND inscription_niveau_activites.nive_ID = $id_student_neveu
                        AND inscription_niveau_activites.ID not in(select niv_acti_ID from inscription_activites);";   
                        $result_inscription_activites = mysqli_query($conn,$inscription_activites_quryes);

                        while ($row = mysqli_fetch_assoc($result_inscription_activites)) { $Descriptions_activites = $row['Descriptions'];  $activitesIDs = $row['niv_acti_ID'] ; ?>   
                        
                        
                <div class="form-check form-switch">
  <input class="form-check-input" name="check_list[]" type="checkbox" id="<?php echo "$activitesIDs"; ?>" value="<?php echo "$activitesIDs";?>">
  <label class="form-check-label" for="<?php echo "$activitesIDs"; ?>"><?php echo "$Descriptions_activites"; ?></label><br>
</div>
<?php }?>
                
                <div class="mt-5 text-center "><button name="subactives" id="subForm" class="btn btn-primary profile-button " type="submit">Ajouter Activités</button></div>

                

                
                </div>
                </div>
                </div>
                    </div>

                    <div class="card">
    <div class="card-header" id="headingone">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseone" aria-expanded="false" aria-controls="collapseone">
        Pause Activités
    </button>
      </h5>
    </div>
    <div id="collapseone" class="collapse" aria-labelledby="headingone" data-parent="#accordionExample">
      <div class="card-body">
                <div id="group">
                <h4 class="text-center mb-5">Pause Activités</h4>
                
                
                <style> 
                
                .clsinputcheck:checked + .lablcheckTEST {
                    -webkit-text-decoration-line: line-through; 
                  text-decoration-line: line-through; 
                }
                        </style>     
 <!-- checkbox -->
                
 <?php 
                        
                        // $inscription_activites_quryes = "SELECT inscription_activites.statut,activites.Descriptions from inscription_activites join activites
                        // ON inscription_activites.acti_ID = activites.ID  WHERE inscription_niveau_activites.Annee_scolaire = '$Annee_scolaireSQL' AND inscription_activites.inscr_ID = $ID_inscription" ;   
                        // $result_inscription_activites = mysqli_query($conn,$inscription_activites_quryes);

                        $inscription_activites_quryesi = "SELECT inscription_activites.ID,inscription_activites.statut,activites.Descriptions from inscription_activites
                        join inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on activites.ID = inscription_niveau_activites.activ_ID 
                        where  inscription_niveau_activites.Annee_scolaire = '$Annee_scolaireSQL' and inscription_activites.inscr_ID = $ID_inscription and inscription_niveau_activites.nive_ID=$id_niveusqrdrt";   
                        $result_inscription_activitesi = mysqli_query($conn,$inscription_activites_quryesi);

                        while ($row = mysqli_fetch_assoc($result_inscription_activitesi)) { $Descriptions_activites = $row['Descriptions'];  $activitesIDs = $row['ID'] ; ?>   
 
                        
                <div class="form-check form-switch">
  <input class="form-check-input clsinputcheck" name="pauseACTIV[]" type="checkbox" id="<?php echo "$activitesIDs"; ?>" value="<?php echo "$activitesIDs";?>" <?php if ($row['statut'] == 0) {echo "checked";} ?>>
  <label class="form-check-label lablcheckTEST" for="<?php echo "$activitesIDs"; ?>"><?php echo "$Descriptions_activites"; ?></label><br>
</div>
<?php }?>
                
                <div class="mt-5 text-center "><button name="PAUSEactives" id="subForm" class="btn btn-primary profile-button " type="submit">Soumettre</button></div>

                
                
              
                </div>
                </div>
                </div>
                    </div>


                    <!-- card remis  -->

                    <div class="card">
    <div class="card-header" id="headingone">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseoneo" aria-expanded="false" aria-controls="collapseone">
        Remise
    </button>
      </h5>
    </div>
    <div id="collapseoneo" class="collapse" aria-labelledby="headingone" data-parent="#accordionExample">
      <div class="card-body">
                <div id="group">
                <h4 class="text-center mb-5">Remise</h4>
                
                
                <style> 
                
                .clsinputcheck:checked + .lablcheckTEST {
                    -webkit-text-decoration-line: line-through; 
                  text-decoration-line: line-through; 
                }
                        </style>     
 <!-- checkbox -->
                
 <?php 
                        
                       

                        $inscription_activites_quryesi = "SELECT inscription_activites.ID,inscription_activites.Remise,inscription_activites.statut,activites.Descriptions from inscription_activites
                        join inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on activites.ID = inscription_niveau_activites.activ_ID 
                        where  inscription_niveau_activites.Annee_scolaire = '$Annee_scolaireSQL' and inscription_activites.inscr_ID = $ID_inscription and inscription_niveau_activites.nive_ID=$id_niveusqrdrt";   
                        $result_inscription_activitesi = mysqli_query($conn,$inscription_activites_quryesi);

                        while ($row = mysqli_fetch_assoc($result_inscription_activitesi)) { $Descriptions_activites = $row['Descriptions'];  $Remise = $row['Remise'];  $activitesIDs = $row['ID'] ; ?>   
 
                        

<div class="form-row">
    <div class="form-group col-md-4">
      <label class="col-form-label"><?php echo "$Descriptions_activites"; ?> :</label>
    </div>
    <div class="form-group col-md-3">
  
    <input type="number" class="form-control input" value="<?php echo "$Remise";?>" disabled>
    </div>
    <div class="form-group col-md-1">
    <label class="col-form-label">DH</label>
  </div>
    <div class="form-group col-md-2">
    <button class="btn btn-success mt-1 edit" type="button" value="<?php echo $activitesIDs;?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit" ></i></button>
    

    </div>
  </div>

<?php }?>
                

                
                
              
                </div>
                </div>
                </div>
                    </div>

<!-- card remis final -->

                </div>
              
                <?php


if (isset($_POST["PAUSEactives"]) ) {

  
  $query_statu = "UPDATE inscription_activites SET statut = b'1' WHERE inscr_ID = $ID_inscription";
  mysqli_query($conn,$query_statu);

  if (isset($_POST["pauseACTIV"])) {

  foreach($_POST['pauseACTIV'] as $IDASTIVITI) {
                  
    $query_query_status = "UPDATE inscription_activites SET statut = b'0' WHERE ID = $IDASTIVITI";
  mysqli_query($conn,$query_query_status);

  $Query_frorSPY = "SELECT activites.Descriptions FROM inscription_activites 
                  JOIN inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on activites.ID = inscription_niveau_activites.activ_ID WHERE inscription_activites.ID = $IDASTIVITI";
                    $result_frorSPY = mysqli_query($conn,$Query_frorSPY);
                    $row_frorSPY= mysqli_fetch_assoc($result_frorSPY);
                    $Descriptions_forSPY= $row_frorSPY["Descriptions"];

                  $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Insérer','$dateTIMEnow','$PrenomFORspy $NomFORspy','Activité  $Descriptions_forSPY a été pausee','$ip_address')";
                  mysqli_query($conn,$Query_spy);



    }
  }
      
      header("Refresh:0");
  
      ob_end_flush();   
    
  
           }  



if (isset($_POST["RemiseUPDATE"]) && isset($_POST["Remise"]) ) {


$Remisevalue = $_POST["Remise"];
$RemiseUPDATE = $_POST["RemiseUPDATE"];

    $activites_updatestatu_inscrption_insert = "UPDATE `inscription_activites` SET `Remise` = $Remisevalue
    WHERE `inscription_activites`.`ID` = $RemiseUPDATE";
    
    mysqli_query($conn,$activites_updatestatu_inscrption_insert);
  
    $Query_frorSPY = "SELECT activites.Descriptions FROM inscription_activites 
                  JOIN inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on activites.ID = inscription_niveau_activites.activ_ID WHERE inscription_activites.ID = $RemiseUPDATE";
                    $result_frorSPY = mysqli_query($conn,$Query_frorSPY);
                    $row_frorSPY= mysqli_fetch_assoc($result_frorSPY);
                    $Descriptions_forSPY= $row_frorSPY["Descriptions"];

                  $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Insérer','$dateTIMEnow','$PrenomFORspy $NomFORspy','Une remise de $Remisevalue DH a été ajoutée au activite $Descriptions_forSPY ','$ip_address')";
                  mysqli_query($conn,$Query_spy);

    header("Refresh:0");

    ob_end_flush();   
  

         }       
              if (isset($_POST["subactives"]) && isset($_POST["check_list"])) {
                
                foreach($_POST['check_list'] as $ID_activitestsql) {
                  
                  $activites_inscrption_insert = "INSERT INTO `inscription_activites`(`inscr_ID`, `niv_acti_ID`, `statut`) VALUES ($ID_inscription,$ID_activitestsql,1)";
                  
                  mysqli_query($conn,$activites_inscrption_insert);

                  $Query_frorSPY = "SELECT activites.Descriptions FROM inscription_niveau_activites 
                  JOIN activites on inscription_niveau_activites.activ_ID = activites.ID WHERE inscription_niveau_activites.ID = $ID_activitestsql";
                    $result_frorSPY = mysqli_query($conn,$Query_frorSPY);
                    $row_frorSPY= mysqli_fetch_assoc($result_frorSPY);
                    $Descriptions_forSPY= $row_frorSPY["Descriptions"];

                  $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Insérer','$dateTIMEnow','$PrenomFORspy $NomFORspy','activite $Descriptions_forSPY a été ajouté ','$ip_address')";
                  mysqli_query($conn,$Query_spy);
              
              
                  }

                  header("Refresh:0");
              
                  ob_end_flush();   

              }
              
              }?>


                <?php  echo "<br><label class='labels'>Activités il participe :</label> <br>";

$inscription_activites_quryesi = "SELECT inscription_activites.ID,inscription_activites.statut,activites.Descriptions from inscription_activites
join inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on activites.ID = inscription_niveau_activites.activ_ID 
where inscription_niveau_activites.Annee_scolaire = '$Annee_scolaireSQL' and inscription_activites.inscr_ID = $ID_inscription and inscription_niveau_activites.nive_ID=$id_niveusqrdrt";   
$result_inscription_activitesi = mysqli_query($conn,$inscription_activites_quryesi);
                   while ($row_activites_payement_names = mysqli_fetch_assoc($result_inscription_activitesi)) {

                    $Descriptions = $row_activites_payement_names["Descriptions"];
                    $ID = $row_activites_payement_names["ID"];
                  ?>
                  
                   <span id='<?php echo $ID?>' class='badge badge-pill badge-<?php if ($row_activites_payement_names["statut"]==0) {echo"danger";}else{echo"primary";} ?> mr-2 mb-2'><?php echo $Descriptions?></span> 
                  
                  <?php }?>
                
                <div class="mt-5 text-center "><button id="subForm" name="submit" class="btn btn-primary profile-button" type="submit">Soumettre</button></div>
                </form>
            </div>
            <a href="<?php if (isset($_GET['id'])) { echo'payment.php?ids='.$ID_student; } if (isset($_GET['ids'])) { echo'payment.php?id='.$ID_student; }?>"> <button type="button" class="btn btn-outline-secondary btn-lg btn-block mt-5"><?php if (isset($_GET['id'])) { echo'Paiement Activités <i class="fa fa-trophy" aria-hidden="true"></i>'; } if (isset($_GET['ids'])) { echo'Paiement Mensue <i class="fa fa-usd" aria-hidden="true"></i>'; }?></button> </a>


        </div>
        <div class="col-md-4">
            <div class="p-3 py-5">
                <div class="col-md-12"><label class="labels">historique de paiement:</label> 
                <form method='POST' onsubmit='return confirm ("Êtes-vous sûr?")'>
                <?php 

                if (isset($_GET['id'])) {
                $date_payement = "SELECT `Montant`, `Debut_de_validite`, `ID`, `Fin_de_validite` FROM `paiement` WHERE `Ins_ID` = $ID_inscription ORDER BY `ID` DESC";
                $result_date =  mysqli_query($conn,$date_payement);
                while ($row_date = mysqli_fetch_assoc($result_date)) {
                $Debut_de_validite = $row_date["Debut_de_validite"];
                $Fin_de_validite = $row_date["Fin_de_validite"];
                $Montant = $row_date["Montant"]; 
                $IDpayement = $row_date["ID"];            

                echo "    <li class='list-group-item list-group-item-success Mensuel'> <button style='background-color:transparent'
                 name='deletPAYEMENT' value='$IDpayement'  class='btn btn-primary-outline ' 
                 type='submit' ><span  class='add-experience  profile-button'><i class='fa fa-trash-o'>
                 </i></span> </button><span class='Mensuel_Debut_de_validite'>$Debut_de_validite</span> - <span class='Mensuel_Fin_de_validite'>$Fin_de_validite</span> 
                 <br> (<span class='Mensuel_Montant'>$Montant</span> DH) 

                 <button data-toggle='modal' data-target='#exampleModal' style='background-color:transparent'
                 name='deletPAYEMENT' value='$IDpayement'  class='btn btn-primary-outline payement_MensuelID' 
                 type='button' ><span  class='add-experience  profile-button'><i class='fa fa-file-pdf-o'>
                 </i></span> </button></li> <br> ".'<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                 <div  class="modal-dialog" role="document">
                   <div class="modal-content">
                     <div class="modal-header">
                       <h5 class="modal-title" id="exampleModalLabel">Facture Paiement Activités</h5>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                       </button>
                     </div>
                     <div class="modal-body">'."
                       <div id='printito'> 
                       ".'
                       <img src="https://i.imgur.com/5JD7Co6.png" class="rounded mx-auto d-block" >
                       
                       <h1 style="margin-top: 2%;text-align: center;border-style: solid;"> RECU DE PAIEMENT </h1>
                       <div style="margin-top: 10%;">
                           
                       <h4> Étudiant<span id="ÉtudiantNAME" style="padding-left: 100PX;">:</span> </h4>
                       <h4> Date<span id="dateNOWfct" style="padding-left: 142PX;">:</span> </h4>
                       <h4> Paiement ID<span id="IDfct" style="padding-left: 63px;">:</span> </h4>
                       <h4> Type<span style="padding-left: 145px;">: Paiement Mensuel</span> </h4>
                       <br>
                       <hr>
                       <br><br>
                       <h4> Debut de validite<span id="startIN" style="padding-left: 12px;">:</span> </h4>
                       <h4> Fin de validite<span id="endIN" style="padding-left: 48px;">:</span> </h4>
                       <br>
                       <h4> <strong>Montant</strong><span id="Montant" style="padding-left: 107px;">:</span> </h4>
                       <h4> <strong>Frais</strong><span style="padding-left: 152px;">: 0.00 DH</span> </h4>
                       <h4> <strong>Total a payer</strong><span id="Total" style="padding-left: 65px;">:</span> </h4><br><br>
                       
 
                       </div>
 
                       </div>
                     </div>
                     <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                       <button type="button" id="btnPRINT" class="btn btn-primary"><i class="fa fa-print mr-2" aria-hidden="true"></i>Imprimer</button>
                     </div>
                   </div>
                 </div>
               </div>';} 
              }
              
              if (isset($_GET['ids'])) {

                $date_payement = "SELECT inscription_niveau_activites.Date_de_debut,inscription_niveau_activites.Date_de_fin,paiement_activite.ID,paiement_activite.Date_de_paiement,inscription_niveau_activites.Tarif,activites.Descriptions,paiement_activite.Debut_de_validite,paiement_activite.Fin_de_validite,inscription_activites.Remise from paiement_activite join inscription_activites on inscription_activites.ID = paiement_activite.insactivites_ID join inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on inscription_niveau_activites.activ_ID = activites.ID 
                where paiement_activite.insc_ID =  $ID_inscription AND inscription_niveau_activites.Annee_scolaire = '$Annee_scolaireSQL' ORDER BY paiement_activite.ID DESC";
                $result_date =  mysqli_query($conn,$date_payement);
                while ($row_date = mysqli_fetch_assoc($result_date)) {
     
                $Debut_de_validite = $row_date["Date_de_paiement"];  
                $date = strtotime($Debut_de_validite);
                $resultDatenotime = date('Y-m-d', $date);
                $lastDate = date('Y-m-d', strtotime($resultDatenotime. ' + 1 months'));
                $Debut_de_validite = $row_date["Debut_de_validite"];
                $Fin_de_validite = $row_date["Fin_de_validite"];
                $IDpayementactive = $row_date["ID"];            
                $Tarif = $row_date["Tarif"] - $row_date["Remise"];      
                $Descriptionspayementactive = $row_date["Descriptions"]; 
                $Date_de_debutpayementactive = $row_date["Date_de_debut"]; 
                $Date_de_finpayementactive = $row_date["Date_de_fin"]; 

                echo "   <form method='POST'  onsubmit='return confirm ('Êtes-vous sûr?')'><li class='list-group-item list-group-item-success Activités' style='padding-bottom: 0px !important;'> <button style='background-color:transparent' name='deletPAYEMENTactive' class='btn btn-primary-outline ' value='$IDpayementactive' type='submit' ><span  class='  add-experience  profile-button'><i class='fa fa-trash-o'></i></span> </button>  <span class='nameactives'> $Descriptionspayementactive </span>   <br>  <span class='dateVALIDATION'>$Debut_de_validite</span> - <span class='Fin_de_validite'>$Fin_de_validite</span> <br> (<span class='Tarif'>$Tarif</span> DH)<button style='background-color:transparent' name='deletPAYEMENTactive' value='$IDpayementactive'
                class='btn btn-primary-outline IDpayementactive' type='button' data-toggle='modal' data-target='#exampleModal' ><span  class='  add-experience  profile-button'><i class='fa fa-file-pdf-o'></i></span> </button>  <br> <br><p class='terminera_active' style='font-size: 11px;'> 'Cette activité se terminera dans  <span class='Date_de_finpayementactive'>$Date_de_finpayementactive</span> ' </p> </li> <br> ". '<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div  class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Facture Paiement Activités</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">'."
                      <div id='printito'> 
                      ".'
                      <img src="https://i.imgur.com/5JD7Co6.png" class="rounded mx-auto d-block" >
                      
                      <h1 style="margin-top: 2%;text-align: center;border-style: solid;"> RECU DE PAIEMENT </h1>
                      <div style="margin-top: 10%;">
                          
                      <h4> Étudiant<span id="ÉtudiantNAME" style="padding-left: 100PX;">:</span> </h4>
                      <h4> Date<span id="dateNOWfct" style="padding-left: 142PX;">:</span> </h4>
                      <h4> Paiement ID<span id="IDfct" style="padding-left: 63px;">:</span> </h4>
                      <h4> Type<span style="padding-left: 145px;">: Paiement Activités</span> </h4>
                      <h4> Nom de l'."'".'activité<span id="activitéNAME" style="padding-left: 12px;">:</span> </h4>
                      <br>
                      <hr>
                      <br><br>
                      <h4> Debut de validite<span id="startIN" style="padding-left: 12px;">:</span> </h4>
                      <h4> Fin de validite<span id="endIN" style="padding-left: 48px;">:</span> </h4>
                      <br>
                      <h4> <strong>Montant</strong><span id="Montant" style="padding-left: 107px;">:</span> </h4>
                      <h4> <strong>Frais</strong><span style="padding-left: 152px;">: 0.00 DH</span> </h4>
                      <h4> <strong>Total a payer</strong><span id="Total" style="padding-left: 65px;">:</span> </h4><br><br>

                      <h6> <span id="terminera_active"></span></h6>
                     

                      </div>

                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                      <button type="button" id="btnPRINT" class="btn btn-primary"><i class="fa fa-print mr-2" aria-hidden="true"></i>Imprimer</button>
                    </div>
                  </div>
                </div>
              </div>';} 

              }

              ?>
                </form>
              </div>
            </div>
        </div>
                <div class="col ">
            <div class="p-3 py-5">
                <?php
                $fathersID = $row_Father['ID'] ;
                $MathersID = $row_Mather['ID'];
                $fResponsiblesID = $row_Responsible['ID'];  
                $Query = "SELECT etudiant_parent_tuteur.Parent_Tuteur_ID, etudiant_parent_tuteur.Etudiant_ID,etudiant_parent_tuteur.Mather_id,etudiant_parent_tuteur.Parent_Tuteur_ID,etudiant_parent_tuteur.Father_id,etudiant.* FROM etudiant_parent_tuteur INNER JOIN etudiant ON etudiant_parent_tuteur.Etudiant_ID = etudiant.ID WHERE etudiant.ID != $ID_student AND (etudiant_parent_tuteur.Mather_id= $MathersID OR etudiant_parent_tuteur.Father_id = $fathersID OR etudiant_parent_tuteur.Parent_Tuteur_ID = $fResponsiblesID)  ";
                $results = mysqli_query($conn,$Query);
                
                if (!mysqli_num_rows($results)==0) { 
                    # code...
                ?>
            <h2 class="text-center">Frères et Sœurs :</h2>
            <div class="row text-center" id="results">
    <?php 

    
while ($rows = mysqli_fetch_assoc($results)) {
// printf("%s (%s)\n", $row["ID"], $row["Nom"]);
?>

    <!-- Team item -->
    <div class="col-xl-3 col-sm-6 mb-5">
    <a href="student.php?id=<?php echo $rows["Etudiant_ID"]?>"> 
    <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="<?php if ( !$rows['Photo']==null|| !$rows['Photo']=="" ) {echo "../uploads/". $rows['Photo']; }else echo "https://cdn-icons-png.flaticon.com/512/183/183760.png";   ?>">
            <h5 class="mb-0"><?php echo $rows["Prenom"]." ". $rows["Nom"] ?> </h5>
            </a>
            <ul class="social mb-0 list-inline mt-3">
                <li class="list-inline-item"><a href="student.php?id=<?php echo $rows["Etudiant_ID"]?>" class="social-link"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
                <li class="list-inline-item"><a href="payment.php?ids=<?php echo $rows["Etudiant_ID"]?>" class="social-link"><i class="fa fa-trophy" aria-hidden="true"></i></a></li>
                <li class="list-inline-item"><a href="payment.php?id=<?php echo $rows["Etudiant_ID"]?>" class="social-link"><i class="fa fa-usd" aria-hidden="true"></i></a></li>
            </ul>
        </div>
        
    </div><!-- End -->

  
  
    <?php }}?>
        
        
        </div>
    </div>
    </div>
</div>
</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
        var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      DateNOWfct = yyyy  + '/' + mm  + '/' + dd ;

      //activités
    $('.Activités').click(function(){
    var idpayement = "";
    var namestudents = "";
    var dateVALIDATION = "";
    var Fin_de_validite = "";
    var nameactives = "";
    var Tarif = "";
    var Date_de_finpayementactive = "";
    var terminera_active = "";

    var idpayement = $('.IDpayementactive',this).val();
    var namestudents = $('.namestudents').text();
    var dateVALIDATION = $('.dateVALIDATION',this).text();
    var Fin_de_validite = $('.Fin_de_validite',this).text();
    var nameactives = $('.nameactives',this).text();
    var Tarif = $('.Tarif',this).text();
    var Date_de_finpayementactive = $('.Date_de_finpayementactive',this).text();
    var terminera_active = $('.terminera_active',this).text();

    $( "#ÉtudiantNAME" ).text( ": "+namestudents );
    $( "#dateNOWfct" ).text( ": "+DateNOWfct );
    $( "#IDfct" ).text( ": #"+idpayement );
    $( "#activitéNAME" ).text(": "+ nameactives );
    $( "#startIN" ).text( ": "+dateVALIDATION );
    $( "#endIN" ).text( ": "+Fin_de_validite );
    $( "#Montant" ).text( ": "+Tarif+" DH" );
    $( "#Total" ).text( ": "+Tarif+" DH" );
    $( "#terminera_active" ).text( terminera_active );


});

//activités end


//Mensuel

$('.Mensuel').click(function(){
  $( "#exampleModalLabel" ).text( "Facture Paiement Mensuel" );
    var idpayement = "";
    var namestudents = "";
    var dateVALIDATION = "";
    var Fin_de_validite = "";
    var Tarif = "";

    var idpayement = $('.payement_MensuelID',this).val();
    var namestudents = $('.namestudents').text();
    var dateVALIDATION = $('.Mensuel_Debut_de_validite',this).text();
    var Fin_de_validite = $('.Mensuel_Fin_de_validite',this).text(); 
    var Tarif = $('.Mensuel_Montant',this).text();


    $( "#ÉtudiantNAME" ).text( ": "+namestudents );
    $( "#dateNOWfct" ).text( ": "+DateNOWfct );
    $( "#IDfct" ).text( ": #"+idpayement );
    $( "#startIN" ).text( ": "+dateVALIDATION );
    $( "#endIN" ).text( ": "+Fin_de_validite );
    $( "#Montant" ).text( ": "+Tarif+" DH" );
    $( "#Total" ).text( ": "+Tarif+" DH" );
   


});

//Mensuel end
$('#btnPRINT').click(function(){
    

    $("#printito").printThis();
  
  
  });
  
var btn_edit = document.getElementById('Edit');


btn_edit.addEventListener("click", edit);

function edit (){
    var botton_sub = document.getElementById('subForm');
    // botton_sub.style.display = "block";
    btn_edit.style.display = "none";
    botton_sub.className = "btn btn-primary profile-button";


    const disables = document.querySelectorAll('.disable');

disables.forEach(box => {
  box.className = "form-control";

});

}



</script>




<?php  include("../include/footer.php");
 }else  if ( !isset($_SESSION['login'])) {
  header('Location: login.php');
}
?>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

<script src="..\js\printThis.js"></script>



   <script>

$(document).ready(function () {
            
            $( ".edit" ).click(function() {
                var name = $(this).attr("name");

if (name =="RemiseUPDATE") {

 
}else{

                $(".input").removeAttr('name');
                $(".input").prop("disabled", true).css("background-color", "#f2f2f2");
                $(".edit").removeAttr('name');
                $(".edit").attr("type", "button");
                $(".edit").find(">:first-child").attr('class', 'fa fa-edit');

}
                
               
                $tag = $(this).parent().parent() ;
                $tag.find(":input[type=number]").prop("disabled", false).css("background-color", "white");
                $tag.find(":input[type=number]").attr('name', 'Remise');
                    $(this).find(">:first-child").attr('class', 'fa fa-floppy-o');
                   
                    $targertyhis = $(this)
                    setTimeout(
  function() 
  {
    $targertyhis.attr('name', 'RemiseUPDATE');
    $targertyhis.attr("type", "submit");
                  }, 500);
                    
                    

});


 
});



</script>
