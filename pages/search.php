
<?php 
// if ( !isset($_SESSION['login'])) {
//     header('Location: login.php');
// }

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

if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Director" || isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {

    
    $dateTIMEnow = date("Y-m-d H:i:s");
    $nameUSER = $_SESSION['login'][2];
    $prenomeUSER =  $_SESSION['login'][3];

include("../Connect/cnx.php");



include("../include/nav.php");

ob_start();

if (isset($_POST["DELET"])) {
   $IDdelet = $_POST["DELET"];

   $Query_parentSPYS = "SELECT * FROM parent_tuteur WHERE ID = $IDdelet";
    $result_parentSPYS = mysqli_query($conn,$Query_parentSPYS);
    $row = mysqli_fetch_assoc($result_parentSPYS);
    $prenomspy = $row['prenom'];
    $Nompy = $row['Nom'];
    $Cinspy = $row['Cin'];

   $Query_delet = "DELETE FROM `parent_tuteur` WHERE `ID` = $IDdelet";
    mysqli_query($conn,$Query_delet);

    $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Supprimer','$dateTIMEnow','Parents($prenomspy $Nompy  )','Parent ($prenomspy $Nompy) Titulaire de la carte nationale ($Cinspy) a été supprimé','$ip_address')";
mysqli_query($conn,$Query_spy);



}

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

<title><?php echo "$Titre"; ?> Admin - Rechercher</title>


<div class="col">
<br><br>
<h4 class="text-right" style="text-align: center !important;">Rechercher</h4> <br>

<div class="table-responsive mt-5">
<form method="POST" onsubmit="return confirm ('Êtes-vous sûr?')">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>prenom</th>
                <th>Adresse personnelle</th>
                <th>Tel personnel</th>
                <th>Adresse travail</th>
                <th>Tel travail</th>
                <th>Cin</th>
                <th>Profession</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>

          <?php 

          $Query_table_paiement_activite = "SELECT * FROM parent_tuteur";
          $result_table_paiement_activite = mysqli_query($conn,$Query_table_paiement_activite);

          while ($row_table_paiement_activite = mysqli_fetch_assoc($result_table_paiement_activite)) {
          
          ?>
            <tr style="width: 100%;">
                <td> <a href="./parent.php?id=<?php echo $row_table_paiement_activite["ID"]; ?>"> <?php echo $row_table_paiement_activite["ID"]; ?></a></td>
                <td><?php echo $row_table_paiement_activite["Nom"]; ?></td>
                <td><?php echo $row_table_paiement_activite["prenom"];?></td>
                <td><?php echo $row_table_paiement_activite["Adresse_personnelle"];?></td>
                <td><?php echo $row_table_paiement_activite["Tel_personnel"]; ?></td>
                <td><?php echo $row_table_paiement_activite["Adresse_travail"]; ?></td>
                <td><?php echo $row_table_paiement_activite["Tel_travail"]; ?></td>
                <td><?php echo $row_table_paiement_activite["Cin"]; ?></td>
                <td><?php echo $row_table_paiement_activite["Profession"]; ?></td>
                <td>

             <a href="./parent.php?id=<?php echo $row_table_paiement_activite["ID"]; ?>"> <button type="button" class="btn btn-success mt-1"><i class="fa fa-edit"></i></button></a>
                    <?php  
                $idCHECK = $row_table_paiement_activite["ID"];
                $Query_checkinscription = "SELECT * FROM `etudiant_parent_tuteur` WHERE `Mather_id` = $idCHECK OR `Father_id` = $idCHECK OR `Etudiant_ID` = $idCHECK";
                $result_checkinscription = mysqli_query($conn,$Query_checkinscription);
                if(mysqli_num_rows($result_checkinscription) >0){
                                                                    //found
                }else{
                                                                    //not found
                        ?>
            <button name="DELET" value="<?php echo $row_table_paiement_activite["ID"]; ?>" type="submit" class="btn btn-danger mt-1"><i class="fa fa-trash"></i></button>
            <?php } ?>
            </td>
            </tr>
            <?php }?>



        </tbody>
        <tfoot>
            <tr>
            <th>ID</th>
                <th>Nom</th>
                <th>prenom</th>
                <th>Adresse personnelle</th>
                <th>Tel personnel</th>
                <th>Adresse travail</th>
                <th>Tel travail</th>
                <th>Cin</th>
                <th>Profession</th>
                <th>Action</th>


        </tfoot>
    </table>
    </form>
    </div>


<?php  include("../include/footer.php"); }else  if ( !isset($_SESSION['login'])) {
            header('Location: login.php');
        }?>
       <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
    $('#example').DataTable();
});
    </script>