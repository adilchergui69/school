
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

// if ( !isset($_SESSION['login'])) {
//     header('Location: login.php');
// }
if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Director" || isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {

include("../Connect/cnx.php");

$dateTIMEnow = date("Y-m-d H:i:s");
$nameUSER = $_SESSION['login'][2];
$prenomeUSER =  $_SESSION['login'][3];

ob_start();
$ID_student = $_GET['id'];

$Query_description_class = "SELECT inscription.ID AS inscription_ID , etudiant.ID as etudiants_id,etudiant.Prenom,etudiant.Nom,etudiant.Photo,etudiant.Langues_parlees_par_les_parents,inscription.Etu_ID,inscription.grop_ID,groupe.ID,groupe.Niveau_ID,groupe.Nom AS nomgroup,niveau.ID AS niveau_ID ,niveau.Annee FROM etudiant JOIN inscription ON etudiant.ID = inscription.Etu_ID JOIN groupe ON groupe.ID = inscription.grop_ID JOIN niveau ON niveau.ID = groupe.Niveau_ID WHERE etudiant.ID=$ID_student";
  $result_Query_description_class = mysqli_query($conn,$Query_description_class);
  $row_result_Query_description_class = mysqli_fetch_assoc($result_Query_description_class);

  $ID_inscription = $row_result_Query_description_class["inscription_ID"];
  $id_niveusqrdrt = $row_result_Query_description_class["niveau_ID"];
// table etudiant
$alert = "";
$Query = "SELECT * FROM etudiant WHERE ID=$ID_student;";

$result = mysqli_query($conn,$Query);

$row = mysqli_fetch_assoc($result);

$PrenomstudentFORspy = $row['Prenom'];
 $namestudentFORspy = $row['Nom'];
///mather
$Query_join_Mather = "SELECT etudiant_parent_tuteur.Etudiant_ID, parent_tuteur.Cin, parent_tuteur.ID, parent_tuteur.Nom, parent_tuteur.Prenom, parent_tuteur.Tel_personnel, parent_tuteur.Tel_travail
FROM etudiant_parent_tuteur
INNER JOIN parent_tuteur ON etudiant_parent_tuteur.Mather_id=parent_tuteur.ID WHERE etudiant_parent_tuteur.Etudiant_ID=$ID_student";

$result_join_Mather = mysqli_query($conn,$Query_join_Mather);

//father

$Query_join_Father = "SELECT etudiant_parent_tuteur.Etudiant_ID, parent_tuteur.Cin, parent_tuteur.ID, parent_tuteur.Nom, parent_tuteur.Prenom, parent_tuteur.Tel_personnel, parent_tuteur.Tel_travail
FROM etudiant_parent_tuteur
INNER JOIN parent_tuteur ON etudiant_parent_tuteur.Father_id=parent_tuteur.ID WHERE etudiant_parent_tuteur.Etudiant_ID=$ID_student";

$result_join_Father = mysqli_query($conn,$Query_join_Father);


///responable

$Query_join_Responsible = "SELECT etudiant_parent_tuteur.Etudiant_ID,  parent_tuteur.ID,parent_tuteur.Cin,  parent_tuteur.Nom, parent_tuteur.Prenom, parent_tuteur.Tel_personnel, parent_tuteur.Tel_travail
FROM etudiant_parent_tuteur
INNER JOIN parent_tuteur ON etudiant_parent_tuteur.Parent_Tuteur_ID=parent_tuteur.ID WHERE etudiant_parent_tuteur.Etudiant_ID=$ID_student";

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
$Problem = [];

