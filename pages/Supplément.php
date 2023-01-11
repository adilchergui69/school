
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

ob_start();

  if (isset($_POST["submit"])) {

$Prenom = $_POST["Prenom"];
$Nom = $_POST["Nom"];
$Prix = $_POST["Prix"];
$Date_de_paiement = $_POST["Date_de_paiement"];
$Payer_pour = $_POST["Payer_pour"];
$Mode_de_paiement = $_POST["Mode_de_paiement"];

$sql = "INSERT INTO `supplément`(`Prenom`, `Nom`, `Date_de_paiement`, `Prix`, `Payer pour`, `Mode_de_paiement`, `Annee_scolaire`) VALUES ('$Prenom','$Nom','$Date_de_paiement','$Prix','$Payer_pour','$Mode_de_paiement','$Annee_scolaireSQL')";
    
mysqli_query($conn,$sql);

$Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Insérer','$dateTIMEnow','Supplément','Été payé pour $Payer_pour Date de paiement $Date_de_paiement, prix($Prix DH),Mode de paiement : $Mode_de_paiement','$ip_address')";
mysqli_query($conn,$Query_spy);


  }





include("../include/nav.php");


?>

<link rel="stylesheet" href="../css\student.css">

    <!-- MAIN -->
    <div class="col">
        
        <!-- <h1>
            
            <small class="text-muted"></small>
        </h1> -->
        
        <title><?php echo "$Titre"; ?> Admin - Supplément</title>

<!-- For demo purpose -->
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">

        <div class="col-md-5 border-right">
            <div>
                <div>
               <h4 class="mb-4 mt-5" style="text-align: center;">Supplément</h4>  
                </div>
                <form method="POST" onsubmit="return confirm ('Êtes-vous sûr?')">
                <div class="row mt-2">
  
                <div class="col-md-6"><label class="labels">Prenom du bénéficiaire</label><input name="Prenom" type="text"  class="form-control" placeholder="Prenom" required></div>
                    <div class="col-md-6"><label class="labels">Nom du bénéficiaire</label><input name="Nom" type="text"  class="form-control" placeholder="Nom" required></div>
                    <div class="col-md-6"><label class="labels">Prix</label><input step="0.01" name="Prix" type="number" class="form-control" value="" placeholder="Prix" required ></div>
                    <div class="col-md-6"><label class="labels">Date de paiement</label><input name="Date_de_paiement" type="datetime-local" class="form-control" value="<?php $newDate = date('Y-m-d H:i'); echo $newDate; ?>" placeholder="Date de paiement" required ></div>
                      <div class="col-md-6"><label class="labels">Payer pour</label><input name="Payer_pour" type="text" class="form-control" placeholder="Payer pour..." required></div>

                    <div class="col-md-6"><label class="labels">Mode de paiement</label>
                    <select name="Mode_de_paiement" class="form-control" aria-label="Mode de paiement" required>
                    <option value="espèces">Espèces</option>
                    <option value="chèque">Chèque</option>
                    <option value="Paiement bancaire">Paiement bancaire</option>
                  </select>
                


                </div>

                   </div>
                   <div class="mt-3 text-center ">                
                    <label for="inputEmail4">Année scolaire : <strong><?php echo $Annee_scolaireSQL; ?></strong>
                  </label>
                  </div>

                <div class="mt-5 text-center "><button id="subForm" name="submit" class="btn btn-primary profile-button" type="submit">Soumettre</button></div>
                </form>
            </div>
        </div>
        <div class="col-md-7">
            <div class="p-3 py-5">
                <div class="col-md-12"><label class="labels">historique de paiement:</label> 
                <form method="POST" onsubmit="return confirm ('Êtes-vous sûr?')">

