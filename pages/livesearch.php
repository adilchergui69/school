
<?php 

session_start();
if ( !isset($_SESSION['login'])) {
    header('Location: login.php');
}
 
if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Director" || isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {
    include("../Connect/cnx.php");
    $input = $_POST['input'];
    $select = $_POST['select'];

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

if ($select =="") {
    $Query = "SELECT etudiant.ID,etudiant.Prenom,etudiant.Nom,etudiant.Photo,etudiant.Langues_parlees_par_les_parents,inscription.Etu_ID,inscription.grop_ID,groupe.ID,groupe.Niveau_ID,groupe.Nom AS GROUPNOM,niveau.ID,niveau.Annee FROM etudiant JOIN inscription ON etudiant.ID = inscription.Etu_ID JOIN groupe ON groupe.ID = inscription.grop_ID JOIN niveau ON niveau.ID = groupe.Niveau_ID  where inscription.Annee_scolaire = '$Annee_scolaireSQL' AND  etudiant.Prenom LIKE '{$input}%' OR etudiant.Nom LIKE '{$input}%' OR etudiant.ID LIKE '{$input}%' OR groupe.Nom LIKE '{$input}%' OR niveau.Annee LIKE '{$input}%' ";

}

if ($select =="Nom") {
    $Query = "SELECT etudiant.ID,etudiant.Prenom,etudiant.Nom,etudiant.Photo,etudiant.Langues_parlees_par_les_parents,inscription.Etu_ID,inscription.grop_ID,groupe.ID,groupe.Niveau_ID,groupe.Nom AS GROUPNOM,niveau.ID,niveau.Annee FROM etudiant JOIN inscription ON etudiant.ID = inscription.Etu_ID JOIN groupe ON groupe.ID = inscription.grop_ID JOIN niveau ON niveau.ID = groupe.Niveau_ID  where inscription.Annee_scolaire = '$Annee_scolaireSQL' AND etudiant.Prenom LIKE '{$input}%' OR etudiant.Nom LIKE '{$input}%'";

}

if ($select =="Niveaux") {
    $Query = "SELECT etudiant.ID,etudiant.Prenom,etudiant.Nom,etudiant.Photo,etudiant.Langues_parlees_par_les_parents,inscription.Etu_ID,inscription.grop_ID,groupe.ID,groupe.Niveau_ID,groupe.Nom AS GROUPNOM,niveau.ID,niveau.Annee FROM etudiant JOIN inscription ON etudiant.ID = inscription.Etu_ID JOIN groupe ON groupe.ID = inscription.grop_ID JOIN niveau ON niveau.ID = groupe.Niveau_ID  where inscription.Annee_scolaire = '$Annee_scolaireSQL' AND niveau.Annee LIKE '{$input}%'";
}
    $result = mysqli_query($conn,$Query);


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

   