if ($row['Problemes_de_vue'] ==1) {array_push($Problem,"Problemes de vue");}
if ($row['Problemes_d_ouie'] ==1) {array_push($Problem,"Problemes d'ouie");}
if ($row['Problemes_de_prononciation'] ==1) { array_push($Problem,"Problemes de prononciation");}
if ($row['Asthme'] ==1) {array_push($Problem,"Asthme");}
if ($row['Diabete'] ==1) {array_push($Problem,"Diabete");}
if ($row['Insuffisance_cardiaque'] ==1) {array_push($Problem,"Insuffisance cardiaque");}
if ($row['Nervosite'] ==1) {array_push($Problem,"Nervosite");}
if (!$row['Autres_maladies'] =="") {array_push($Problem,$row['Autres_maladies']);}
//End test for if have any problem

// print_r($Problem);



include("../include/nav.php");
if (isset($_POST["update"])) {


    $Prenom = $_POST["Prenom"];
    $Nom = $_POST["Nom"];
    $Date_de_naissance = $_POST["Date_de_naissance"];
    $Code_MASSAR = $_POST["Code_MASSAR"];
    $Vit_avec = $_POST["Vit_avec"];
    $Nombre_de_freres_et_sœurs = $_POST["Nombre_de_freres_et_sœurs"];
    $Langues_parlees_par_les_parents = $_POST["Langues_parlees_par_les_parents"];
    $Adresse_de_la_clinique = $_POST["Adresse_de_la_clinique"];
    $email = $_POST["email"];
    
    $Problemes_de_vue = $_POST["Problemes_de_vue"];
    $Problemes_d_ouie = $_POST["Problemes_d_ouie"];
    $Problemes_de_prononciation = $_POST["Problemes_de_prononciation"];
    $Asthme = $_POST["Asthme"];
    $Diabete = $_POST["Diabete"];
    $Insuffisance_cardiaque = $_POST["Insuffisance_cardiaque"];
    $Nervosite = $_POST["Nervosite"];

    $Autres_maladies = $_POST["Autres_maladies"];
    if (!$row['Photo'] == "" || !$row['Photo'] == null) {
        $fileIMGE = $row['Photo'];
    }else{
        $fileIMGE = null;

    }
    if(file_exists($_FILES['fileToUpload']['tmp_name'])) {
    

        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        $fileIMGE = $_FILES["fileToUpload"]["name"];
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
if($check !== false) {
// echo "File is an image - " . $check["mime"] . ".";
$uploadOk = 1;
} else {
// echo "File is not an image.";
$alert = "<div class='alert alert-danger mt-5' role='alert'>
Le fichier n'est pas une image.</div>";
$uploadOk = 0;
}

if (file_exists($target_file)) {

    $alert = "<div class='alert alert-danger mt-5' role='alert'>
    Désolé, le fichier existe déjà.</div>";
    $uploadOk = 0;
    }
    
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 300000) {
    //   echo "Sorry, your file is too large.";
    $alert = "<div class='alert alert-danger mt-5' role='alert'>
    Désolé, votre fichier est trop volumineux.</div>";
    $uploadOk = 0;
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    $alert = "<div class='alert alert-danger mt-5' role='alert'>
    Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.</div>";
    //   echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
    $alert = "<div class='alert alert-danger mt-5' role='alert'>
    Désolé, votre fichier n'a pas été téléchargé.</div>";
    //   echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    // echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
    $alert = '<div class="alert alert-success mt-5" role="alert">
    Le fichier '. htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " a été téléchargé.</div>";
    } else {
    // echo "Sorry, there was an error uploading your file.";
    $alert = "<div class='alert alert-danger mt-5' role='alert'>
    Désolé, une erreur s'est produite lors du téléchargement de votre fichier.</div>";
    }
    }
}

