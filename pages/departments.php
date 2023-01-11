
<?php 



session_start();
// if ( !isset($_SESSION['login'])) {
//     header('Location: login.php');
// }
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

 if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Director" || isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {
    ob_start();

include("../Connect/cnx.php");


include("../include/nav.php");

// echo date('m');
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

$Query_activitesTABL = "SELECT inscription_niveau_activites.ID,inscription_niveau_activites.Annee_scolaire,inscription_niveau_activites.Date_de_fin,inscription_niveau_activites.Date_de_debut,activites.Descriptions,niveau.Annee,inscription_niveau_activites.Tarif 
from activites 
join inscription_niveau_activites on inscription_niveau_activites.activ_ID = activites.ID
join niveau on inscription_niveau_activites.nive_ID = niveau.ID where inscription_niveau_activites.Annee_scolaire = '$Annee_scolaireSQL'";
$result_activitesTABL = mysqli_query($conn,$Query_activitesTABL);

$Query_Anne  = "SELECT `ID`, `Annee` FROM `niveau`;";

$result_niveau = mysqli_query($conn, $Query_Anne);

    $dateTIMEnow = date("Y-m-d H:i:s");
    $nameUSER = $_SESSION['login'][2];
    $prenomeUSER =  $_SESSION['login'][3];

if (isset($_POST['subGoup'])) {

    $Annee = $_POST['Annee'];
    $group_Anne = $_POST['group'];



    $Query_groupe = "INSERT INTO `groupe`( `Nom`, `Niveau_ID`) VALUES ('$group_Anne',$Annee);";
    mysqli_query($conn, $Query_groupe);

    $Query_Annee = "SELECT `ID`, `Annee` FROM `niveau` WHERE ID = $Annee";
    $result_Annee = mysqli_query($conn,$Query_Annee);
    $row_Annee = mysqli_fetch_assoc($result_Annee);
    $namANNEE= $row_Annee["Annee"];
    $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Insérer','$dateTIMEnow','Groupes','Le groupe $group_Anne a été ajouté au niveau $namANNEE','$ip_address')";
    mysqli_query($conn,$Query_spy);




    header('Location: departments.php');

    ob_end_flush();

}

if (isset($_POST['subForm'])) {

$groupe = $_POST['groupe'];
$departement = $_POST['departement'];
$prix = $_POST['prix'];

$Query_niveau = "INSERT INTO `niveau`(`Annee`,`Price`) VALUES ('$departement','$prix');";
 



if ( mysqli_query($conn,$Query_niveau)) {
    $groupe_id = mysqli_insert_id($conn);
  }

  $Query_groupe = "INSERT INTO `groupe`(`Nom`, `Niveau_ID`) VALUES ('$groupe',$groupe_id);";

  mysqli_query($conn, $Query_groupe);

  $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Insérer','$dateTIMEnow','département' ,'Le niveau $departement a été ajouté avec le groupe $groupe Le prix $prix DH','$ip_address')";
  mysqli_query($conn,$Query_spy);


header('Location: departments.php');

ob_end_flush();

}


if (isset($_POST['activites']) && isset($_POST['niveu_list'])) {
    $Descriptions = $_POST['Descriptions'];
    $Date_de_fin = $_POST['Date_de_fin'];
    $Date_de_debut = $_POST['Date_de_debut'];
    $Tarif = $_POST['Tarif'];
    $Annee_scolaire = $_POST['scolaire'];

    $Query_activites = "INSERT INTO `activites`(`Descriptions`) VALUES ('$Descriptions');";

    mysqli_query($conn, $Query_activites);

    $last_id_active = mysqli_insert_id($conn);

    foreach($_POST['niveu_list'] as $ID_niveu_list) {
                  
        $activites_inscrption_insert = "INSERT INTO `inscription_niveau_activites`(`nive_ID`, `activ_ID`, `Tarif`,`Annee_scolaire`, `Date_de_debut`, `Date_de_fin`) 
        VALUES ($ID_niveu_list,$last_id_active,$Tarif,'$Annee_scolaire','$Date_de_debut','$Date_de_fin')";
        
        mysqli_query($conn,$activites_inscrption_insert);

        $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Insérer','$dateTIMEnow','activites','activite $Descriptions a été ajouté le prix: $Tarif DH, Date de debut : $Date_de_debut, Date de fin : $Date_de_fin','$ip_address')";
    mysqli_query($conn,$Query_spy);

        }


    
    header('Location: departments.php');
    ob_end_flush();
}


//table deparetemnt

if (isset($_POST["update_deparetement"])) {



    $ID_deparetementTBL = $_POST["update_deparetement"];
    $groupeTBL = $_POST['GroupeTBL'];
    $departementTBL = $_POST['DépartementTBL'];
    $prixTBL = $_POST['PrixTBL'];

    $Query_AnneGROUPSPY  = "SELECT  groupe.ID, groupe.Niveau_ID,groupe.Nom,niveau.Annee,niveau.ID,niveau.Price FROM 
    groupe JOIN niveau ON  groupe.Niveau_ID = niveau.ID WHERE groupe.ID = $ID_deparetementTBL";
    $result_Query_AnneGROUPSPY = mysqli_query($conn, $Query_AnneGROUPSPY);
    $ROW_Query_AnneGROUPSPY = mysqli_fetch_assoc($result_Query_AnneGROUPSPY);
    $NomFRORspy = $ROW_Query_AnneGROUPSPY["Nom"];
    $AnneeFRORspy = $ROW_Query_AnneGROUPSPY["Annee"];
    $PriceFRORspy = $ROW_Query_AnneGROUPSPY["Price"];

    $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Mettre à jour'
    ,'$dateTIMEnow','Département($AnneeFRORspy)','Une ou plusieurs valeurs ont été modifiées de ($AnneeFRORspy,$NomFRORspy,$PriceFRORspy DH) à ($departementTBL,$groupeTBL,$prixTBL DH)','$ip_address')";
    mysqli_query($conn,$Query_spy);


    $Query_updateTABL = "UPDATE groupe JOIN niveau ON niveau.ID = groupe.Niveau_ID
