
<?php 
// if ( !isset($_SESSION['login'])) {
//     header('Location: login.php');
// }

session_start();

if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Director" || isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {


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

$Query = "SELECT etudiant.ID as etudiants_id,etudiant.Prenom,etudiant.Nom,etudiant.Photo,etudiant.Langues_parlees_par_les_parents,inscription.Etu_ID,inscription.grop_ID,groupe.ID,groupe.Niveau_ID,groupe.Nom AS nomgroup,niveau.ID,niveau.Annee FROM etudiant JOIN inscription ON etudiant.ID = inscription.Etu_ID JOIN groupe ON groupe.ID = inscription.grop_ID JOIN niveau ON niveau.ID = groupe.Niveau_ID where inscription.Annee_scolaire = '$Annee_scolaireSQL'";
$result = mysqli_query($conn,$Query);



include("../include/nav.php");
?>
<script src="..\include\jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="../css\students.css">

<title><?php echo "$Titre"; ?> Admin - Les Élèves</title>
    <!-- MAIN -->
    <div class="col">
    <br><br>
<h4 class="text-right" style="text-align: center !important;">Liste étudiants</h4> <br>
        <!-- <h1>
            Collapsing Menu
            <small class="text-muted">Version 2.1</small>
        </h1>
         -->
        
<!-- For demo purpose -->

<section> 

          <div class="input-group mb-3 mt-3">
          <select class="form-control mr-3"  id="select" aria-label="Default select example">
                <option value="">Toutes</option>
                <option value="Nom">Nom</option>
                <option value="Niveaux">Niveaux</option>
          </select>    
          
          <input type="text" class="form-control" id="live_search"  placeholder="Etudiant..." >
            <div class="input-group-append">
              <button class="btn btn-outline-secondary bg-success text-white" name="submit" id="submit" type="button">soumettre</button>
            </div>
            
          </div>
      
       
</section>

<div class="row text-center" id="results">


    <?php while ($row = mysqli_fetch_assoc($result)) {
    // printf("%s (%s)\n", $row["ID"], $row["Nom"]);
?>

        <!-- Team item -->
        <div class="col-xl-3 col-sm-6 mb-5">
        <a href="student.php?id=<?php echo $row["etudiants_id"]?>"> 
        <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="<?php if ( !$row['Photo']==null|| !$row['Photo']=="" ) {echo "../uploads/". $row['Photo']; }else echo "https://cdn-icons-png.flaticon.com/512/183/183760.png";   ?>">
                <h5 class="mb-0"><?php echo $row["Prenom"]." ". $row["Nom"] ?> </h5><span class="small text-uppercase text-muted"><?php echo $row["Annee"] . "/" .$row["nomgroup"] ?></span>
                </a>
                <ul class="social mb-0 list-inline mt-3">
                    <li class="list-inline-item"><a href="student.php?id=<?php echo $row["etudiants_id"]?>" class="social-link"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
                    <li class="list-inline-item"><a href="payment.php?ids=<?php echo $row["etudiants_id"]?>" class="social-link"><i class="fa fa-trophy" aria-hidden="true"></i></a></li>
                    <li class="list-inline-item"><a href="payment.php?id=<?php echo $row["etudiants_id"]?>" class="social-link"><i class="fa fa-usd" aria-hidden="true"></i></a></li>
                </ul>
            </div>
            
        </div><!-- End -->

      
        <?php } include("../include/footer.php"); }else  if ( !isset($_SESSION['login'])) {
            header('Location: login.php');
        }?>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
  $(document).ready(function(){

    $("#live_search").keyup(function(){
var input = $(this).val();
var select = $("#select").val();
if (input != "") {
  $("#results").css("display", "block");

  $.ajax({
    url:"livesearch.php" ,
    method:"POST" ,
    data:{input:input, select:select} ,
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" type="text/javascript"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" type="text/javascript"></script>