// Check if file already exists



    if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin") {
    $Remise = $_POST["remise"];

    $Query_join_Father = "UPDATE `etudiant` SET `Autres_maladies`='$Autres_maladies', `Prenom`='$Prenom',`Nom`='$Nom',`Date_de_naissance`='$Date_de_naissance',`Code_MASSAR`='$Code_MASSAR',`Vit_avec`='$Vit_avec',`Nombre_de_freres_et_sœurs`='$Nombre_de_freres_et_sœurs',`Langues_parlees_par_les_parents`='$Langues_parlees_par_les_parents',`Problemes_de_vue`='$Problemes_de_vue',`Problemes_d_ouie`='$Problemes_d_ouie',`Problemes_de_prononciation`='$Problemes_de_prononciation',`Asthme`='$Asthme',`Diabete`='$Diabete',`Insuffisance_cardiaque`='$Insuffisance_cardiaque',`Nervosite`='$Nervosite',`Adresse_de_la_clinique`='$Adresse_de_la_clinique',`Photo`='$fileIMGE',`email`='$email',`Remise`='$Remise' WHERE `ID`=$ID_student";
    }

    if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Director") {
       
        $Query_join_Father = "UPDATE `etudiant` SET `Autres_maladies`='$Autres_maladies', `Prenom`='$Prenom',`Nom`='$Nom',`Date_de_naissance`='$Date_de_naissance',`Code_MASSAR`='$Code_MASSAR',`Vit_avec`='$Vit_avec',`Nombre_de_freres_et_sœurs`='$Nombre_de_freres_et_sœurs',`Langues_parlees_par_les_parents`='$Langues_parlees_par_les_parents',`Problemes_de_vue`='$Problemes_de_vue',`Problemes_d_ouie`='$Problemes_d_ouie',`Problemes_de_prononciation`='$Problemes_de_prononciation',`Asthme`='$Asthme',`Diabete`='$Diabete',`Insuffisance_cardiaque`='$Insuffisance_cardiaque',`Nervosite`='$Nervosite',`Adresse_de_la_clinique`='$Adresse_de_la_clinique',`Photo`='$fileIMGE',`email`='$email' WHERE `ID`=$ID_student";
        }


       
 mysqli_query($conn,$Query_join_Father);

 $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Mettre à jour','$dateTIMEnow','Étudiant $PrenomstudentFORspy $namestudentFORspy','Une ou plusieurs informations sur étudiant ont changé','$ip_address')";
 mysqli_query($conn,$Query_spy);

 header("Refresh:0");
    
 ob_end_flush();
}

if (isset($_POST["delet"])) {
   
$inscriptionID = "SELECT ID FROM inscription WHERE Etu_ID=$ID_student;";
$resultinscriptionID = mysqli_query($conn,$inscriptionID);

if (mysqli_num_rows($resultinscriptionID) == 0) { 
    $sql1 = "DELETE FROM etudiant_parent_tuteur WHERE Etudiant_ID=$ID_student;";
    mysqli_query($conn, $sql1);
    $sql2 = "DELETE FROM etudiant WHERE ID=$ID_student;";
    mysqli_query($conn, $sql2);

    $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Supprimer','$dateTIMEnow','Étudiant $PrenomstudentFORspy $namestudentFORspy','Étudiant a été Supprimer','$ip_address')";
 mysqli_query($conn,$Query_spy);

    header("Location: ../pages/students.php");
    
    ob_end_flush();



 }else{
  $IDinst = mysqli_fetch_assoc($resultinscriptionID);
  $reaLIDinst = $IDinst["ID"];
    

    $sqlDELETEpaiement = "DELETE FROM paiement WHERE Ins_ID=$reaLIDinst";
    mysqli_query($conn, $sqlDELETEpaiement);
    
  $sqlDELETEpaiement_activite = "DELETE FROM paiement_activite WHERE insc_ID=$reaLIDinst;";
  mysqli_query($conn, $sqlDELETEpaiement_activite);

  $sqlDELETEinscription_activites = "DELETE FROM inscription_activites WHERE inscr_ID=$reaLIDinst;";
  mysqli_query($conn, $sqlDELETEinscription_activites);

    $sqlDELETEetudiant_parent_tuteur = "DELETE FROM etudiant_parent_tuteur WHERE Etudiant_ID=$ID_student;";
    mysqli_query($conn, $sqlDELETEetudiant_parent_tuteur);
    $sqlDELETEetudiant_parent_tuteur = "DELETE FROM inscription WHERE Etu_ID=$ID_student;";
    mysqli_query($conn, $sqlDELETEetudiant_parent_tuteur);
    $sqlDELETEetudiant = "DELETE FROM etudiant WHERE ID=$ID_student;";
    mysqli_query($conn, $sqlDELETEetudiant);

    $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Supprimer','$dateTIMEnow','Étudiant $PrenomstudentFORspy $namestudentFORspy','Étudiant a été Supprimer','$ip_address')";
 mysqli_query($conn,$Query_spy);

header("Location: ../pages/students.php");

    
    
    ob_end_flush();
 }
}