SET niveau.Annee = '$departementTBL', niveau.Price = $prixTBL , groupe.Nom = '$groupeTBL' WHERE groupe.ID =  $ID_deparetementTBL" ;
mysqli_query($conn,$Query_updateTABL);

header('Location: departments.php');

ob_end_flush();

}

if (isset($_POST["delet_deparetement"])) {
    $ID_deparetementTBL = $_POST["delet_deparetement"];
    
$Query_deparetemntsTABL = "SELECT niveau.ID AS idNIVO,niveau.Annee,niveau.Price,groupe.ID,groupe.Niveau_ID,groupe.Nom FROM groupe
JOIN niveau ON niveau.ID = groupe.Niveau_ID WHERE groupe.ID = $ID_deparetementTBL";
$result_Query_AnneGROUPSPYDEL = mysqli_query($conn, $Query_deparetemntsTABL);
$ROW_Query_AnneGROUPSPYDEL = mysqli_fetch_assoc($result_Query_AnneGROUPSPYDEL);
$NomFRORspy = $ROW_Query_AnneGROUPSPYDEL["Nom"];
$AnneeFRORspy = $ROW_Query_AnneGROUPSPYDEL["Annee"];
$PriceFRORspy = $ROW_Query_AnneGROUPSPYDEL["Price"];
$idNIVOS = $ROW_Query_AnneGROUPSPYDEL["idNIVO"];
    


$Query_updateTABL = "DELETE FROM `groupe` WHERE ID =  $ID_deparetementTBL" ;
mysqli_query($conn,$Query_updateTABL);
$Query_DELETNIVOSTABL = "DELETE FROM `niveau` WHERE ID =  $idNIVOS" ;
mysqli_query($conn,$Query_DELETNIVOSTABL);

$Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Supprimer','$dateTIMEnow','Groupe($NomFRORspy)','Le Groupe ($NomFRORspy) de Département ($AnneeFRORspy) a été supprimé','$ip_address')";
mysqli_query($conn,$Query_spy);
header('Location: departments.php');

ob_end_flush();

}



$Query_deparetemntsTABL = "SELECT niveau.ID,niveau.Annee,niveau.Price,groupe.ID AS IDoffall,groupe.Niveau_ID,groupe.Nom FROM groupe
JOIN niveau ON niveau.ID = groupe.Niveau_ID";
$result_deparetemntsTABL = mysqli_query($conn,$Query_deparetemntsTABL);

//table deparetement 


//table activites

if (isset($_POST["update_active"])) {

    $ID_activeTBL = $_POST["update_active"];
    $DescriptionsTBL = $_POST['DescriptionsTBL'];
    $Date_de_finTBL = $_POST['Date_de_finTBL'];
    $Date_de_debutTBL = $_POST['Date_de_debut'];
    $TarifTBL = $_POST['TarifTBL'];

    $Query_activitesSPYS = "SELECT activites.ID AS idactiviteso,activites.Descriptions,inscription_niveau_activites.Date_de_debut,inscription_niveau_activites.Date_de_fin,inscription_niveau_activites.Tarif,inscription_niveau_activites.Date_de_debut FROM activites JOIN inscription_niveau_activites on activites.ID = inscription_niveau_activites.activ_ID WHERE inscription_niveau_activites.ID = $ID_activeTBL";
    $result_activitesSPYS = mysqli_query($conn,$Query_activitesSPYS);
    $row = mysqli_fetch_assoc($result_activitesSPYS);
    $Descriptionsspy = $row['Descriptions'];
    $Datespy = $row['Date_de_debut'];
    $Date_de_debutspy = $row['Date_de_fin'];
    $Prixspy = $row['Tarif'];
    $Date_de_debutSPY = $row['Date_de_debut'];
    $idactiviteso = $row['idactiviteso'];
    $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Mettre à jour'
    ,'$dateTIMEnow','activites($Descriptionsspy)','Une ou plusieurs valeurs ont été modifiées de ($Descriptionsspy,$Date_de_debutspy / $Datespy,$Date_de_debutSPY,$Prixspy DH) à ($DescriptionsTBL,$Date_de_debutTBL / $Date_de_finTBL,$TarifTBL DH)','$ip_address')";
    mysqli_query($conn,$Query_spy);


    $Query_updateTABL = "UPDATE `activites` SET `Descriptions`='$DescriptionsTBL' WHERE ID = $idactiviteso" ;
mysqli_query($conn,$Query_updateTABL);
$Query_updateTABL = "UPDATE `inscription_niveau_activites` SET `Date_de_debut`='$Date_de_debutTBL' ,`Date_de_fin`='$Date_de_finTBL',`Tarif`= $TarifTBL WHERE ID = $ID_activeTBL" ;
mysqli_query($conn,$Query_updateTABL);
header('Location: departments.php');

ob_end_flush();

}


if (isset($_POST["delet_activite"])) {

 $ID_activiteTBL = $_POST["delet_activite"];

 $Query_activitesSPYS = "SELECT inscription_niveau_activites.Tarif,inscription_niveau_activites.Annee_scolaire,activites.Descriptions,activites.ID as IDactivy FROM inscription_niveau_activites join activites on activites.ID = inscription_niveau_activites.activ_ID WHERE inscription_niveau_activites.ID = $ID_activiteTBL";
    $result_activitesSPYS = mysqli_query($conn,$Query_activitesSPYS);
    $row = mysqli_fetch_assoc($result_activitesSPYS);
    $Descriptionsspy = $row['Descriptions'];
    $aneespypy = $row['Annee_scolaire'];
    $Prixspy = $row['Tarif'];
    $IDactivy = $row['IDactivy'];
$Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Supprimer','$dateTIMEnow','activite($Descriptionsspy)','activite ($Descriptionsspy)  a été supprimé  (Annee scolaire: $aneespypy)','$ip_address')";
mysqli_query($conn,$Query_spy);
$Query_updateTABL = "DELETE FROM `inscription_niveau_activites` WHERE ID =  $ID_activiteTBL" ;
mysqli_query($conn,$Query_updateTABL);
$Query_deletTABLpaiement_activite = "DELETE FROM `activites` WHERE `ID` =  $IDactivy" ;
mysqli_query($conn,$Query_deletTABLpaiement_activite);


header('Location: departments.php');

ob_end_flush();

}




