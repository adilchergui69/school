<?php
//  echo md5("adil2019");



$ip_address = "";

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

  if (!$ip_address == "") {

 

    session_start();
    include("../Connect/cnx.php");

    $Query_ips = "INSERT INTO `ip_handlers`(`IPS`) VALUES ('$ip_address') ON DUPLICATE KEY UPDATE IPS=VALUES(IPS);";
    mysqli_query($conn,$Query_ips);
    // mysqli_close($conn);
  
    $Query_ips_check = "SELECT `IPS`,`failed_login` FROM `ip_handlers` WHERE `IPS` = '$ip_address';";
    $results_ips_check = mysqli_query($conn,$Query_ips_check);
    $row_ips_check = mysqli_fetch_assoc($results_ips_check);
    $failed_login = $row_ips_check["failed_login"];
    // mysqli_close($conn);

    if ($failed_login < 3) {
     
    

    if (!isset($_SESSION['login']))
{

}

if (isset($_POST['submit']) && !empty($_POST['Email']) 
&& !empty($_POST['Password'])) {
    $Email = htmlspecialchars(trim(strtolower($_POST['Email'])));
    $Password = md5($_POST['Password']);
    $Query = "SELECT * FROM users WHERE Email = '$Email' && Mot_de_passe = '$Password'";
    
if (mysqli_num_rows($result = mysqli_query($conn,$Query))) {

  $Query_ips = "INSERT INTO `ip_handlers`(`IPS`, `failed_login`, `success_login`) VALUES ('$ip_address','0','0') ON DUPLICATE KEY UPDATE success_login=success_login+1,failed_login=0;";
  mysqli_query($conn,$Query_ips);
  // mysqli_close($conn);

    $row=mysqli_fetch_assoc( $result );
    $ID =$row['ID'];
    $Nom_d_utilisateur =$row['Nom_d_utilisateur'];
    $Nom =$row['Nom'];
    $Prenom =$row['Prenom'];
    $Email =$row['Email'];
    $Tel =$row['Tel'];
    $Role =$row['Role'];
    $your_array = [$ID,$Nom_d_utilisateur,$Nom,$Prenom,$Email,$Tel,$Role];
    $_SESSION['login'] = $your_array;
   
    
    $dateTIMEnow = date("Y-m-d H:i:s");
    $nameUSER = $_SESSION['login'][2]; 
    $prenomeUSER =  $_SESSION['login'][3];

    $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Connexion','$dateTIMEnow','$nameUSER $prenomeUSER','Il est connecté avec succès','$ip_address')";
    mysqli_query($conn,$Query_spy);
    mysqli_close($conn);
}else{
  if ($failed_login == 0) {
   echo "<div class='alert alert-danger' role='alert'> Votre mot de passe ou email est incorrect, veuillez les vérifier. (Il ne vous reste que <span style='color: red; font-size: 25px;'> 2 </span>tentatives!) </div>";
} elseif ($failed_login == 1) {
  echo "<div class='alert alert-danger' role='alert'> Votre mot de passe ou email est incorrect, veuillez les vérifier. (Il ne vous reste que <span style='color: red; font-size: 25px;'> 1 </span>tentatives!) </div>";
} elseif ($failed_login == 2) {
  echo "<div class='alert alert-danger' role='alert'> <span style='color: red; font-size: 25px;'> (Parlez au support pour résoudre les problèmes) </span> </div>";

}elseif ($failed_login == 3) {
  echo "<div class='alert alert-danger' role='alert'> <span style='color: red; font-size: 25px;'> (Parlez au support pour résoudre les problèmes) </span> </div>";
}

  $Query_ips = "INSERT INTO `ip_handlers`(`IPS`, `failed_login`, `success_login`) VALUES ('$ip_address','0','0') ON DUPLICATE KEY UPDATE failed_login=failed_login+1;";
  mysqli_query($conn,$Query_ips);
  mysqli_close($conn);
}




}



if (  isset($_SESSION['login'])) {
 
 if (isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {

  header('Location: index.php');
 }

 if (isset($_SESSION['login']) && $_SESSION['login'][6] ==="Director" ) {

  header('Location: students.php');
 }

    echo '
    <form method="POST">
        <input name="déconnecter" type="submit" value="déconnecter" />
    </form>';

//     if (isset($_POST['déconnecter'])) {
   
//         session_destroy();      
//         unset($_SESSION['login']);
// }




    if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Director" ) {

        echo 'hello'. $_SESSION['login'][2];
    
        }

        if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {

        
            }


if (isset($_POST["déconnecter"]) && isset($_SESSION['login'])) {
  $dateTIMEnow = date("Y-m-d H:i:s");
  $nameUSER = $_SESSION['login'][2]; 
  $prenomeUSER =  $_SESSION['login'][3];

  $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Déconnecter','$dateTIMEnow','$nameUSER $prenomeUSER','Il est déconnecté avec succès','$ip_address')";
mysqli_query($conn,$Query_spy);
mysqli_close($conn);

  session_destroy();

}
      

    }

    if ( !isset($_SESSION['login'])) {

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/login.css">


</head>
<body>
  <div class=" vertical-center text-center">
    <div class="container">
      <div class="row">
        <div class="col-md-6 offset-md-3">
          <form class="form p-4 my-4" method="POST">
            <img class="mb-4" src="../img/logo.png" width="200">
            <h1 class="h3 mb-3 fw-normal">Admin Login</h1>

            <div class="form-floating">
              <input type="email" name="Email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
              <label for="floatingInput">Email</label>
            </div>
            <div class="form-floating password">
              <input type="password" name="Password" class="form-control" id="floatingPassword" placeholder="Password" required>
              <label for="floatingPassword">Mot de passe</label>
            </div>

            <div class="checkbox mb-3">
              <label>
                <input class="form-check-input" type="checkbox" value="remember-me"> souviens-toi de moi
              </label>
            </div>
            <button name="submit" class="w-100 btn btn-lg btn-primary" type="submit">connexion</button>
            <p class="mt-5 mb-3 text-muted">
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<script></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
</body>
</html>

<?php 
  }}else{ echo'<img src="..\img\funny.jpg" alt="Girl in a jacket" style="width: 100%;">';} 
  }else  if ( !isset($_SESSION['login'])) {
  header('Location: login.php');
}
//  echo md5("adilchergui");
?>

