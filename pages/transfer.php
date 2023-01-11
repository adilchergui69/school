
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


include("../Connect/cnx.php");

ob_start();

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

if (isset($_POST["updateANEE"])) {
  $begin = $_POST["begin"];
  $end = $_POST["end"];
  $Annee_scolaireSQL ="$begin-$end";
}
?>
<link rel="stylesheet" href="../css\students.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
<title><?php echo "$Titre"; ?> Admin - Élèves transférés</title>

<div class="col">
<br><br>
<h4 class="text-right" style="text-align: center !important;">Élèves transférés</h4> <br>

<form method="POST" onsubmit="return confirm ('Êtes-vous sûr?')">
<div class="form-row mt-5 justify-content-center">
<div class="form-group col-md-2">
<label  class="col-form-label">Année scolaire :</label>
    </div>

    <div class="form-group col-md-1">
      <input type="number" name="begin" value="<?php if (isset($_POST["updateANEE"])) {echo $begin;}else{echo $value_annee_setting_Begin; }?>" class="form-control" id="inputCity" min="2022" >
    </div>
    <div class="form-group col-md-0">
<label  class="col-form-label">-</label>
    </div>
    <div class="form-group col-md-1">
    <input type="number" name="end" value="<?php if (isset($_POST["updateANEE"])) {echo $end;}else{echo $value_annee_setting_End;} ?>" class="form-control" id="inputCity" min="2023" >
    </div>
    <div class="form-group col-md-1">
    <button class="btn btn-success mt-1 edit" name="updateANEE" type="submit" title="Edit"><i class="fa fa-search" ></i></button>
    </div>
    </div>
</form>

<form action="" method="POST" onsubmit="return confirm ('Êtes-vous sûr?')">
<div class="table-responsive mt-5">
        
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>       
          <th><input type="checkbox" class="checkall"></th>

            <th>ID</th>
                <th>Nom</th>
                <th>prenom</th>
                <th>Class</th>
                <th>Groupe</th>
                
                
                

            </tr>
        </thead>
        <tbody>

          <?php 

          $Query_table_paiement_activite = "SELECT etudiant.ID as etudiants_id,etudiant.Prenom,etudiant.Nom,etudiant.Photo,etudiant.Langues_parlees_par_les_parents,inscription.Etu_ID,inscription.grop_ID,groupe.ID,groupe.Niveau_ID,groupe.Nom AS nomgroup,niveau.ID,niveau.Annee FROM etudiant JOIN inscription ON etudiant.ID = inscription.Etu_ID JOIN groupe ON groupe.ID = inscription.grop_ID JOIN niveau ON niveau.ID = groupe.Niveau_ID where inscription.Annee_scolaire = '$Annee_scolaireSQL'";
          $result_table_paiement_activite = mysqli_query($conn,$Query_table_paiement_activite);
        
          while ($row_table_paiement_activite = mysqli_fetch_assoc($result_table_paiement_activite)) {
          
          ?>
            <tr>
            <td><input type="checkbox" name="check_list[]" value="<?php echo $row_table_paiement_activite["etudiants_id"]; ?>"></td>
                <td> <a href="./student.php?id=<?php echo $row_table_paiement_activite["etudiants_id"]; ?>" target="_blank"><?php echo $row_table_paiement_activite["etudiants_id"]; ?></a> </td>
                <td><?php echo $row_table_paiement_activite["Nom"]; ?></td>
                <td><?php echo $row_table_paiement_activite["Prenom"];?></td>
                <td><?php echo $row_table_paiement_activite["Annee"]; ?></td>
                <td><?php echo $row_table_paiement_activite["nomgroup"];?></td>
    

            </tr>
            <?php }?>



        </tbody>
        <tfoot>
            <tr>
            <th><input type="checkbox" class="checkall"></th>
            <th>ID</th>
                <th>Nom</th>
                <th>prenom</th>
                <th>Class</th>
                <th>Groupe</th>
 

          </tr>

        </tfoot>
    </table>

    </div >
    
    <div class=" m-2 mt-1">
   

    <div class="form-row">
    <div class="col">
    <label for="inputEmail4">Class</label>
    <select required class="custom-select" name="select_Annee"  id="nivo">
    <option selected disabled class="bg-secondary text-white">Choisir Class...</option>


<?php 

  



$Query = "SELECT `ID`, `Annee` FROM `niveau`";
$result = mysqli_query($conn,$Query);

