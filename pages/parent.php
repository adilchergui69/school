
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
    
    $dateTIMEnow = date("Y-m-d H:i:s");
    $nameUSER = $_SESSION['login'][2];
    $prenomeUSER =  $_SESSION['login'][3];
include("../Connect/cnx.php");
ob_start();
$ID_tuteur = $_GET['id'];

// table etudiant
$alert = "";
$Query = "SELECT * FROM parent_tuteur WHERE ID=$ID_tuteur;";

$result = mysqli_query($conn,$Query);

$row = mysqli_fetch_assoc($result);

$PrenomFORspy = $row["prenom"];
$NomFORspy = $row["Nom"];


include("../include/nav.php");
if (isset($_POST["update"])) {
    $Prenom = $_POST["Prenom"];
    $Nom = $_POST["Nom"];
    $Adresse_personnelle = $_POST["Adresse_personnelle"];
    $Tel_personnel = $_POST["Tel_personnel"];
    $Adresse_travail = $_POST["Adresse_travail"];
    $Tel_travail = $_POST["Tel_travail"];
    $Cin = $_POST["Cin"];
    $Lien = $_POST["Lien"];
    $Profession = $_POST["Profession"];
    
       
$Query_update = "UPDATE `parent_tuteur` SET `Nom`='$Nom',`prenom`='$Prenom',`Adresse_personnelle`='$Adresse_personnelle',`Tel_personnel`='$Tel_personnel',`Adresse_travail`='$Adresse_travail',`Tel_travail`='$Tel_travail',`Cin`='$Cin',`Lien`='$Lien',`Profession`='$Profession' WHERE `ID`=$ID_tuteur";
        


       
 mysqli_query($conn,$Query_update);

 $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Mettre à jour','$dateTIMEnow','parent(e) $PrenomFORspy $NomFORspy','Une ou plusieurs informations sur parent(e) ont changé','$ip_address')";
 mysqli_query($conn,$Query_spy);

 header("Refresh:0");
    
 ob_end_flush();
}

$Query = "SELECT etudiant_parent_tuteur.Parent_Tuteur_ID, etudiant_parent_tuteur.Etudiant_ID,etudiant_parent_tuteur.Mather_id,etudiant_parent_tuteur.Parent_Tuteur_ID,etudiant_parent_tuteur.Father_id,etudiant.* FROM etudiant_parent_tuteur INNER JOIN etudiant ON etudiant_parent_tuteur.Etudiant_ID = etudiant.ID WHERE etudiant_parent_tuteur.Mather_id=$ID_tuteur OR etudiant_parent_tuteur.Father_id = $ID_tuteur OR etudiant_parent_tuteur.Parent_Tuteur_ID = $ID_tuteur";
$results = mysqli_query($conn,$Query);

if (isset($_POST["DELET"])) {
    $IDdelet = $_GET["id"];
 
    $Query_parentSPYS = "SELECT * FROM parent_tuteur WHERE ID = $IDdelet";
     $result_parentSPYS = mysqli_query($conn,$Query_parentSPYS);
     $rowSPY = mysqli_fetch_assoc($result_parentSPYS);
     $prenomspy = $rowSPY['prenom'];
     $Nompy = $rowSPY['Nom'];
     $Cinspy = $rowSPY['Cin'];
 
    $Query_delet = "DELETE FROM `parent_tuteur` WHERE `ID` = $IDdelet";
     mysqli_query($conn,$Query_delet);
 
     $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Supprimer','$dateTIMEnow','Parents($prenomspy $Nompy  )','Parent ($prenomspy $Nompy) Titulaire de la carte nationale ($Cinspy) a été supprimé','$ip_address')";
 mysqli_query($conn,$Query_spy);
 
 
 header("Location: search.php");
    
 ob_end_flush();

 }

?>

<link rel="stylesheet" href="../css\student.css">

    <!-- MAIN -->
    <div class="col">
        
        <!-- <h1>
            
            <small class="text-muted"></small>
        </h1> -->
        
        