//table activites 




?>
 <style>
    .btn-group{
        
    border: 1px solid #495057;

    }
 
 .pb-5, .py-5 { padding-bottom: 1rem!important;} 
.form-control {
  width: 100%;
}
.multiselect-container {
  box-shadow: 0 3px 12px rgba(0,0,0,.175);
  margin: 0;
}
.multiselect-container .checkbox {
  margin: 0;
}
.multiselect-container li {
  margin: 0;
  padding: 0;
  line-height: 0;
}
.multiselect-container li a {
  line-height: 25px;
  margin: 0;
  padding:0 35px;
}
.custom-btn {
  width: 100% !important;
}
.custom-btn .btn, .custom-multi {
  text-align: left;
  width: 100% !important;
}
.dropdown-menu > .active > a:hover {
  color:inherit;
}



</style>
 <title><?php echo "$Titre"; ?> Admin - département</title>

        <div class="col-md-4 border-right">
            <div class="p-3 py-5">
            

<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Ajouter un nouveau Départemen
            </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">

  
            <div id="département">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    
                    <h4 class="text-right" style="text-align: center !important;">Ajouter un nouveau Département   </h4> 
                </div>
                <form method="POST" onsubmit="return confirm ('Êtes-vous sûr?')">
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels">Département</label><input  type="text" class="form-control " placeholder="département..." value="" name="departement" required></div>
                    <div class="col-md-6"><label class="labels">Nom de groupe</label><input  type="text" class="form-control " value="" placeholder="Nom de groupe..." name="groupe" required></div>
                    <div class="col-md-6"><label class="labels">Prix</label><input  type="number" step="0.01" class="form-control " value="" placeholder="Prix" name="prix" required></div>
                </div>

                <div class="mt-5 text-center "><button name="subForm" id="subForm" class="btn btn-primary profile-button disbleNon" type="submit">Ajouter Département</button></div>
                </form>
                </div>
                </div>
                </div>
                </div>
                <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        Ajouter un nouveau Group
    </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
                <div id="group">
                <h4 class="text-center mb-5">Ajouter un nouveau Group   </h4>
                <form method="POST" onsubmit="return confirm ('Êtes-vous sûr?')">
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels">département</label><select class="form-control" name="Annee" >
                        <?php while ($row = mysqli_fetch_assoc($result_niveau)) { $name = $row['Annee'];  $Niveau_ID = $row['ID'] ;  echo "<option value='$Niveau_ID'>$name</option>"; }?>   
                        </select></div>
                    <div class="col-md-6"><label class="labels">Nom de groupe</label><input  type="text" class="form-control " placeholder="Nom de groupe" value="" name="group" required></div>
                </div>

                <div class="mt-5 text-center "><button name="subGoup" id="subForm" class="btn btn-primary profile-button disbleNon" type="submit">Ajouter Group</button></div>
                </form>
                </div>
                </div>
                </div>
                    </div>
                </div>
            <!-- table departement-->
            <br>
            <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Département</th>
                                        <th scope="col">Groupe</th>
                                        <th scope="col">Prix</th>
                                        <th scope="col">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <form method="POST"  onsubmit="return confirm ('Êtes-vous sûr?')">
                                <?php  while ($row = mysqli_fetch_assoc($result_deparetemntsTABL)) {?>


                                
                                    <tr>    
                                        <th scope="row"><?php echo $row["IDoffall"]; ?></th>
                                        
                                        <td> <input type="text" class="no-outline Prenom input" placeholder="Annee" value="<?php echo $row["Annee"]; ?>" disabled style="width: 100%;"></td>
                                        <td> <input type="text" class="no-outline Nom input" placeholder="Groupe" value="<?php echo $row["Nom"]; ?>" disabled style="width: 100%;"> </td>
                                        <td> <input type="text" class="no-outline Email input" placeholder="Price" value="<?php echo $row["Price"];?>" disabled style="width: 120%;">  </td>
                                        <td style="width: 31%;">
                                            <!-- Call to action buttons -->
                                            <ul class="list-inline m-0">
                                                
                                                <li class="list-inline-item mb-1">
                                                    <button class="btn btn-success btn-sm rounded-0 edit" type="button" data-toggle="tooltip" data-placement="top" value="<?php echo $row["IDoffall"]; ?>"  title="Edit"><i class="fa fa-edit"></i></button>
                                                </li>
                                                <?php  
                                                $idCHECK = $row["IDoffall"];
                                                $Query_checkACTIVITItable = "SELECT niveau.ID FROM groupe JOIN niveau ON niveau.ID = groupe.niveau_ID 
                                                JOIN inscription_niveau_activites ON niveau.ID = inscription_niveau_activites.nive_ID where groupe.ID = $idCHECK";
                                                $result_checkACTIVITItable = mysqli_query($conn,$Query_checkACTIVITItable);

                                                $Query_checkinscription = "SELECT * FROM `inscription` WHERE `grop_ID` = $idCHECK";
                                                $result_checkinscription = mysqli_query($conn,$Query_checkinscription);
                                                if(mysqli_num_rows($result_checkinscription) >0 ){
                                                    //found
                                                 }else{
                                                    //not found
                                                    if(mysqli_num_rows($result_checkACTIVITItable) === 0 ){
                                                ?>
                                                <li class="list-inline-item mb-1">
                                                    <button class="btn btn-danger btn-sm rounded-0" type="submit" name="delet_deparetement" data-toggle="tooltip" data-placement="top" value="<?php echo $row["IDoffall"]; ?>" title="Delete"><i class="fa fa-trash"></i></button>
                                                </li>
                                                <?php }} ?>
                                            </ul>
                                        </td>
                                    </tr>
                                    
<?php }?>
</form>      
                                </tbody>
                            </table>
                            <br><br>

    </div>