if (isset($_POST["deletMLD"])) {
    
    $mldTEST = $_POST["deletMLD"];
    
    switch ($mldTEST) {
    case "Problemes de vue":
            $deletMALADI = 'Problemes_de_vue';
            header("Refresh:0");
            ob_end_flush();
            break;
        case "Problemes d'ouie":
            $deletMALADI = 'Problemes_d_ouie';
            header("Refresh:0");
            ob_end_flush();
            break;
        case "Problemes de prononciation":
            $deletMALADI = 'Problemes_de_prononciation';
            header("Refresh:0");
            ob_end_flush();
            break;
            case "Asthme":
                $deletMALADI = 'Asthme';
                header("Refresh:0");
                ob_end_flush();
                break;
                case "Diabete":
                    $deletMALADI = 'Diabete';
                    header("Refresh:0");
                    ob_end_flush();
                    break;
                    case "Insuffisance cardiaque":
                        $deletMALADI = 'Insuffisance_cardiaque';
                        header("Refresh:0");
                        ob_end_flush();
                        break;
                        case "Nervosite":
                            $deletMALADI = 'Nervosite';
                            header("Refresh:0");
                            ob_end_flush();
                            break;
                         
    }
    if ($mldTEST == $row['Autres_maladies']) {

        $deletMALADI = 'Autres_maladies';

        $updateMLD = "UPDATE etudiant SET $deletMALADI='' WHERE ID=$ID_student;";
        mysqli_query($conn,$updateMLD);

        $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Supprimer','$dateTIMEnow','Étudiant $PrenomstudentFORspy $namestudentFORspy','La maladie Autres maladies a été Supprimer','$ip_address')";
        mysqli_query($conn,$Query_spy);

    }else{
        $updateMLD = "UPDATE etudiant SET $deletMALADI=0 WHERE ID=$ID_student;";
        mysqli_query($conn,$updateMLD);

        $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Supprimer','$dateTIMEnow','Étudiant $PrenomstudentFORspy $namestudentFORspy','La maladie $deletMALADI a été Supprimer','$ip_address')";
        mysqli_query($conn,$Query_spy);

    }



}

$activites_quryes = "SELECT inscription_activites.ID,inscription_activites.statut,activites.Descriptions from inscription_activites
join inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on activites.ID = inscription_niveau_activites.activ_ID 
where inscription_niveau_activites.Annee_scolaire = '$Annee_scolaireSQL' and inscription_activites.inscr_ID = $ID_inscription  and inscription_niveau_activites.nive_ID = $id_niveusqrdrt";

$result_activites_quryes = mysqli_query($conn,$activites_quryes);
?>

<link rel="stylesheet" href="../css\student.css">

<title><?php echo "$Titre"; ?> Admin - Informations Étudiants</title>
    <!-- MAIN -->
    
    <div class="col">
        
        <!-- <h1>
            
            <small class="text-muted"></small>
        </h1> -->
        
        
<!-- For demo purpose -->
<form  method="POST" enctype="multipart/form-data" onsubmit="return confirm ('Êtes-vous sûr?')">
<?php echo $alert;  ?>
<div class="container">