<?php 
$Supplément = "SELECT * FROM `supplément` where Annee_scolaire = '$Annee_scolaireSQL'  ORDER BY `ID` DESC";
$Supplémentresult =  mysqli_query($conn,$Supplément);
while ($row = mysqli_fetch_assoc($Supplémentresult)) {

$ID = $row["ID"];  
$Prenom = $row["Prenom"];
$Nom = $row["Nom"];
$Prix = $row["Prix"];
$Date_de_paiement = $row["Date_de_paiement"];
$Payer_pour = $row["Payer pour"];
$Mode_de_paiement = $row["Mode_de_paiement"];

echo "    <li class='list-group-item list-group-item-danger Supplément'> <button style='background-color:transparent' name='deletSupplément' value='$ID'  class='btn btn-primary-outline ' type='submit' ><span  class='  add-experience  profile-button'><i class='fa fa-trash-o'></i></span> </button> <span style='text-align: center;'> <span class='bénéficiaire'>$Nom $Prenom</span> / <span class='Payer_pour'>$Payer_pour</span> / (-<span class='Prix'>$Prix</span> DH) <button style='background-color:transparent' name='deletPAYEMENTactive' value='$ID'
class='btn btn-primary-outline IDpayementactive' type='button' data-toggle='modal' data-target='#exampleModal' ><span  class='  add-experience  profile-button'><i class='fa fa-file-pdf-o'></i></span> </button> <p style='font-size: 12px;text-align: end;'> ' <span class='Date'>$Date_de_paiement</span>' </p> </span></li> <br>   ";} 

if (isset($_POST["deletSupplément"])) {

  $idSupplément = $_POST["deletSupplément"];

  $Query_deletpayemntSPY = "SELECT * FROM `supplément` WHERE `ID`= $idSupplément";

$result_Query_deletpayemntSPY = mysqli_query($conn,$Query_deletpayemntSPY);

$row_Query_deletpayemntSPY = mysqli_fetch_assoc($result_Query_deletpayemntSPY);

$supplément_Montant = $row_Query_deletpayemntSPY["Prix"];
$Prenom_supplément = $row_Query_deletpayemntSPY["Prenom"];
$Nom_supplément = $row_Query_deletpayemntSPY["Nom"];
$Nom_Payer_pour = $row_Query_deletpayemntSPY["Payer pour"];
$supplément_date = $row_Query_deletpayemntSPY["Date_de_paiement"];



  $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Supprimer','$dateTIMEnow','supplément','Payer pour $Nom_Payer_pour,Date de paiement $supplément_date Il a été supprimé, prix($supplément_Montant DH),le bénéficiaire $Prenom_supplément $Nom_supplément','$ip_address')";
  mysqli_query($conn,$Query_spy);

  $dletPAYEMENTqr = "DELETE FROM supplément WHERE ID = $idSupplément;";
  mysqli_query($conn,$dletPAYEMENTqr);
  header("Refresh:0");

}



?>
              </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<style>

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
</form>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div  class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Facture Paiement Supplément</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div id='printito'> 
                     
                      <img src="https://i.imgur.com/5JD7Co6.png" class="rounded mx-auto d-block" >
                      
                      <h1 style="margin-top: 2%;text-align: center;border-style: solid;"> RECU DE PAIEMENT </h1>
                      <div style="margin-top: 10%;">
                          
                      <h4> Bénéficiaire<span id="bénéficiaire" style="padding-left: 87PX;">:</span> </h4>
                      <h4> Date<span id="dateNOWfct" style="padding-left: 161PX;">:</span> </h4>
                      <h4> Paiement ID<span id="IDfct" style="padding-left: 80px;">:</span> </h4>
                      <h4> Payer pour<span id="Payer_pour" style="padding-left: 95px;">:</span> </h4>
                      <br>
                      <hr>
                      <br><br>
                      <h4> <strong>Montant</strong><span id="Montant" style="padding-left: 107px;">:</span> </h4>
                      <h4> <strong>Frais</strong><span style="padding-left: 152px;">: 0.00 DH</span> </h4>
                      <h4> <strong>Total a payer</strong><span id="Total" style="padding-left: 61px;">:</span> </h4><br><br>

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
              </div>
              <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>

//Supplément
$('.Supplément').click(function(){
 var idpayement = "";
 var bénéficiaire = "";
 var Prix = "";
 var Date  = "";
 var Payer_pour  = "";
 

 var idpayement = $('.IDpayementactive',this).val();
 var bénéficiaire = $('.bénéficiaire',this).text();
 var Prix = $('.Prix',this).text();
 var Date = $('.Date',this).text();
 var Payer_pour = $('.Payer_pour',this).text();
 

 $( "#bénéficiaire" ).text( ": "+bénéficiaire );
 $( "#dateNOWfct" ).text( ": "+Date );
 $( "#Payer_pour" ).text( ": "+Payer_pour );
 $( "#IDfct" ).text( ": #"+idpayement );
 $( "#Montant" ).text( ": "+Prix+" DH" );
 $( "#Total" ).text( ": "+Prix+" DH" );
 


});

$('#btnPRINT').click(function(){
 

 $("#printito").printThis();


});
//Supplément end



</script>
<?php  include("../include/footer.php");
 }else  if ( !isset($_SESSION['login'])) {
  header('Location: login.php');
}
?>
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="..\js\printThis.js"></script>