<!-- table departement-->

            </div>
        </div>

        <div class="col-md-5">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience"><span>Ajouter activites</span><span class="border px-3 p-1 add-experience"><i class="fa fa-plus"></i>&nbsp;Ajouter activites</span></div><br>
                <form action="" method="POST" onsubmit="return confirm ('Êtes-vous sûr?')">
               
                <div class="col-md-12"><label class="labels">Descriptions</label><input name="Descriptions" type="text" value="" class="form-control disable" placeholder="enter Descriptions" ></div>
                <div class="col-md-12 mt-2"><label class="labels">Tarif</label><input name="Tarif" type="number" step="0.01"  value="" class="form-control disable" placeholder="enter Tarif" ></div>
                <div class="row mt-2 mt-3">
                <div class="col-md-6"><label class="labels">Date de debut </label>

                <input  type="date" style="width: 77%;" class="form-control " placeholder="département" value="" name="Date_de_debut" required>
                        </div>
                    <div class="col-md-6"><label class="labels">Date de fin</label>
                    
                    <input  type="date" style="width: 77%;" class="form-control " placeholder="département" value="" name="Date_de_fin" required>
                
                        </div>
                </div>

                <div class="row mt-2 mt-3">
                
                    <div class="col-md-6"><label class="labels">Departments</label>
                    
                    <select name="niveu_list[]" type="text" class="multiselect" multiple="multiple" role="multiselect" style="border: 1px solid;">          
              <!-- <option value="0" selected="selected">Photos</option> -->
              <?php  
              
              $Query_allNIVEU = "SELECT ID,Annee FROM niveau";
              $result_allNIVEU = mysqli_query($conn,$Query_allNIVEU);
              
              while ($row_allNIVEU = mysqli_fetch_assoc($result_allNIVEU)) {?>

              <option value="<?php echo $row_allNIVEU["ID"]; ?>"><?php echo $row_allNIVEU["Annee"]; ?></option>

              <?php } ?>
            </select> 
                
                        </div>

                        <div class="col-md-6"><label class="labels">Année scolaire</label>

                <select class="form-control" name="scolaire" aria-label="Default select example">
  
                <option value="<?php echo $Annee_scolaireSQL; ?>"><?php echo $Annee_scolaireSQL; ?></option>

                </select>

                        </div>
            </div>


                </div> 
                <div class=" text-center "><button name="activites" class="btn btn-primary profile-button disbleNon" type="submit">Ajouter Activite</button></div>
                </form>
                <!-- table activites-->
                <br><br>
                <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Classe</th>
                                        <th scope="col">Descriptions</th>
                                        <th scope="col" style="width: 21%;">Date debut</th>
                                        <th scope="col" style="width: 19%;">Date fin</th>
                                        <th scope="col">Prix</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <form method="POST"  onsubmit="return confirm ('Êtes-vous sûr?')">
                                <?php  while ($row = mysqli_fetch_assoc($result_activitesTABL)) {?>


                                
                                    <tr>    
                                        <th scope="row"> <?php echo $row["ID"]; ?></th>
                                        <td> <input style="width: 120%;border: 0;box-shadow: none;" value="<?php echo $row["Annee"]; ?>"disabled> </td>
                                        <td> <input type="text" class="no-outline Prenom input" placeholder="Descriptions" value="<?php echo $row["Descriptions"]; ?>" disabled style="width: 100%;border: 0;box-shadow: none;"></td>
                                        <td> <input type="text" class="no-outline Date_de_debut input" placeholder="Nom" value="<?php echo $row["Date_de_debut"]; ?>" disabled style="width: 100%;border: 0;box-shadow: none;"> </td>
                                        <td> <input type="text" class="no-outline Nom input" placeholder="Nom" value="<?php echo $row["Date_de_fin"]; ?>" disabled style="width: 100%;border: 0;box-shadow: none;"> </td>
                                        <td> <input type="text" class="no-outline Email input" placeholder="Tarif" value="<?php echo $row["Tarif"];?>" disabled style="width: 120%;border: 0;box-shadow: none;">  </td>
                                        <td style="width: 31%;">
                                            <!-- Call to action buttons -->
                                            <ul class="list-inline m-0">
                                                
                                                <li class="list-inline-item mb-1">
                                                    <button class="btn btn-success btn-sm rounded-0 editacititi" type="button" data-toggle="tooltip" data-placement="top" value="<?php echo $row["ID"]; ?>"  title="Edit"><i class="fa fa-edit"></i></button>
                                                    
                                                </li>
                                                
                                                <?php  
                                                $idCHECK = $row["ID"];
                                                $Query_checkinscription = "SELECT inscription_niveau_activites.ID FROM inscription_niveau_activites JOIN inscription_activites ON inscription_activites.niv_acti_ID = inscription_niveau_activites.ID WHERE inscription_niveau_activites.ID = $idCHECK";
                                                $result_checkinscription = mysqli_query($conn,$Query_checkinscription);
                                                if(mysqli_num_rows($result_checkinscription) >0){
                                                    //found
                                                 }else{
                                                    //not found

                                                ?>
                                                <li class="list-inline-item mb-1">
                                                    <button class="btn btn-danger btn-sm rounded-0" type="submit" name="delet_activite" data-toggle="tooltip" data-placement="top" value="<?php echo $row["ID"]; ?>" title="Delete"><i class="fa fa-trash"></i></button>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </td>
                                    </tr>
                                    
<?php }?>
</form>      
                                </tbody>
                            </table>

                            <?php
                            
 $Query_activitesSPYS = "SELECT Annee_scolaire FROM inscription_niveau_activites WHERE Annee_scolaire = '$Annee_scolaireSQL'";
       $result_activitesSPYS = mysqli_query($conn,$Query_activitesSPYS);
           $row = mysqli_fetch_assoc($result_activitesSPYS);

           $Query_activitesSPYS = "SELECT * FROM activites";
           $result_activitesSPYS = mysqli_query($conn,$Query_activitesSPYS);
               $row2 = mysqli_fetch_assoc($result_activitesSPYS);

                if ($row!="")
                {}
                else
                {
                    if ($row2!="") {
                        echo '<form action="" method="POST" onsubmit="return confirm ("Êtes-vous sûr?")"> 
                        
                        <div class=" text-center mt-5">
                        <input name="begin" type="number" class="yearchange" placeholder="YYYY" min="2020" max="2100" id="fromthis" required> - <input id="tothis" name="end" class="mb-3 yearchange" type="number" placeholder="YYYY" min="2017" max="2100" required><br>
                        <button name="returnALL" class="btn btn-primary profile-button disbleNon" type="submit">Renvoie toutes les activités de année <span id="annescoler"> </span>
                        </button>
                        </div>
                         </form>';

                            if (isset($_POST["returnALL"])) {
                              $begin = $_POST["begin"];
                              $end = $_POST["end"];

                                $Query_activitesreturnALL = "SELECT * FROM inscription_niveau_activites WHERE Annee_scolaire = '$begin-$end'";
                                    $result_activitesreturnALL = mysqli_query($conn,$Query_activitesreturnALL); 

                                while ($row = mysqli_fetch_assoc($result_activitesreturnALL)) {
                                    $nive_ID = $row["nive_ID"];
                                    $activ_ID = $row["activ_ID"];
                                    $Tarif = $row["Tarif"];
                                    
                                    $Query_inscription_niveau_activites_newyears = "INSERT INTO `inscription_niveau_activites`( `nive_ID`, `activ_ID`, `Tarif`, `Annee_scolaire`)
                                                             VALUES ($nive_ID,$activ_ID,$Tarif,'$Annee_scolaireSQL')";
                                    mysqli_query($conn, $Query_inscription_niveau_activites_newyears);

                                        }
                                        header('Location: departments.php');

                                        ob_end_flush();
                            }

                    }
                }

                            ?> 
                            <br><br>

    </div>
                <!-- table activites-->
                <br><br>
            </div>
        </div>





<?php  include("../include/footer.php"); }else  if ( !isset($_SESSION['login'])) {
            header('Location: login.php');

         
        }?>
      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

   <script>



