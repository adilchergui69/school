
<?php 


session_start();

if (isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {


include("../Connect/cnx.php");



include("../include/nav.php");



$count_day = [];
$count_moth = [];
$count_year = [];

$thisMOTH = date("m");
$thisYEAR = date("Y");
$thisDAY = date("d");
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


//day count
$Query_day = "SELECT SUM(supplément.Prix) as tootal
FROM supplément WHERE supplément.Annee_scolaire = '$Annee_scolaireSQL' AND DAY(supplément.Date_de_paiement) = $thisDAY
UNION ALL
SELECT SUM(paiement.Montant)
FROM paiement JOIN inscription ON paiement.Ins_ID = inscription.ID WHERE  inscription.Annee_scolaire = '$Annee_scolaireSQL' AND DAY(paiement.Date_de_paiement) = $thisDAY
UNION ALL
SELECT SUM(inscription_niveau_activites.Tarif - inscription_activites.Remise)
from paiement_activite join inscription_activites on inscription_activites.ID = paiement_activite.insactivites_ID join inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on inscription_niveau_activites.activ_ID = activites.ID WHERE inscription_niveau_activites.Annee_scolaire = '$Annee_scolaireSQL'  AND DAY(paiement_activite.Date_de_paiement) = $thisDAY;";
$result_day = mysqli_query($conn,$Query_day);


while ($row = mysqli_fetch_assoc($result_day)) {
array_push($count_day,$row['tootal']);

}

//day END


//MONTH count
$Query_MONTH = "SELECT SUM(supplément.Prix) as tootal
FROM supplément WHERE supplément.Annee_scolaire = '$Annee_scolaireSQL' AND MONTH(supplément.Date_de_paiement) = $thisMOTH
UNION ALL
SELECT SUM(paiement.Montant)
FROM paiement JOIN inscription ON paiement.Ins_ID = inscription.ID WHERE  inscription.Annee_scolaire = '$Annee_scolaireSQL' AND MONTH(paiement.Date_de_paiement) = $thisMOTH
UNION ALL
SELECT SUM(inscription_niveau_activites.Tarif - inscription_activites.Remise)
from paiement_activite join inscription_activites on inscription_activites.ID = paiement_activite.insactivites_ID join inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on inscription_niveau_activites.activ_ID = activites.ID WHERE inscription_niveau_activites.Annee_scolaire = '$Annee_scolaireSQL'  AND MONTH(paiement_activite.Date_de_paiement) = $thisMOTH;";
$result_MONTH = mysqli_query($conn,$Query_MONTH);



while ($row = mysqli_fetch_assoc($result_MONTH)) {
  array_push($count_moth,$row['tootal']);
}
//MONTH END


//year count
$Query_year = "SELECT SUM(supplément.Prix) as tootal
FROM supplément WHERE supplément.Annee_scolaire = '$Annee_scolaireSQL' AND YEAR(supplément.Date_de_paiement) = $thisYEAR
UNION ALL
SELECT SUM(paiement.Montant)
FROM paiement JOIN inscription ON paiement.Ins_ID = inscription.ID WHERE  inscription.Annee_scolaire = '$Annee_scolaireSQL' AND YEAR(paiement.Date_de_paiement) = $thisYEAR
UNION ALL
SELECT SUM(inscription_niveau_activites.Tarif - inscription_activites.Remise)
from paiement_activite join inscription_activites on inscription_activites.ID = paiement_activite.insactivites_ID join inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on inscription_niveau_activites.activ_ID = activites.ID WHERE inscription_niveau_activites.Annee_scolaire = '$Annee_scolaireSQL'  AND YEAR(paiement_activite.Date_de_paiement) = $thisYEAR;";
$result_year = mysqli_query($conn,$Query_year);


while ($row = mysqli_fetch_assoc($result_year)) {
  array_push($count_year,$row['tootal']);

}



$Query_etudiant = "SELECT * FROM `etudiant` WHERE statut IS null order by `ID` desc limit 5 ";
$result_etudiant = mysqli_query($conn,$Query_etudiant);

?>
<link rel="stylesheet" href="../css\students.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">


<style>
.rounded-pill {
    border-radius: 50rem!important;
}

.count {
    border-radius: 18px;
    margin-right: 30px;
    margin-bottom: 20px !important;
}

</style>
    <!-- MAIN -->
    <div class="col">
        
        <!-- <h1>
        TABLEAU 
            <small class="text-muted">DE BORD</small>
        </h1> -->
        
        
<!-- For demo purpose -->


<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    
    <!-- put your bootstrap here -->

    <!-- Latest compiled and minified CSS -->

    <!-- Latest compiled and minified JavaScript -->

    <!-- include jszip script if needed -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>

    <!-- Include FileSaver.js (you can download it from description) -->

    <script src="../js/FileSaver.min.js"></script>

    <!-- and dont forget to include excel-gen,js file -->
    <script src="../js/excel-gen.js"></script>
    <title><?php echo "$Titre"; ?> Admin - Dashboard</title>
    

<div class="container">
    <div>

    

        <!-- Team item -->
        <section class="statistics mt-4">

<?php
if ($thisMOTH = 7 || $thisMOTH = 8|| $thisMOTH = 9) {


  
  echo '<script>localStorage.removeItem("alert");</script>';


?>


<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Important!</strong> Changer l'année scolaire à la nouvelle année scolaire pour un nouveau départ réussi ⇨ <a id="allerts" href="setting.php">Cliquez ici</a> ⇦
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
  <?php }?>
      <div class="row">
        <div class="col-sm count" style="background-color: #20a8da; margin-left: 20px;">
          <div class="box d-flex rounded-2 align-items-center mb-4 mb-lg-0 p-3">
            <i class="uil-envelope-shield fs-2 text-center bg-primary rounded-circle"></i>
            <div class="ms-3">
              <div class="d-flex align-items-center">
                <h3 class="mb-0"><?php echo $count_day[1]+$count_day[2] - $count_day[0]; ?></h3> <span class="d-block ms-2">DH</span>
              </div>
              <p class="fs-normal mb-0">ce jour</p>
            </div>
          </div>
        </div>
        <div class="col-sm count" style="background-color: #f8cb00; margin-left: 20px;">
          <div class="box d-flex rounded-2 align-items-center mb-4 mb-lg-0 p-3">
            <i class="uil-file fs-2 text-center bg-danger rounded-circle"></i>
            <div class="ms-3">
              <div class="d-flex align-items-center">
                <h3 class="mb-0"><?php echo $count_moth[1]+$count_moth[2] - $count_moth[0]; ?></h3> <span class="d-block ms-2">DH</span>
              </div>
              <p class="fs-normal mb-0">ce mois</p>
            </div>
          </div>
        </div>
        <div class="col-sm count" style="background-color: #f96d6c; margin-left: 20px;">
          <div class="box d-flex rounded-2 align-items-center mb-4 mb-lg-0 p-3">
            <i class="uil-users-alt fs-2 text-center bg-success rounded-circle"></i>
            <div class="ms-3">
              <div class="d-flex align-items-center">
              <h3 class="mb-0"><?php echo $count_year[1]+$count_year[2] - $count_year[0]; ?></h3> <span class="d-block ms-2">DH</span>
              </div>
              <p class="fs-normal mb-0">cette année</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="admins mt-4">
      
            <!-- <h4>Admins:</h4> -->



        <!-- Team item -->
        
        
        <div class="container">
    <div class="row text-center">


    <?php while ($row_etudiant = mysqli_fetch_assoc($result_etudiant)) {
    // printf("%s (%s)\n", $row["ID"], $row["Nom"]);
?>

        <!-- Team item -->
        
        <div class="col-xl-3 col-sm-6 mb-5">
        <a href="student.php?id=<?php echo $row_etudiant["ID"]?>"> 
            <div class="bg-white rounded shadow-sm py-5 px-4"><img src="<?php if ( !$row_etudiant['Photo']==null || !$row_etudiant['Photo']== "") {echo "../uploads/". $row_etudiant['Photo']; }else echo "https://cdn-icons-png.flaticon.com/512/183/183760.png"; ?>" alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                <h5 class="mb-0"><?php echo $row_etudiant["Prenom"]." ". $row_etudiant["Nom"] ?> </h5><span class="small text-uppercase text-muted"><?php echo $row_etudiant["Langues_parlees_par_les_parents"]?></span>
                </a>
                <ul class="social mb-0 list-inline mt-3">
                    <li class="list-inline-item"><a href="student.php?id=<?php echo $row_etudiant["ID"]?>" class="social-link"><i class="fa fa-eye" aria-hidden="true"></i></a></li>

                </ul>
            </div>
        </div><!-- End -->

            <?php }?>

    </section>

    <div class="table-responsive mt-5">
    <table id="<?php echo"Paiement".$thisDAY."_".$thisMOTH."_".$thisYEAR;?>" class="table table-striped table-bordered" style="width:100%" data-sorter="datesSorter">
        <thead>
            <tr>
                <th>ID</th>
                <th>Prenom</th>
                <th>Nom</th>
                <th>Mode de paiement</th>
                <th id="date_paiment">Date de paiement</th>
                <th>Prix</th>
                <th>Payer pour</th>
            </tr>
        </thead>
        <tbody>

          <?php 

          $Query_table_paiement_activite = "SELECT etudiant.ID AS id_Utudt, etudiant.Prenom, etudiant.Nom,inscription_activites.Remise,paiement_activite.Mode_de_paiement,paiement_activite.ID,paiement_activite.Date_de_paiement,inscription_niveau_activites.Tarif,activites.Descriptions,paiement_activite.Debut_de_validite,paiement_activite.Fin_de_validite from paiement_activite join inscription_activites on inscription_activites.ID = paiement_activite.insactivites_ID join inscription_niveau_activites on inscription_niveau_activites.ID = inscription_activites.niv_acti_ID join activites on inscription_niveau_activites.activ_ID = activites.ID join inscription on inscription.ID = inscription_activites.inscr_ID  join etudiant on inscription.Etu_ID = etudiant.ID where inscription_niveau_activites.Annee_scolaire = '$Annee_scolaireSQL'";
          $result_table_paiement_activite = mysqli_query($conn,$Query_table_paiement_activite);

          while ($row_table_paiement_activite = mysqli_fetch_assoc($result_table_paiement_activite)) {
          
          ?>
            <tr>
                <td><a href="./student.php?id=<?php echo $row_table_paiement_activite["id_Utudt"]; ?>"><?php echo $row_table_paiement_activite["id_Utudt"]; ?></a> </td>
                <td><?php echo $row_table_paiement_activite["Prenom"]; ?></td>
                <td><?php echo $row_table_paiement_activite["Nom"];?></td>
                <td><?php echo $row_table_paiement_activite["Mode_de_paiement"];?></td>
                <td><?php echo $row_table_paiement_activite["Date_de_paiement"]; ?></td>
                <td><?php echo $row_table_paiement_activite["Tarif"] - $row_table_paiement_activite["Remise"]; ?></td>
                <td> Activités </td>
            </tr>
            <?php }?>

            <?php 

$Query_table_paiement = "SELECT paiement.Ins_ID, paiement.Mode_de_paiement, paiement.Montant,paiement.Date_de_paiement, inscription.Etu_ID,inscription.ID, etudiant.Prenom , etudiant.ID , etudiant.Nom FROM paiement INNER JOIN inscription ON inscription.ID = paiement.Ins_ID INNER JOIN etudiant ON etudiant.ID = inscription.Etu_ID where inscription.Annee_scolaire = '$Annee_scolaireSQL'";
$result_table_paiement = mysqli_query($conn,$Query_table_paiement);

while ($row_table_paiement = mysqli_fetch_assoc($result_table_paiement)) {

?>
  <tr>
      <td><a href="./student.php?id=<?php echo $row_table_paiement["Etu_ID"]; ?>"><?php echo $row_table_paiement["Etu_ID"]; ?></a></td>
      <td><?php echo $row_table_paiement["Prenom"]; ?></td>
      <td><?php echo $row_table_paiement["Nom"];?></td>
      <td><?php echo $row_table_paiement["Mode_de_paiement"];?></td>
      <td><?php echo $row_table_paiement["Date_de_paiement"]; ?></td>
      <td><?php echo $row_table_paiement["Montant"]; ?></td>
      <td> Paiement Mensuel </td>
  </tr>
  <?php }?>

  <?php 

$Supplément = "SELECT * FROM `supplément` where Annee_scolaire = '$Annee_scolaireSQL' ";
$Supplémentresult =  mysqli_query($conn,$Supplément);
while ($row = mysqli_fetch_assoc($Supplémentresult)) {

$ID = $row["ID"];  
$Prenom = $row["Prenom"];
$Nom = $row["Nom"];
$Prix = $row["Prix"];
$Date_de_paiement = $row["Date_de_paiement"];
$Payer_pour = $row["Payer pour"];
$Mode_de_paiement = $row["Mode_de_paiement"];

?>
  <tr style="background-color: #f96d6c;">
      <td><a href="./Supplément.php"><?php echo $row["ID"]; ?></a></td>
      <td><?php echo $row["Prenom"]; ?></td>
      <td><?php echo $row["Nom"];?></td>
      <td><?php echo $row["Mode_de_paiement"];?></td>
      <td><?php echo $row["Date_de_paiement"]; ?></td>
      <td ><?php echo "-" . $row["Prix"]; ?></td>
      <td> <?php echo $row["Payer pour"]; ?> </td>
  </tr>
  <?php }?>

        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Prenom</th>
                <th>Nom</th>
                <th>Mode de paiement</th>
                <th>Date de paiement</th>
                <th>Prix</th>
                <th>Payer pour</th>
            </tr>
            </tfoot>
    </table>
    <button class="btn btn-success mt-2" id="generate-excel"> Télécharger Rapport </button>
    <br><br><br><br>
    </div>
<?php  include("../include/footer.php"); }else  if ( !isset($_SESSION['login'])) {
            header('Location: login.php');
        }?>
  



    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script>

        $(document).ready(function () {

          excel = new ExcelGen({

"src_id" : "<?php echo"Paiement".$thisDAY."_".$thisMOTH."_".$thisYEAR;?>",
"show_header" : "true"

});

$("#generate-excel").click(function () {
excel.generate();
});


          $('#<?php echo"Paiement".$thisDAY."_".$thisMOTH."_".$thisYEAR;?>').DataTable(
       {
       
        searching: true,
        
        //Datatables will show information about the table including information about filtered data if that action is being performed 
         //https://datatables.net/reference/option/info
        info: false,
        order: [[ 4, "desc" ]],
      

       }
    );

    

});
    </script>

    <script>
$(function() {
  const showAlert = localStorage.getItem("alert") === null;
  $(".alert").toggleClass("d-none",!showAlert)
  $(".close").on("click",function() {
    localStorage.setItem("alert","seen");
    $(this).closest(".alert").addClass("d-none")
  });
})  
</script>