<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="<?php if ( !$row['Photo']==null|| !$row['Photo']=="" ) {echo "../uploads/". $row['Photo']; }else echo "https://cdn-icons-png.flaticon.com/512/183/183760.png";   ?>">
               <div id="customFile" class="disbleNon" >
               <label class="form-label" for="customFile">changer l'image ?</label>
<input type="file" name="fileToUpload" class="form-control  fileIMAGE" />
               </div>
                
                <span class="font-weight-bold"><?php echo $row['Prenom'] ." ". $row['Nom']. " <br> ( #". $row['ID']." )"; ?></span>
                <?php echo $row_result_Query_description_class["Annee"] . "/" .$row_result_Query_description_class["nomgroup"] ?>

            <span class="text-black-50"><?php echo $row['email']; ?></span>
            <br>
            <div class="card text-white bg-danger mb-3" style="max-width: 18rem;width: 100%;">
            
  <div class="card-header">information de Pére <br> <?php echo $Father_Responsible ?></div> 
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

<?php }?>
            </div>
        </div>
        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
               <h4 class="text-right">Informations étudiants </h4> <span id="Edit"  class="border px-2 p-1 add-experience btn btn-warning profile-button"><i class="fa fa-pencil"></i>&nbsp;Éditer</span>
                </div>
               
                <div class="row mt-2">
                   
                    <div class="col-md-6"><label class="labels">Prenom</label><input name="Prenom"  type="text" class="form-control disable" placeholder="Prenom" value="<?php echo $row['Prenom']; ?>"></div>
                    <div class="col-md-6"><label class="labels">Nom</label><input name="Nom" type="text" class="form-control disable" value="<?php echo $row['Nom']; ?>" placeholder="Nom"></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Date de naissance</label><input name="Date_de_naissance" type="date" value="<?php echo $row['Date_de_naissance']; ?>" class="form-control disable" placeholder="Date de naissance" ></div>
                    <div class="col-md-12"><label class="labels">Code MASSAR</label><input name="Code_MASSAR" type="text" class="form-control disable" placeholder="Code MASSAR" value="<?php echo $row['Code_MASSAR']; ?>"></div>
                    <div class="col-md-12"><label class="labels">Vit avec</label><input name="Vit_avec" type="text" class="form-control disable" placeholder="Vit avec" value="<?php echo $row['Vit_avec']; ?>"></div>
                    <div class="col-md-12"><label class="labels">Nombre de freres et sœurs</label><input name="Nombre_de_freres_et_sœurs" type="number" class="form-control disable" placeholder="Nombre de freres et sœurs" value="<?php echo $row['Nombre_de_freres_et_sœurs']; ?>"></div>
                    <div class="col-md-12"><label class="labels">Langues parlees par les parents</label><input name="Langues_parlees_par_les_parents"  type="text" class="form-control disable" placeholder="Langues" value="<?php echo $row['Langues_parlees_par_les_parents']; ?>"></div>
                    <div class="col-md-12"><label class="labels">Adresse de la clinique</label><input name="Adresse_de_la_clinique" type="text" class="form-control disable" placeholder="Adresse" value="<?php echo $row['Adresse_de_la_clinique']; ?>"></div>
                    <div class="col-md-12"><label class="labels">email</label><input type="text" name="email" class="form-control disable" placeholder="enter email" value="<?php echo $row['email'] ?>"></div>
                    <br>  <label class="labels col-md-12 disbleNon mt-2">Maladies étudiantes</label> <br>
                    
                    





                    <div class="form-check ml-3 disbleNon">
  <input class="form-check-input " name="Problemes_de_vue" type="checkbox" value="<?php echo $row['Problemes_de_vue']; ?>" id="flexCheckDefault1" <?php  if ($row['Problemes_de_vue'] ==1) {echo "checked";} ?>>
  <label class="form-check-label mr-2" for="flexCheckDefault1">
  Problemes de vue  
  </label>