<!-- For demo purpose -->
<form  method="POST" enctype="multipart/form-data" onsubmit="return confirm ('Êtes-vous sûr?')">
<?php echo $alert;  ?>
<title><?php echo "$Titre"; ?> Admin - Informations parentales</title>
<div class="container">

<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        
    <div class="col-md-10 ml-5">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Informations parentales  </h4> <span id="Edit"  class="border px-2 p-1 add-experience btn btn-warning profile-button"><i class="fa fa-pencil"></i>&nbsp;Éditer</span>
                </div>
               
                <div class="row mt-2">
                   
                    <div class="col-md-6"><label class="labels">Prenom</label><input name="Prenom"  type="text" class="form-control disable" placeholder="Prenom" value="<?php echo $row['prenom']; ?>"></div>
                    <div class="col-md-6"><label class="labels">Nom</label><input name="Nom" type="text" class="form-control disable" value="<?php echo $row['Nom']; ?>" placeholder="Nom"></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Adresse personnelle</label><input name="Adresse_personnelle" type="text" placeholder="Adresse personnelle" value="<?php echo $row['Adresse_personnelle']; ?>" class="form-control disable" placeholder="Date de naissance" ></div>
                    <div class="col-md-12"><label class="labels">Tel personnel</label><input name="Tel_personnel" type="text" class="form-control disable" placeholder="Tel personnel" value="<?php echo $row['Tel_personnel']; ?>"></div>
                    <div class="col-md-12"><label class="labels">Adresse travail</label><input name="Adresse travail" type="text" class="form-control disable" placeholder="Adresse travail" value="<?php echo $row['Adresse_travail']; ?>"></div>
                    <div class="col-md-12"><label class="labels">Tel travail</label><input name="Tel_travail" type="number" class="form-control disable" placeholder="Tel travail" value="<?php echo $row['Tel_travail']; ?>"></div>
                    <div class="col-md-12"><label class="labels">Cin</label><input name="Cin"  type="text" class="form-control disable" placeholder="Cin" value="<?php echo $row['Cin']; ?>"></div>
                    <div class="col-md-12"><label class="labels">Lien</label><input name="Lien" type="text" class="form-control disable" placeholder="Lien" value="<?php echo $row['Lien']; ?>"></div>
                    <div class="col-md-12"><label class="labels">Profession</label><input type="text" name="Profession" class="form-control disable" placeholder="Profession" value="<?php echo $row['Profession'] ?>"></div>

                    <?php  
                $idCHECK = $row["ID"];
                $Query_checkinscription = "SELECT * FROM `etudiant_parent_tuteur` WHERE `Mather_id` = $idCHECK OR `Father_id` = $idCHECK OR `Etudiant_ID` = $idCHECK";
                $result_checkinscription = mysqli_query($conn,$Query_checkinscription);
                if(mysqli_num_rows($result_checkinscription) >0){
                                                                    //found
                }else{
                           
                    //not found
                        ?>
                        <form method="POST" onsubmit="return confirm ('Êtes-vous sûr?')">
                        <div class="col-md-12 text-center mt-5">
<button type="submit" class="btn btn-danger ml-4 P-3" name="DELET" > <span class=" px-2 p-1 add-experience  profile-button m-3
    "><i class="fa fa-trash-o"></i>&nbsp; Supprimer</span> </button>

    </div>
    </form>
      <?php } ?>

                </div>
<?php 




?>

                <div class="mt-5 text-center "><button id="subForm" class="btn btn-primary profile-button disbleNon" name="update" type="submit">Enregistrer</button></div>
                
            </div>
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
    box.className = "";

});

}



</script>




<?php  include("../include/footer.php"); } else  if ( !isset($_SESSION['login'])) {
            header('Location: login.php');
        }?>
   
   <script src="https://code.jquery.com/jquery-3.5.1.js"></script>