while ($row = mysqli_fetch_assoc($result)) {


$ID_class =  $row["ID"];
$name_class = $row["Annee"];
echo "<option value='$ID_class'>$name_class</option>";

}


?>   
</select>
</div>
    <div class="col">
    <label for="inputEmail4">Groupe</label>
    <select class="custom-select" name="select_GROUP" id="results">

 
   </select>
    </div>
    <div class="col">
    <label for="inputEmail4">Année scolaire</label>
    <select required class="custom-select mb-5" name="select_Annee_scolaire" id="inputGroupSelect04">
    <option value="<?php   $Annee_scolaireSQL ="$value_annee_setting_Begin-$value_annee_setting_End"; echo $Annee_scolaireSQL; ?>"><?php echo $Annee_scolaireSQL; ?></option>
</select>

 
   </select>
    </div>
  </div>
    </div>
    <button type="submit" class="btn btn-primary m-5" name="Enregistrer" style="margin-left: 45% !important;">Enregistrer</button>

    </form> 
    </div>

<?php if (isset($_POST['check_list']) && isset($_POST['select_Annee_scolaire']) && isset($_POST['Enregistrer']) && isset($_POST['select_Annee']) && isset($_POST['select_GROUP'])) {
    
    $select_Annee = $_POST['Enregistrer'];
    $select_GROUP = $_POST['select_GROUP'];
    $select_Annee_scolaire = $_POST['select_Annee_scolaire'];

    $dateTIMEnow = date("Y-m-d H:i:s");
    $nameUSER = $_SESSION['login'][2];
    $prenomeUSER =  $_SESSION['login'][3];

    foreach($_POST['check_list'] as $ID_student) {

    

      $Query_student = "SELECT * FROM etudiant WHERE ID=$ID_student";
      $result_student = mysqli_query($conn,$Query_student);
      $row_student = mysqli_fetch_assoc($result_student);
      $name_student = $row_student['Nom'];
      $Prenom_student = $row_student['Prenom'];
  
      $Query_nivoGROUYP = "SELECT groupe.ID, groupe.Nom, groupe.niveau_ID,niveau.ID,niveau.Annee FROM groupe JOIN niveau ON groupe.niveau_ID = niveau.ID WHERE groupe.ID = $select_GROUP";
      $result_Query_nivoGROUYP = mysqli_query($conn,$Query_nivoGROUYP);
      $row_nivoGROUYP = mysqli_fetch_assoc($result_Query_nivoGROUYP);
      $namegroupqr = $row_nivoGROUYP["Nom"];
       $nameNIVO = $row_nivoGROUYP["Annee"];

      $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Mettre à jour','$dateTIMEnow','$name_student $Prenom_student','Étudiant a été transféré au département: $nameNIVO _ groupe: $namegroupqr','$ip_address')";
      mysqli_query($conn,$Query_spy);

      $Query_checkinscription = "SELECT * FROM `inscription` WHERE `Etu_ID` = $ID_student AND `Annee_scolaire` = '$Annee_scolaireSQL'";
      $result_checkinscription = mysqli_query($conn,$Query_checkinscription);

        if(mysqli_num_rows($result_checkinscription) > 0 ){


          $Query_update = "UPDATE inscription SET grop_ID ='$select_GROUP',Annee_scolaire ='$select_Annee_scolaire' WHERE Etu_ID = $ID_student AND `Annee_scolaire` = '$Annee_scolaireSQL'";
          mysqli_query($conn,$Query_update);

  }else{

    $Query_insert = "INSERT INTO `inscription`(`Etu_ID`, `grop_ID`, `Annee_scolaire`) VALUES ($ID_student,$select_GROUP,'$select_Annee_scolaire')";
          mysqli_query($conn,$Query_insert);

       
    }
  }
    header("Refresh:0");

    ob_end_flush();   

}  include("../include/footer.php"); }else  if ( !isset($_SESSION['login'])) {
            header('Location: login.php');
        }?>
       <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".checkall").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

       
    $('#example').DataTable();
});
    </script>

    <script>
  $(document).ready(function(){

    $("#nivo").change(function(){
     
var input = $(this).val();
var select = $("#select").val();
if (input != "") {
  $("#results").css("display", "block");

  $.ajax({
    url:"ajaxclass_group.php" ,
    method:"POST" ,
    data:{input:input} ,
    success:function(data){
      $("#results").html(data);
    }
  });
}else{
  $("#results").css("display", "none");
}
      
    })
    
  })
</script>