</div>
<div class="form-check ml-3 disbleNon">
  <input class="form-check-input" name="Problemes_d_ouie" type="checkbox" value="<?php echo $row['Problemes_d_ouie']; ?>" id="flexCheckChecked2" <?php  if ($row['Problemes_d_ouie'] ==1) {echo "checked";} ?>>
  <label class="form-check-label mr-2" for="flexCheckChecked2">
  Problemes d'ouie
  </label>
</div>

<div class="form-check ml-3 disbleNon">
  <input class="form-check-input" name="Problemes_de_prononciation" type="checkbox" value="<?php echo $row['Problemes_de_prononciation']; ?>" id="flexCheckChecked3" <?php  if ($row['Problemes_de_prononciation'] ==1) {echo "checked";} ?>>
  <label class="form-check-label mr-2" for="flexCheckChecked3">
  Problemes de prononciation  </label>
</div>
<div class="form-check ml-3 disbleNon">
  <input class="form-check-input" name="Asthme" type="checkbox" value="<?php echo $row['Asthme']; ?>" id="flexCheckChecked4" <?php  if ($row['Asthme'] ==1) {echo "checked";} ?>>
  <label class="form-check-label mr-2" for="flexCheckChecked4">
  Asthme
  </label>
</div>
<div class="form-check ml-3 disbleNon">
  <input class="form-check-input" name="Diabete" type="checkbox" value="<?php echo $row['Diabete']; ?>" id="flexCheckChecked5" <?php  if ($row['Diabete'] ==1) {echo "checked";} ?>>
  <label class="form-check-label mr-2" for="flexCheckChecked5">
  Diabete
  </label>
</div>
<div class="form-check ml-3 disbleNon">
  <input class="form-check-input" name="Insuffisance_cardiaque" type="checkbox" value="<?php echo $row['Insuffisance_cardiaque']; ?>" id="flexCheckChecked6" <?php  if ($row['Insuffisance_cardiaque'] ==1) {echo "checked";} ?>>
  <label class="form-check-label mr-2" for="flexCheckChecked6">
  Insuffisance cardiaque
  </label>
</div>
<div class="form-check ml-3 disbleNon">
  <input class="form-check-input" name="Nervosite" type="checkbox" value="<?php echo $row['Nervosite']; ?>" id="flexCheckChecked7" <?php  if ($row['Nervosite'] ==1) {echo "checked";} ?>>
  <label class="form-check-label mr-2" for="flexCheckChecked7">
  Nervosite
  </label>
</div>

 </div>
<?php 

