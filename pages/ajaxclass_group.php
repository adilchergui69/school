
<?php 

session_start();
if ( !isset($_SESSION['login'])) {
    header('Location: login.php');
}
 
if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Director" || isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {
    include("../Connect/cnx.php");
    $input = $_POST['input'];



    $Query_groupett = "SELECT `ID`, `Nom` FROM `groupe` WHERE `Niveau_ID` = $input";
    $result_groupett = mysqli_query($conn,$Query_groupett);

    while ($row = mysqli_fetch_assoc($result_groupett)) {
        $ID_group = $row["ID"];
        $nom_group = $row["Nom"];
  
  
        echo "<option value='$ID_group'>$nom_group</option>";
   
  
    }




    ?>
    <div class="row text-center" id="results">


<?php while ($row = mysqli_fetch_assoc($result)) {
// printf("%s (%s)\n", $row["ID"], $row["Nom"]);
?>

    <!-- Team item -->
    <div class="col-xl-3 col-sm-6 mb-5">
    <a href="student.php?id=<?php echo $row["etudiants_id"]?>"> 
        <div class="bg-white rounded shadow-sm py-5 px-4"><img src="<?php if ( !$row['Photo']==null) {echo $row['Photo']; }else echo "https://cdn-icons-png.flaticon.com/512/183/183760.png"; ?>" alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
            <h5 class="mb-0"><?php echo $row["Prenom"]." ". $row["Nom"] ?> </h5><span class="small text-uppercase text-muted"><?php echo $row["Annee"] . "/" .$row["GROUPNOM"] ?></span>
            </a>
            <ul class="social mb-0 list-inline mt-3">
                <li class="list-inline-item"><a href="student.php?id=<?php echo $row["etudiants_id"]?>" class="social-link"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
                <li class="list-inline-item"><a href="payment.php?ids=<?php echo $row["etudiants_id"]?>" class="social-link"><i class="fa fa-money" aria-hidden="true"></i></a></li>
                <li class="list-inline-item"><a href="payment.php?id=<?php echo $row["etudiants_id"]?>" class="social-link"><i class="fa fa-usd" aria-hidden="true"></i></a></li>
            </ul>
        </div>
        
    </div><!-- End -->
    
    <?php }}
    
    ?>

   