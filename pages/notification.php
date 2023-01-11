
<?php 
// if ( !isset($_SESSION['login'])) {
//     header('Location: login.php');
// }

session_start();

if (isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {


include("../Connect/cnx.php");



include("../include/nav.php");


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
<title><?php echo "$Titre"; ?> Admin - Notification</title>
    <!-- MAIN -->
    <div class="col">
        
     
    <!-- exel dowaloand -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>
    <script src="../js/FileSaver.min.js"></script>
    <script src="../js/excel-gen.js"></script>

    <!-- end file exel dowaloand -->
    <br><br>
<h4 class="text-right" style="text-align: center !important;">Notification</h4> 


<div class="table-responsive mt-5">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
                <th>ID</th>
                <th>Prenom</th>
                <th>Nom</th>
                <th>vivre avec</th>
                <th>Classe</th>
                <th>Groupe</th>
                <th>Mois non payés</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            <?php 

$thisMOTH = date("m");
$thisYEAR = date("Y");

$ins_notPAYid = [];
$IDSinscription = "SELECT paiement.Montant, SUM(paiement.Montant + etudiant.Remise) AS totall,paiement.Date_de_paiement,paiement.Ins_ID AS IDpayements,paiement.Debut_de_validite,paiement.Fin_de_validite, inscription.Etu_ID,inscription.ID as insID,inscription.grop_ID, etudiant.ID AS etudiantsID,etudiant.Prenom,etudiant.Nom AS etudiant_Noms ,etudiant.Remise,etudiant.Vit_avec,groupe.*,groupe.Nom AS groupNAM,niveau.* FROM inscription JOIN paiement ON paiement.Ins_ID = inscription.ID JOIN etudiant ON etudiant.ID = inscription.Etu_ID JOIN groupe ON groupe.ID = inscription.grop_ID JOIN niveau ON niveau.ID = groupe.Niveau_ID WHERE inscription.Annee_scolaire = '$Annee_scolaireSQL' AND YEAR(paiement.Date_de_paiement) = $thisYEAR   GROUP BY IDpayements order by IDpayements DESC ;";
$IDSinscriptionRESULT = mysqli_query($conn,$IDSinscription);
if ($thisMOTH == "07" ||$thisMOTH == "08" ) {
}else{

    switch ($thisMOTH) {
        case "09":
            $thisMOTH = 1;
            break;
        case "10":
            $thisMOTH = 2;
            break;
        case "11":
            $thisMOTH = 3;
            break;
            case "12":
                $thisMOTH = 4;
                break;
                case "01":
                    $thisMOTH = 5;
                    break;
                    case "02":
                        $thisMOTH = 6;
                        break;
                        case "03":
                            $thisMOTH = 7;
                            break;
                        case "04":
                            $thisMOTH = 8;
                            break;
                            case "05":
                                $thisMOTH = 9;
                                break;
                                case "06":
                                    $thisMOTH = 10;
                                    break;
                                    case "07":
                                        $thisMOTH = 11;
                                        break;
                                        case "08":
                                            $thisMOTH = 12;
                                            break;
    }
while ($ROW_test = mysqli_fetch_assoc($IDSinscriptionRESULT)) {
    
       $price_class = $ROW_test["Price"];
        $price_class_thismothTOTALL = $price_class * $thisMOTH ;
        $whatneedtobe = $price_class * $thisMOTH;
         $Totall_pay = $ROW_test["totall"];
        $mothNOTpay = ($whatneedtobe-$Totall_pay)/$price_class;

    if ($Totall_pay < $price_class_thismothTOTALL) {
        array_push($ins_notPAYid, $ROW_test["totall"]);
    

    


    ?>
<tr>

        <td><a href="./student.php?id=<?php echo $ROW_test["etudiantsID"]; ?>"><?php echo $ROW_test["etudiantsID"]; ?> </a></td>
        <td><?php echo $ROW_test["Prenom"]; ?></td>
        <td><?php echo $ROW_test["etudiant_Noms"];?></td>
        <td><?php echo $ROW_test["Vit_avec"];?></td>
        <td><?php echo $ROW_test["Annee"];?></td>
        <td><?php echo $ROW_test["groupNAM"];?></td>
        <td><?php echo $mothNOTpay." Mois";?></td>
        <td><ul class="social mb-0 list-inline">
                    <li class="list-inline-item"><a href="./student.php?id=<?php echo $ROW_test["etudiantsID"]; ?>" class="social-link"><i class="fa fa-eye btn bg-primary text-white" aria-hidden="true"></i></a></li>
                    <li class="list-inline-item"><a href="./payment.php?id=<?php echo $ROW_test["etudiantsID"]; ?>" class="social-link"><i class="fa fa-usd btn bg-success text-white" aria-hidden="true"></i></a></li>
                </ul></td>


    <?php }}

$newINSCRIPTIONnotPAY = "SELECT inscription.ID,inscription.Etu_ID,etudiant.statut,etudiant.ID AS etudiantsID,etudiant.Prenom,etudiant.Nom AS etudiant_Noms,etudiant.Vit_avec,niveau.*,groupe.*,groupe.Nom AS groupNAM FROM inscription JOIN etudiant ON etudiant.ID = inscription.Etu_ID JOIN groupe ON groupe.ID = inscription.grop_ID JOIN niveau ON niveau.ID = groupe.Niveau_ID  WHERE NOT EXISTS (SELECT * FROM paiement WHERE paiement.Ins_ID = inscription.ID) AND etudiant.statut = 1 AND inscription.Annee_scolaire = '$Annee_scolaireSQL'";
$TESTforNEWins = mysqli_query($conn,$newINSCRIPTIONnotPAY);

while ($ROW_NEWinst = mysqli_fetch_assoc($TESTforNEWins)) {
    
        array_push($ins_notPAYid, 0);
    ?> 
    
    <td><a href="./student.php?id=<?php echo $ROW_NEWinst["etudiantsID"]; ?>"><?php echo $ROW_NEWinst["etudiantsID"]; ?> </a></td>
        <td><?php echo $ROW_NEWinst["Prenom"]; ?></td>
        <td><?php echo $ROW_NEWinst["etudiant_Noms"];?></td>
        <td><?php echo $ROW_NEWinst["Vit_avec"];?></td>
        <td><?php echo $ROW_NEWinst["Annee"];?></td>
        <td><?php echo $ROW_NEWinst["groupNAM"];?></td>
        <td>Aucun mois n'a été payé</td>
    <td><ul class="social mb-0 list-inline">
                    <li class="list-inline-item"><a href="./student.php?id=<?php echo $ROW_NEWinst["etudiantsID"]; ?>" class="social-link"><i class="fa fa-eye btn bg-primary text-white" aria-hidden="true"></i></a></li>
                    <li class="list-inline-item"><a href="./payment.php?id=<?php echo $ROW_NEWinst["etudiantsID"]; ?>" class="social-link"><i class="fa fa-usd btn bg-success text-white" aria-hidden="true"></i></a></li>
                </ul></td>

</tr>
    <?php
 }

 $_SESSION['count'] =  count($ins_notPAYid);
?>
    </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Prenom</th>
                <th>Nom</th>
                <th>vivre avec</th>
                <th>Classe</th>
                <th>Groupe</th>
                <th>Mois non payés</th>
                <th>Action</th>

            </tr>
        </tfoot>
    </table>
    <button class="btn btn-success mt-2" id="generate-excel"> Télécharger Rapport </button>
    <br><br><br><br>
<?php


}


 ?>
        </tbody>
    </table>

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

"src_id" : "example",
"show_header" : "true"

});

$("#generate-excel").click(function () {
excel.generate();
});


$('#example').DataTable(
{

searching: true,

//Datatables will show information about the table including information about filtered data if that action is being performed 
 //https://datatables.net/reference/option/info
info: true,
order: [[ 6, "asc" ]],



}
);



});
</script>