$('.yearchange').on('change', function() {
    fromthis = $("#fromthis").val()
    tothis = $("#tothis").val()
    $("#annescoler").text(fromthis + "-"+tothis)
});

    /**
 * bootstrap-multiselect.js
 * https://github.com/davidstutz/bootstrap-multiselect
 *
 * Copyright 2012, 2013 David Stutz
 * 
 * Dual licensed under the BSD-3-Clause and the Apache License, Version 2.0.
 */
!function($) {
    
    "use strict";// jshint ;_;

    if (typeof ko != 'undefined' && ko.bindingHandlers && !ko.bindingHandlers.multiselect) {
        ko.bindingHandlers.multiselect = {
            init : function(element, valueAccessor, allBindingsAccessor, viewModel, bindingContext) {},
            update : function(element, valueAccessor, allBindingsAccessor, viewModel, bindingContext) {
                var ms = $(element).data('multiselect');
                if (!ms) {
                    $(element).multiselect(ko.utils.unwrapObservable(valueAccessor()));
                }
                else if (allBindingsAccessor().options && allBindingsAccessor().options().length !== ms.originalOptions.length) {
                    ms.updateOriginalOptions();
                    $(element).multiselect('rebuild');
                }
            }
        };
    }

    function Multiselect(select, options) {

        this.options = this.mergeOptions(options);
        this.$select = $(select);
        
        // Initialization.
        // We have to clone to create a new reference.
        this.originalOptions = this.$select.clone()[0].options;
        this.query = '';
        this.searchTimeout = null;
        
        this.options.multiple = this.$select.attr('multiple') == "multiple";
        this.options.onChange = $.proxy(this.options.onChange, this);
        
        // Build select all if enabled.
        this.buildContainer();
        this.buildButton();
        this.buildSelectAll();
        this.buildDropdown();
        this.buildDropdownOptions();
        this.buildFilter();
        this.updateButtonText();

        this.$select.hide().after(this.$container);
    };

    Multiselect.prototype = {
        
        // Default options.
        defaults: {
            // Default text function will either print 'None selected' in case no
            // option is selected, or a list of the selected options up to a length of 3 selected options.
            // If more than 3 options are selected, the number of selected options is printed.
            buttonText: function(options, select) {
                if (options.length == 0) {
                    return this.nonSelectedText + ' <b class="caret"></b>';
                }
                else {
                    
                    if (options.length > 5) {
                        return options.length + ' ' + this.nSelectedText + ' <b class="caret"></b>';
                    }
                    else {
                        var selected = '';
                        options.each(function() {
                            var label = ($(this).attr('label') !== undefined) ? $(this).attr('label') : $(this).html();
                            
                            //Hack by Victor Valencia R.
                            if($(select).hasClass('multiselect-icon')){
                                var icon = $(this).data('icon');
                                label = '<span class="glyphicon ' + icon + '"></span> ' + label;
                            }
                            
                            selected += label + ', ';
                        });
                        return selected.substr(0, selected.length - 2) + ' <b class="caret"></b>';
                    }
                }
            },
            // Like the buttonText option to update the title of the button.
            buttonTitle: function(options, select) {
                if (options.length == 0) {
                    return this.nonSelectedText;
                }
                else {
                    var selected = '';
                    options.each(function () {
                        selected += $(this).text() + ', ';
                    });
                    return selected.substr(0, selected.length - 2);
                }
            },
            // Is triggered on change of the selected options.
            onChange : function(option, checked) {

            },
            buttonClass: 'btn',
            dropRight: false,
            selectedClass: 'active',
            buttonWidth: 'auto',
            buttonContainer: '<div class="btn-group custom-btn" />',
            // Maximum height of the dropdown menu.
            // If maximum height is exceeded a scrollbar will be displayed.
            maxHeight: false,
            includeSelectAllOption: false,
            selectAllText: ' Select all',
            selectAllValue: 'multiselect-all',
            enableFiltering: false,
            enableCaseInsensitiveFiltering: false,
            filterPlaceholder: 'Search',
            // possible options: 'text', 'value', 'both'
            filterBehavior: 'text',
            preventInputChangeEvent: false,        
            nonSelectedText: 'None selected',            
            nSelectedText: 'selected'
        },
        
        // Templates.
        templates: {
            button: '<button type="button" class="multiselect dropdown-toggle form-control" data-toggle="dropdown"></button>',
            ul: '<ul class="multiselect-container dropdown-menu custom-multi"></ul>',
            filter: '<div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span><input class="form-control multiselect-search" type="text"></div>',
            li: '<li><a href="javascript:void(0);"><label></label></a></li>',
            liGroup: '<li><label class="multiselect-group"></label></li>'
        },
        
        constructor: Multiselect,
        
        buildContainer: function() {
            this.$container = $(this.options.buttonContainer);
        },
        
        buildButton: function() {
            // Build button.
            this.$button = $(this.templates.button).addClass(this.options.buttonClass);
            
            // Adopt active state.
            if (this.$select.prop('disabled')) {
                this.disable();
            }
            else {
                this.enable();
            }
           
            // Manually add button width if set.
            if (this.options.buttonWidth) {
                this.$button.css({
                    'width' : this.options.buttonWidth
                });
            }

            // Keep the tab index from the select.
            var tabindex = this.$select.attr('tabindex');
            if (tabindex) {
                this.$button.attr('tabindex', tabindex);
            }
           
            this.$container.prepend(this.$button)
        },
        
        // Build dropdown container ul.
        buildDropdown: function() {
            
            // Build ul.
            this.$ul = $(this.templates.ul);
            
            if (this.options.dropRight) {
                this.$ul.addClass('pull-right');
            }
            
            // Set max height of dropdown menu to activate auto scrollbar.
            if (this.options.maxHeight) {
                // TODO: Add a class for this option to move the css declarations.
                this.$ul.css({
                    'max-height': this.options.maxHeight + 'px',
                    'overflow-y': 'auto',
                    'overflow-x': 'hidden'
                });
            }
            
            this.$container.append(this.$ul)
        },
        
        // Build the dropdown and bind event handling.
        buildDropdownOptions: function() {
            
            this.$select.children().each($.proxy(function(index, element) {
                // Support optgroups and options without a group simultaneously.
                var tag = $(element).prop('tagName').toLowerCase();
                if (tag == 'optgroup') {
                    this.createOptgroup(element);
                }
                else if (tag == 'option') {
                    this.createOptionValue(element);
                }
                // Other illegal tags will be ignored.
            }, this));

            // Bind the change event on the dropdown elements.
            $('li input', this.$ul).on('change', $.proxy(function(event) {
                var checked = $(event.target).prop('checked') || false;
                var isSelectAllOption = $(event.target).val() == this.options.selectAllValue;

                // Apply or unapply the configured selected class.
                if (this.options.selectedClass) {
                    if (checked) {
                        $(event.target).parents('li').addClass(this.options.selectedClass);
                    }
                    else {
                        $(event.target).parents('li').removeClass(this.options.selectedClass);
                    }
                }
                
                // Get the corresponding option.
                var value = $(event.target).val();
                var $option = this.getOptionByValue(value);

                var $optionsNotThis = $('option', this.$select).not($option);
                var $checkboxesNotThis = $('input', this.$container).not($(event.target));

                // Toggle all options if the select all option was changed.
                if (isSelectAllOption) {
                    $checkboxesNotThis.filter(function() {
                        return $(this).is(':checked') != checked;
                    }).trigger('click');
                }

                if (checked) {
                    $option.prop('selected', true);

                    if (this.options.multiple) {
                        // Simply select additional option.
                        $option.prop('selected', true);
                    }
                    else {
                        // Unselect all other options and corresponding checkboxes.
                        if (this.options.selectedClass) {
                            $($checkboxesNotThis).parents('li').removeClass(this.options.selectedClass);
                        }

                        $($checkboxesNotThis).prop('checked', false);
                        $optionsNotThis.prop('selected', false);

                        // It's a single selection, so close.
                        this.$button.click();
                    }

                    if (this.options.selectedClass == "active") {
                        $optionsNotThis.parents("a").css("outline", "");
                    }
                }
                else {
                    // Unselect option.
                    $option.prop('selected', false);
                }

                this.updateButtonText();
                this.$select.change();
                this.options.onChange($option, checked);
                
                if(this.options.preventInputChangeEvent) {
                    return false;
                }
            }, this));

            $('li a', this.$ul).on('touchstart click', function(event) {
                event.stopPropagation();
                $(event.target).blur();
            });

            // Keyboard support.
            this.$container.on('keydown', $.proxy(function(event) {
                if ($('input[type="text"]', this.$container).is(':focus')) {
                    return;
                }
                if ((event.keyCode == 9 || event.keyCode == 27) && this.$container.hasClass('open')) {
                    // Close on tab or escape.
                    this.$button.click();
                }
                else {
                    var $items = $(this.$container).find("li:not(.divider):visible a");

                    if (!$items.length) {
                        return;
                    }

                    var index = $items.index($items.filter(':focus'));

                    // Navigation up.
                    if (event.keyCode == 38 && index > 0) {
                        index--;
                    }
                    // Navigate down.
                    else if (event.keyCode == 40 && index < $items.length - 1) {
                        index++;
                    }
                    else if (!~index) {
                        index = 0;
                    }

                    var $current = $items.eq(index);
                    $current.focus();

                    if (event.keyCode == 32 || event.keyCode == 13) {
                        var $checkbox = $current.find('input');

                        $checkbox.prop("checked", !$checkbox.prop("checked"));
                        $checkbox.change();
                    }

                    event.stopPropagation();
                    event.preventDefault();
                }
            }, this));
        },
        
        // Will build an dropdown element for the given option.
        createOptionValue: function(element) {
            if ($(element).is(':selected')) {
                $(element).prop('selected', true);
            }

            // Support the label attribute on options.
            var label = $(element).attr('label') || $(element).html();            
            var value = $(element).val();
                        
            //Hack by Victor Valencia R.            
            if($(element).parent().hasClass('multiselect-icon') || $(element).parent().parent().hasClass('multiselect-icon')){                                
                var icon = $(element).data('icon');
                label = '<span class="glyphicon ' + icon + '"></span> ' + label;
            } 
            
            var inputType = this.options.multiple ? "checkbox" : "radio";

            var $li = $(this.templates.li);
            $('label', $li).addClass(inputType);
            $('label', $li).append('<input type="' + inputType + '" />');
            
            //Hack by Victor Valencia R.
            if(($(element).parent().hasClass('multiselect-icon') || $(element).parent().parent().hasClass('multiselect-icon')) && inputType == 'radio'){                
                $('label', $li).css('padding-left', '0px');
                $('label', $li).find('input').css('display', 'none');
            }

            var selected = $(element).prop('selected') || false;
            var $checkbox = $('input', $li);
            $checkbox.val(value);

            if (value == this.options.selectAllValue) {
                $checkbox.parent().parent().addClass('multiselect-all');
            }

            $('label', $li).append(" " + label);

            this.$ul.append($li);

            if ($(element).is(':disabled')) {
                $checkbox.attr('disabled', 'disabled').prop('disabled', true).parents('li').addClass('disabled');
            }

            $checkbox.prop('checked', selected);

            if (selected && this.options.selectedClass) {
                $checkbox.parents('li').addClass(this.options.selectedClass);
            }
        },

        // Create optgroup.
        createOptgroup: function(group) {
            var groupName = $(group).prop('label');

            // Add a header for the group.
            var $li = $(this.templates.liGroup);
            $('label', $li).text(groupName);
            
            //Hack by Victor Valencia R.
            $li.addClass('text-primary');
            
            this.$ul.append($li);
            
            // Add the options of the group.
            $('option', group).each($.proxy(function(index, element) {                
                this.createOptionValue(element);
            }, this));
        },
        
        // Add the select all option to the select.
        buildSelectAll: function() {
            var alreadyHasSelectAll = this.$select[0][0] ? this.$select[0][0].value == this.options.selectAllValue : false;
            // If options.includeSelectAllOption === true, add the include all checkbox.
            if (this.options.includeSelectAllOption && this.options.multiple && !alreadyHasSelectAll) {
                this.$select.prepend('<option value="' + this.options.selectAllValue + '">' + this.options.selectAllText + '</option>');
            }
        },
        
        // Build and bind filter.
        buildFilter: function() {
            
            // Build filter if filtering OR case insensitive filtering is enabled and the number of options exceeds (or equals) enableFilterLength.
            if (this.options.enableFiltering || this.options.enableCaseInsensitiveFiltering) {
                var enableFilterLength = Math.max(this.options.enableFiltering, this.options.enableCaseInsensitiveFiltering);
                if (this.$select.find('option').length >= enableFilterLength) {
                    
                    this.$filter = $(this.templates.filter);
                    $('input', this.$filter).attr('placeholder', this.options.filterPlaceholder);
                    this.$ul.prepend(this.$filter);

                    this.$filter.val(this.query).on('click', function(event) {
                        event.stopPropagation();
                    }).on('keydown', $.proxy(function(event) {
                        // This is useful to catch "keydown" events after the browser has updated the control.
                        clearTimeout(this.searchTimeout);

                        this.searchTimeout = this.asyncFunction($.proxy(function() {

                            if (this.query != event.target.value) {
                                this.query = event.target.value;

                                $.each($('li', this.$ul), $.proxy(function(index, element) {
                                    var value = $('input', element).val();
                                    if (value != this.options.selectAllValue) {
                                        var text = $('label', element).text();
                                        var value = $('input', element).val();
                                        if (value && text && value != this.options.selectAllValue) {
                                            // by default lets assume that element is not
                                            // interesting for this search
                                            var showElement = false;

                                            var filterCandidate = '';
                                            if ((this.options.filterBehavior == 'text' || this.options.filterBehavior == 'both')) {
                                                filterCandidate = text;
                                            }
                                            if ((this.options.filterBehavior == 'value' || this.options.filterBehavior == 'both')) {
                                                filterCandidate = value;
                                            }

                                            if (this.options.enableCaseInsensitiveFiltering && filterCandidate.toLowerCase().indexOf(this.query.toLowerCase()) > -1) {
                                                showElement = true;
                                            }
                                            else if (filterCandidate.indexOf(this.query) > -1) {
                                                showElement = true;
                                            }

                                            if (showElement) {
                                                $(element).show();
                                            }
                                            else {
                                                $(element).hide();
                                            }
                                        }
                                    }
                                }, this));
                            }
                        }, this), 300, this);
                    }, this));
                }
            }
        },

        // Destroy - unbind - the plugin.
        destroy: function() {
            this.$container.remove();
            this.$select.show();
        },

        // Refreshs the checked options based on the current state of the select.
        refresh: function() {
            $('option', this.$select).each($.proxy(function(index, element) {
                var $input = $('li input', this.$ul).filter(function() {
                    return $(this).val() == $(element).val();
                });

                if ($(element).is(':selected')) {
                    $input.prop('checked', true);

                    if (this.options.selectedClass) {
                        $input.parents('li').addClass(this.options.selectedClass);
                    }
                }
                else {
                    $input.prop('checked', false);

                    if (this.options.selectedClass) {
                        $input.parents('li').removeClass(this.options.selectedClass);
                    }
                }

                if ($(element).is(":disabled")) {
                    $input.attr('disabled', 'disabled').prop('disabled', true).parents('li').addClass('disabled');
                }
                else {
                    $input.prop('disabled', false).parents('li').removeClass('disabled');
                }
            }, this));

            this.updateButtonText();
        },

        // Select an option by its value or multiple options using an array of values.
        select: function(selectValues) {
            if(selectValues && !$.isArray(selectValues)) {
                selectValues = [selectValues];
            }
            
            for (var i = 0; i < selectValues.length; i++) {
                
                var value = selectValues[i];
                
                var $option = this.getOptionByValue(value);
                var $checkbox = this.getInputByValue(value);

                if (this.options.selectedClass) {
                    $checkbox.parents('li').addClass(this.options.selectedClass);
                }

                $checkbox.prop('checked', true);
                $option.prop('selected', true);                
                this.options.onChange($option, true);
            }

            this.updateButtonText();
        },

        // Deselect an option by its value or using an array of values.
        deselect: function(deselectValues) {
            if(deselectValues && !$.isArray(deselectValues)) {
                deselectValues = [deselectValues];
            }

            for (var i = 0; i < deselectValues.length; i++) {
                
                var value = deselectValues[i];
                
                var $option = this.getOptionByValue(value);
                var $checkbox = this.getInputByValue(value);

                if (this.options.selectedClass) {
                    $checkbox.parents('li').removeClass(this.options.selectedClass);
                }

                $checkbox.prop('checked', false);
                $option.prop('selected', false);               
                this.options.onChange($option, false);
            }

            this.updateButtonText();
        },

        // Rebuild the whole dropdown menu.
        rebuild: function() {
            this.$ul.html('');
            
            // Remove select all option in select.
            $('option[value="' + this.options.selectAllValue + '"]', this.$select).remove();
            
            // Important to distinguish between radios and checkboxes.
            this.options.multiple = this.$select.attr('multiple') == "multiple";
            
            this.buildSelectAll();
            this.buildDropdownOptions();
            this.updateButtonText();
            this.buildFilter();
        },
        
        // Build select using the given data as options.
        dataprovider: function(dataprovider) {
            var optionDOM = "";
            dataprovider.forEach(function (option) {
                optionDOM += '<option value="' + option.value + '">' + option.label + '</option>';
            });

            this.$select.html(optionDOM);
            this.rebuild();
        },

        // Enable button.
        enable: function() {
            this.$select.prop('disabled', false);
            this.$button.prop('disabled', false)
                .removeClass('disabled');
        },

        // Disable button.
        disable: function() {
            this.$select.prop('disabled', true);
            this.$button.prop('disabled', true)
                .addClass('disabled');
        },

        // Set options.
        setOptions: function(options) {
            this.options = this.mergeOptions(options);
        },

        // Get options by merging defaults and given options.
        mergeOptions: function(options) {
            return $.extend({}, this.defaults, options);
        },
        
        // Update button text and button title.
        updateButtonText: function() {
            var options = this.getSelected();
            
            // First update the displayed button text.
            $('button', this.$container).html(this.options.buttonText(options, this.$select));            
            
            // Now update the title attribute of the button.
            $('button', this.$container).attr('title', this.options.buttonTitle(options, this.$select));
            
        },

        // Get all selected options.
        getSelected: function() {
            return $('option[value!="' + this.options.selectAllValue + '"]:selected', this.$select).filter(function() {
                return $(this).prop('selected');
            });
        },
        
        // Get the corresponding option by ts value.
        getOptionByValue: function(value) {
            return $('option', this.$select).filter(function() {
                return $(this).val() == value;
            });
        },
        
        // Get an input in the dropdown by its value.
        getInputByValue: function(value) {
            return $('li input', this.$ul).filter(function() {
                return $(this).val() == value;
            });
        },
        
        updateOriginalOptions: function() {
            this.originalOptions = this.$select.clone()[0].options;
        },

        asyncFunction: function(callback, timeout, self) {
            var args = Array.prototype.slice.call(arguments, 3);
            return setTimeout(function() {
                callback.apply(self || window, args);
            }, timeout);
        }
    };

    $.fn.multiselect = function(option, parameter) {
        return this.each(function() {
            var data = $(this).data('multiselect'), options = typeof option == 'object' && option;

            // Initialize the multiselect.
            if (!data) {
                $(this).data('multiselect', ( data = new Multiselect(this, options)));
            }

            // Call multiselect method.
            if ( typeof option == 'string') {
                data[option](parameter);
            }
        });
    };

    $.fn.multiselect.Constructor = Multiselect;
    
    // Automatically init selects by their data-role.
    $(function() {
        $("select[role='multiselect']").multiselect();
    });

}(window.jQuery);

        $(document).ready(function () {
            
            $( ".edit" ).click(function() {
                var name = $(this).attr("name");

if (name =="update_deparetement") {

 
}else{

                $(".input").removeAttr('name');
                $(".input").prop("disabled", true).css("background-color", "#f2f2f2");
                $(".edit").removeAttr('name');
                $(".edit").attr("type", "button");
                $(".edit").find(">:first-child").attr('class', 'fa fa-edit');

}
                
               
                $tag = $(this).parent().parent().parent().parent() ;
                $tag.find(":input[type=text]").prop("disabled", false).css("background-color", "white");
                    $(this).find(">:first-child").attr('class', 'fa fa-check');
                    $tag.find(".Prenom").attr('name', 'DépartementTBL');
                    $tag.find(".Nom").attr('name', 'GroupeTBL');
                    $tag.find(".Email").attr('name', 'PrixTBL');
                    $targertyhis = $(this)
                    setTimeout(
  function() 
  {
    $targertyhis.attr('name', 'update_deparetement');
    $targertyhis.attr("type", "submit");
                  }, 500);
                    
                    



           
    $('#example').DataTable();
});



$( ".editacititi" ).click(function() {
                var name = $(this).attr("name");

if (name =="update_active") {

 
}else{

                $(".input").removeAttr('name');
                $(".input").prop("disabled", true).css("background-color", "#f2f2f2");
                $(".editacititi").removeAttr('name');
                $(".editacititi").attr("type", "button");
                $(".Nom").attr("type", "text");
                $(".Date_de_debut").attr("type", "text");
                $(".editacititi").find(">:first-child").attr('class', 'fa fa-edit');

}
                
               
                $tag = $(this).parent().parent().parent().parent() ;
                $tag.find(":input[type=text]").prop("disabled", false).css("background-color", "white");
                    $(this).find(">:first-child").attr('class', 'fa fa-check');
                    $tag.find(".Prenom").attr('name', 'DescriptionsTBL');
                    $tag.find(".Date_de_debut").attr('name', 'Date_de_debut');
                    $tag.find(".Nom").attr('name', 'Date_de_finTBL');
                    $tag.find(".Nom").attr('type', 'date');
                    $tag.find(".Date_de_debut").attr('type', 'date');
                    $tag.find(".Email").attr('name', 'TarifTBL');
                    $targertyhis = $(this)
                    setTimeout(
                        function() 
  {
    $targertyhis.attr('name', 'update_active');
    $targertyhis.attr("type", "submit");
                  }, 500);
                    
                    
});


           
    $('#example').DataTable();
});

$(window).on("load",function(){
     $(".loader-wrapper").fadeOut("slow");
});
    </script>