if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {

?>
                <div class="col-md-12 mt-4"><label class="labels">Remise (DH)</label><input type="number" step="0.01" name="remise" class="form-control disable" placeholder="enter remise..." value="<?php echo $row['Remise'] ?>"></div>
<?php }?>
<br><label class="labels">Activités il participe :</label> <br>
                <?php 
                   while ($row_activites_payement_names = mysqli_fetch_assoc($result_activites_quryes)) {

                    $Descriptions = $row_activites_payement_names["Descriptions"];
                    $ID = $row_activites_payement_names["ID"];

                    echo "<span id='$ID' class='badge badge-pill badge-primary mr-2 mb-2'>$Descriptions</span> ";

                   }
                   $fathersID = $row_Father['ID'] ;
                   $MathersID = $row_Mather['ID'];
                   $fResponsiblesID = $row_Responsible['ID'];  
                   $Query = "SELECT etudiant_parent_tuteur.Parent_Tuteur_ID, etudiant_parent_tuteur.Etudiant_ID,etudiant_parent_tuteur.Mather_id,etudiant_parent_tuteur.Parent_Tuteur_ID,etudiant_parent_tuteur.Father_id,etudiant.* FROM etudiant_parent_tuteur INNER JOIN etudiant ON etudiant_parent_tuteur.Etudiant_ID = etudiant.ID WHERE Etudiant_ID != $ID_student AND  (etudiant_parent_tuteur.Mather_id= $MathersID OR etudiant_parent_tuteur.Father_id = $fathersID OR etudiant_parent_tuteur.Parent_Tuteur_ID = $fResponsiblesID)";
    $results = mysqli_query($conn,$Query);
    
        if(!$row['QR_code'] == ""){

  
    ?>

                   <br><br><label class="labels">Telecharger QR Code :</label><br>
                   <div>
                   <a download="<?php echo $row['ID']; ?>.png" href="../qr_img/<?php echo $row['ID']; ?>.png" title="ImageName">
    <img alt="ImageName" src="../qr_img/<?php echo $row['ID']; ?>.png">
</a>
                   </div>
<?php   } ?>
                <div class="mt-5 text-center "><button id="subForm" class="btn btn-primary profile-button disbleNon" name="update" type="submit">Enregistrer</button></div>
                
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 py-5">
                <div  class="d-flex justify-content-between align-items-center experience">  <span>formulaire PDF</span> <a href="http://35.180.254.92/wp-content/plugins/Getdatafrom7/completed/<?php echo $row['Date_de_naissance']."_".$row['Prenom'].$row['Nom'].".pdf";?>" class="btn btn-info" download >  <span class=" px-3 p-1 add-experience"><i class="fa fa-file-pdf-o"></i>&nbsp; Télécharger</span></a></div><br>
                <div class="col-md-12"><label class="labels">Ajouter une autre maladie </label><input type="text" class="form-control disable" placeholder="Maladie" name="Autres_maladies" value="<?php echo $row['Autres_maladies']; ?>"></div> <br>
                <div class="col-md-12"><label class="labels">Maladies étudiantes:</label> <?php foreach ($Problem as $value) {echo "   <li class='list-group-item list-group-item-danger'> <button style='background-color:transparent' name='deletMLD' value='$value'  class='btn btn-primary-outline disbleNon' type='submit' ><span  class='  add-experience  profile-button'><i class='fa fa-trash-o'></i></span> </button> $value</li> <br>";} ?></div>
            </div>
        </div>
        <button type="submit" class="btn btn-danger ml-4 P-3" name="delet" onclick="return confirm('Si vous supprimez cet étudiant, vous supprimerez tout ce qui concerne cet étudiant, par exemple :Tous les paiements seront supprimés...Êtes-vous sûr?')"> <span  class=" px-2 p-1 add-experience  profile-button m-3
    "><i class="fa fa-trash-o"></i>&nbsp; Supprimer Tout</span> </button> 
    </div>


    <div class="col ">
            <div class="p-3 py-5">
                <?php if (!mysqli_num_rows($results)==0) { 
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
    </form>
<script>

// call ajax 

// var ajax = new XMLHttpRequest();
// var mmethod = "GET";
// var url = "student.php";
// var asynchronous = true;
// ajax.open(mmethod,url,asynchronous);

// /// start 
// ajax.send()

// ajax.onreadystatechange = function() {




// if (this.readyState == 4 && this.status == 200) {

//     alert(this.responseText);
    
// }

// }

var btn_edit = document.getElementById('Edit');

btn_edit.addEventListener("click", edit);

function edit (){

    var botton_sub = document.getElementById('subForm');
    // botton_sub.style.display = "block";
    btn_edit.style.display = "none";
    botton_sub.className = "btn btn-primary profile-button";

    
    const disables = document.querySelectorAll('.disable');
    var disbleNon = document.querySelectorAll('.disbleNon');
    

disables.forEach(box => {
  box.className = "form-control";

});

disbleNon.forEach(box => {
    box.classList.remove("disbleNon");

});

}



</script>




<?php  include("../include/footer.php"); } else  if ( !isset($_SESSION['login'])) {
            header('Location: login.php');
        }?>
   
   <script src="https://code.jquery.com/jquery-3.5.1.js"></script>