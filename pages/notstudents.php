
<?php 
// if ( !isset($_SESSION['login'])) {
//     header('Location: login.php');
// }

session_start();

if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Director" || isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {


include("../Connect/cnx.php");

$Query = "SELECT * FROM etudiant WHERE statut IS null";
$result = mysqli_query($conn,$Query);



include("../include/nav.php");
?>


<link rel="stylesheet" href="../css\students.css">

<title><?php echo "$Titre"; ?> Admin - Nouveaux inscrits</title>

    <!-- MAIN -->
    <div class="col">
        
        <!-- <h1>
            Collapsing Menu
            <small class="text-muted">Version 2.1</small>
        </h1>
         -->
        
<!-- For demo purpose -->
<br><br>
<h4 class="text-right" style="text-align: center !important;">Nouveaux inscrits</h4> <br>
<section> 

          <div class="input-group mb-3 mt-3">
          <select class="form-control mr-3"  id="select" aria-label="Default select example">
                <option value="">toutes</option>
                <option value="Nom">Nom</option>
          </select>    
          
          <input type="text" class="form-control" id="live_search"  placeholder="ID etudiant" >
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
        <a href="student.php?id=<?php echo $row["ID"]?>"> 
        <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="<?php if ( !$row['Photo']==null|| !$row['Photo']=="" ) {echo "../uploads/". $row['Photo']; }else echo "https://cdn-icons-png.flaticon.com/512/183/183760.png";   ?>">
                <h5 class="mb-0"><?php echo $row["Prenom"]." ". $row["Nom"] ?> </h5><span class="small text-uppercase text-muted"></span>
                </a>
                <ul class="social mb-0 list-inline mt-3">
                    <li class="list-inline-item"><a href="student.php?id=<?php echo $row["ID"]?>" class="social-link"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
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
    url:"livesearchNOTSECRIR.php" ,
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