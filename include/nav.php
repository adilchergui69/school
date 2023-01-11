<?php 


// session_start();
if ( !isset($_SESSION['login'])) {
    header('Location: login.php');
}
if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Director" || isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {
    
    
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

    $dateTIMEnow = date("Y-m-d H:i:s");
    $nameUSER = $_SESSION['login'][2]; 
    $prenomeUSER =  $_SESSION['login'][3];


    ?>
<html lang="en"><head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="76x76" href="https://demos.creative-tim.com/light-bootstrap-dashboard/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="https://demos.creative-tim.com/light-bootstrap-dashboard/assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS Files -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css\nav.css">
    <body>
    <div class="loader-wrapper">
      <span class="loader"><span class="loader-inner"></span></span>
    </div>
    <style>
 <?php 


 $Query_checkcolors = "SELECT * FROM `setting` WHERE `Namess` = 'white'
 OR `Namess` = 'black' OR `Namess` = 'grey1' OR `Namess` = 'grey2' 
 OR `Namess` = 'blue' OR `Namess` = 'green' OR `Namess` = 'red' OR `Namess` = 'logo' OR `Namess` = 'Titre'";
$result_checkcolors = mysqli_query($conn,$Query_checkcolors);

$color_Arry=[];

if (mysqli_num_rows($result_checkcolors) > 8 ) {
    

while ($row_colors = mysqli_fetch_assoc($result_checkcolors)) {

    array_push($color_Arry,$row_colors["Valuess"]);

    }

    $color_white = $color_Arry[0];
    $color_black = $color_Arry[1];
    $color_grey1 = $color_Arry[2];
    $color_grey2 = $color_Arry[3];
    $color_blue = $color_Arry[4];
    $color_green = $color_Arry[5];
    $color_red = $color_Arry[6];
    $logo = $color_Arry[7];
    $Titre = $color_Arry[8];


}

?>
 /* white */
 
 <?php 
 if ($color_white != "#ffffff" ) {
   echo
    "
    .fa{
        color: $color_white !important;
     }
    .submenu-icon{
        color: $color_white !important;
    }
     .menu-collapsed{
        color: $color_white !important;
    }
    
    .row{
     background-color: $color_white!important;
    }
    .card-body{
        background-color: $color_white !important;
    }
    
    
    .card{
        background-color: $color_white !important;
    }
    
    
    

    
    .dropdown-menu{
    
        background-color: $color_white !important;
    }
   
   ";
 }


 ?>


 /* white */


 /* black */

 <?php 
 if ($color_black != "#000" ) {
   echo"

   #collapse-text{
    color: $color_black !important;
}
#collapse-icon{
    color: $color_black !important;
}

   input{
    color: $color_black!important;
}
td{
    color: $color_black!important;
}
th{
    color: $color_black!important;
}

.text-right{
    color: $color_black!important;
}
.col{
    color: $color_black!important;
}
label{
    color: $color_black!important;
}
   ";
 }

 ?>
 


 /* black */


/* blue */

<?php 
 if ($color_blue != "#007bff" ) {
echo
"
.bg-primary {
    background-color: $color_blue !important;
}

.badge-primary{
    background-color: $color_blue !important;
}

.btn-primary {

    background-color: $color_blue !important;
    border-color: $color_blue !important;
}

.btn-link:hover{
    background-color: $color_blue !important;
}

";

}

 ?>

 
/* blue */

/* green */

<?php 
 if ($color_green != "#28a745" ) {
    echo
    "
    .btn-success{

        background-color: $color_green !important;
        border-color: $color_green !important;
    
    }
    
    .bg-success {
    
    background-color: $color_green !important;
    
    }

    ";
 }

 ?>



/* green */


/* red */
<?php 
 if ($color_red != "#dc3545" ) {
   
    echo
"
.btn-danger{
    background-color: $color_red !important;;
}

.bg-danger{
    background-color: $color_red !important;;
}

";

 }

 ?>



/* red */

/* grey  1  */

<?php 
 if ($color_grey1 != "#333333" ) {
    echo
    "
    .sidebar-separator-title{

        background-color: $color_grey1 !important;
    }
    
    #sidebar-container{
    
        background-color: $color_grey1 !important;
    }
    ";

 }

 ?>


/* grey  1  */


/* grey  2  */

<?php 
 if ($color_grey2 != "#343a40" ) {
    echo
"
.bg-dark{

    background-color: $color_grey2 !important;
    }

";

 }

 ?>




/* grey  2  */



.content {
  display: flex;
  justify-content: center;
  align-items: center;
  width:100%;
  height:100%;
}
.loader-wrapper {
  position: fixed !important;
  z-index: 5;
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  background-color: #333;
  display:flex;
  justify-content: center;
  align-items: center;
}
.loader {
  display: inline-block;
  width: 30px;
  height: 30px;
  position: relative;
  border: 4px solid #Fff;
  animation: loader 2s infinite ease;
}
.loader-inner {
  vertical-align: top;
  display: inline-block;
  width: 100%;
  background-color: #fff;
  animation: loader-inner 2s infinite ease-in;
}

@keyframes loader {
  0% { transform: rotate(0deg);}
  25% { transform: rotate(180deg);}
  50% { transform: rotate(180deg);}
  75% { transform: rotate(360deg);}
  100% { transform: rotate(360deg);}
}

@keyframes loader-inner {
  0% { height: 0%;}
  25% { height: 0%;}
  50% { height: 100%;}
  75% { height: 100%;}
  100% { height: 0%;}
}

/* The important part */

:root {
color-scheme: light dark;
}

.dark-mode ::-moz-selection {
color: #000;
background-color: #ddd;
}

.dark-mode ::selection { 
color: #000;
background-color: #ddd;
}    

body.dark-mode,
.dark-mode dialog {
color: #eee;
background-color: #121212;
font-weight: 350;
}

.dark-mode img {
filter: brightness(0.8) contrast(1.2);
}

.dark-mode a[href] {
color: #fff;
}

.dark-mode code, 
.dark-mode kbd {
background-color: #444;
}

.dark-mode select:not([multiple]):not([size]),
.dark-mode select, 
.dark-mode input, 
.dark-mode textarea {
color: #fff;
background-color: #333;
caret-color: #fff;
}

.dark-mode [type='range'] {
background: transparent;
}

.dark-mode button,
.dark-mode [role='button'],
.dark-mode [type='button'],
.dark-mode [type='reset'],
.dark-mode [type='submit'],
.dark-mode input::-webkit-file-upload-button {
background-color: #555;
}


/* The Light Switch */

.light-switch {
background: #fff no-repeat 50% 50% / 2em;
padding: 1em;
border-radius: 50%;
}

body .light-switch {
background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1024' height='1024'%3E%3Cpath d='M349 242c0 242 165 438 370 438 51 0 99-12 143-34a378 378 0 11-507-480c-4 25-6 50-6 76z' fill='black'/%3E%3C/svg%3E");
}

body .light-switch:hover {
box-shadow: 0 0 5px #333;
}

body.dark-mode .light-switch {
background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1024' height='1024'%3E%3Cpath d='M257 528a240 240 0 10480 0 240 240 0 00-480 0zm240-408l-67 135h135zm288 119l-143 47 96 96zm-433 47l-143-47 47 143zM223 459L88 527l135 67zm546 138l135-68-135-67zM497 936l67-135H429zm145-166l143 47-47-143zm-433 47l143-47-96-96z' fill='white'/%3E%3C/svg%3E");
}

body.dark-mode .light-switch:hover {
box-shadow: 0 0 5px #fff;
}

</style>




    <!-- Bootstrap NavBar -->
<nav class="navbar navbar-expand-md navbar-dark bg-primary">
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="#">
    <img src="../img/<?php echo $logo; ?>" width="30" height="30" class="d-inline-block align-top" alt="">
    <span class="menu-collapsed"><?php echo "$Titre"; ?></span>
  </a>

  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
        <?php  if (isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {
            
            ?>
      <li class="nav-item">
        <a class="nav-link" href="index.php">Dashboard <span class="sr-only">(current)</span></a>
      </li>
      <?php }?>
      <li class="nav-item">
        <a class="nav-link"href="students.php" >Étudiants</a>
      </li>
      <?php  if (isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {
            
            ?>
<li class="nav-item">
        <a class="nav-link" href="../scanQR/index.php">Paiements</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Supplément.php">Supplément</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="notification.php">Notification</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="spy.php">SPY</a>
      </li>
      

      <?php }?>



      

      <!-- This menu is hidden in bigger devices with d-sm-none. 
           The sidebar isn't proper for smaller screens imo, so this dropdown menu can keep all the useful sidebar itens exclusively for smaller screens  -->
      <li class="nav-item dropdown d-sm-block d-md-none">
        <a class="nav-link dropdown-toggle" href="#" id="smallerscreenmenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Menu
        </a>
        <div class="dropdown-menu" aria-labelledby="smallerscreenmenu">
        <?php  if (isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {?>
            <a class="dropdown-item" href="index.php">Dashboard</a>
            <?php }?>    
            <a class="dropdown-item" href="add_class.php">Inscription étudiants</a>
            <a class="dropdown-item" href="departments.php">Ajouter classe/activites</a>
            <a class="dropdown-item" href="students.php">Liste étudiants</a>
            <a class="dropdown-item" href="notstudents.php">Nouveaux inscrits</a>
            <a class="dropdown-item" href="transfer.php">Élèves transférés</a>
            <?php  if (isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {?>
            <a class="dropdown-item" href="backup.php">Backup</a>
            <a class="dropdown-item" href="user.php">utilisateurs</a>
            <a class="dropdown-item" href="Setting.php">Paramètre</a>
            <?php }?> 
            <form action="login.php" method="POST">
            <button value="déconnecter" name="déconnecter" type="submit" class="btn list-group-item list-group-item-action d-flex align-items-center pl-4 mt-4">
            
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <span id="collapse-icon" class="fa fa-sign-out mr-3"></span>
                    
                    <span id="collapse-text" class="menu-collapsed">Déconnecter</span>
                </div>
                
   
            </button>
            </form>
        </div>
      </li><!-- Smaller devices menu END -->
      
    </ul>
  </div>


    <button id="btnDarkMode" class="light-switch my-2 my-sm-0"></button>

</nav><!-- NavBar END -->


<!-- Bootstrap row -->
<div class="row" id="body-row">
    <!-- Sidebar -->
    <div id="sidebar-container" class="sidebar-expanded d-none d-md-block"><!-- d-* hiddens the Sidebar in smaller devices. Its itens can be kept on the Navbar 'Menu' -->
        <!-- Bootstrap List Group -->
        <ul class="list-group">
            <!-- Separator with title -->
            <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                <small>MENU PRINCIPAL</small>
            </li>

            <?php  if (isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {
            
            ?>
 <a href="index.php" class="bg-dark list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <span class="fa fa-dashboard fa-fw mr-3"></span>
                    <span class="menu-collapsed">Dashboard</span>
                </div>
            </a>
      <?php }?>

           
      
            <!-- /END Separator -->
            <!-- Menu with submenu -->
            <a href="#submenu1" data-toggle="collapse" id="Class" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <span class="fa fa-cog fa-fw mr-3"></span> 
                    <span class="menu-collapsed" >Gestion Classe</span>
                    <span class="submenu-icon ml-auto"></span>
                </div>
            </a>
        
            <!-- Submenu content -->
            <div id='submenu1' class="collapse sidebar-submenu">

                <a href="departments.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <span class="menu-collapsed">Ajouter classe/activites</span>
                </a>

            </div>
            
        
            <a href="#submenu2" data-toggle="collapse" id="student" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <span class="fa fa-user fa-fw mr-3"></span>
                    <span class="menu-collapsed">Gestion Étudiants</span>
                    <span class="submenu-icon ml-auto"></span>
                </div>
            </a>
            <!-- Submenu content -->
            <div id='submenu2' class="collapse sidebar-submenu">

            
                <a href="notstudents.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <span class="menu-collapsed">Liste Nouveaux inscrits</span>
                </a>
                <a href="students.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <span class="menu-collapsed">Liste étudiants</span>
                </a>
                <a href="add_class.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <span class="menu-collapsed">Inscription étudiants</span>
                </a>
                <a href="transfer.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <span class="menu-collapsed">Élèves transférés</span>
                </a>
            </div>         
               
            <!-- <a href="#" class="bg-dark list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <span class="fa fa-tasks fa-fw mr-3"></span>
                    <span class="menu-collapsed">Tasks</span>    
                </div>
            </a> -->
            <!-- Separator with title -->
            <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                <small>Outils</small>
            </li>
            


            <!-- /END Separator -->

            <a href="search.php" class="bg-dark list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <span class="fa fa-search fa-fw mr-3"></span>
                    <span class="menu-collapsed">Rechercher</span>
                </div>
            </a>

            <?php  if (isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin") { 
                

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
                
    $thisMOTH = date("m");
    $thisYEAR = date("Y");
    

    $ins_notPAYid = [];
    $IDSinscription = "SELECT paiement.Montant, SUM(paiement.Montant + etudiant.Remise) AS totall,paiement.Date_de_paiement,paiement.Ins_ID AS IDpayements,paiement.Debut_de_validite,paiement.Fin_de_validite, inscription.Etu_ID,inscription.ID as insID,inscription.grop_ID, etudiant.ID AS etudiantsID,etudiant.Prenom,etudiant.Nom AS etudiant_Noms ,etudiant.Remise,etudiant.Vit_avec,groupe.*,groupe.Nom AS groupNAM,niveau.* FROM inscription JOIN paiement ON paiement.Ins_ID = inscription.ID JOIN etudiant ON etudiant.ID = inscription.Etu_ID JOIN groupe ON groupe.ID = inscription.grop_ID JOIN niveau ON niveau.ID = groupe.Niveau_ID WHERE YEAR(paiement.Date_de_paiement) = $thisYEAR and inscription.Annee_scolaire = '$Annee_scolaireSQL' GROUP BY IDpayements order by IDpayements DESC ;";
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
        $price_class_thismothTOTALL = $price_class * $thisMOTH;
        $Totall_pay = $ROW_test["totall"];

        if ($Totall_pay<$price_class_thismothTOTALL) {
            array_push($ins_notPAYid, $ROW_test["totall"]);
        }
     }

    $newINSCRIPTIONnotPAY = "SELECT inscription.ID,inscription.Etu_ID,etudiant.ID AS etudiantsID,etudiant.Prenom,etudiant.Nom,etudiant.Vit_avec FROM inscription JOIN etudiant ON etudiant.ID = inscription.Etu_ID WHERE NOT EXISTS (SELECT * FROM paiement WHERE paiement.Ins_ID = inscription.ID) AND etudiant.statut = 1 and inscription.Annee_scolaire = '$Annee_scolaireSQL';";
    $TESTforNEWins = mysqli_query($conn,$newINSCRIPTIONnotPAY);

    while ($ROW_NEWinst = mysqli_fetch_assoc($TESTforNEWins)) {
        
            array_push($ins_notPAYid, 0);
        
     }

     $_SESSION['count'] =  count($ins_notPAYid);
 
    
//      for ($i=0; $i < count($arryIDinscription); $i++) { 
        
//         $getprice = "SELECT inscription.Etu_ID,inscription.ID as insID,inscription.grop_ID,etudiant.ID,etudiant.Prenom,etudiant.Nom,etudiant.Remise,etudiant.Vit_avec,groupe.ID AS GROIDIS, groupe.Niveau_ID,niveau.ID AS NIVOids,niveau.Price FROM inscription JOIN etudiant ON etudiant.ID = inscription.Etu_ID JOIN groupe ON groupe.ID = inscription.grop_ID JOIN niveau ON niveau.ID = groupe.Niveau_ID WHERE inscription.ID = $arryIDinscription[$i]";
//         $conectTOfgetPRICEy = mysqli_query($conn,$getprice);
//         $ROWresltPRICE = mysqli_fetch_assoc($conectTOfgetPRICEy);
        
//     $IDSinscription = "SELECT SUM(`Montant`) FROM `paiement` WHERE Ins_ID = $arryIDinscription[$i]";
//     $IDSinscriptionRESULT = mysqli_query($conn,$IDSinscription);
//     $ROWreslt = mysqli_fetch_assoc($IDSinscriptionRESULT);
    
//     if ($ROWresltPRICE["Remise"] > 0) {
    
//         $defaultPRICE= $ROWresltPRICE["Price"] - $ROWresltPRICE["Remise"];
        
    
//     }
    
//     if ($ROWresltPRICE["Remise"] == 0) {
//         $defaultPRICE= $ROWresltPRICE["Price"];
        
    
//     }
    
//     $realPRICE = $thisMOTH*$defaultPRICE;
    
//     if ($realPRICE > $ROWreslt["SUM(`Montant`)"] ) {
//         array_push($ins_notPAYid, $arryIDinscription[$i]);
    
//     }
// }
 }

// $_SESSION['count'] =  count($ins_notPAYid);






                
                ?>
                 
            <a href="notification.php" class="bg-dark list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-start align-items-center">
                <i class="fa fa-bell-o mr-3" aria-hidden="true"></i>
                    <span class="menu-collapsed">Notification <span class="badge badge-pill bg-danger ml-2"><?php if (isset($_SESSION['count'])) { echo $_SESSION['count']; }?></span></span>
                </div>
            </a>

            <a href="user.php" class="bg-dark list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-start align-items-center">
                <i class="fa fa-user mr-3" aria-hidden="true"></i>
                    <span class="menu-collapsed">Utilisateurs </span>
                </div>
            </a>
          
            <a href="spy.php" class="bg-dark list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-start align-items-center">
                <i class="fa fa-user-secret mr-3" aria-hidden="true"></i>
                    <span class="menu-collapsed">SPY </span>
                </div>
            </a>

            <a href="setting.php" class="bg-dark list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-start align-items-center">
                <i class="fa fa-cog mr-3" aria-hidden="true"></i>
                    <span class="menu-collapsed">Paramètre </span>
                </div>
            </a>


            <!-- Separator without title -->
            
            <!-- /END Separator -->
            <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                <small>Paiements</small>
            </li>
            
            <a href="../scanQR/index.php" target="_blank"class="bg-dark list-group-item list-group-item-action">
               
            <div class="d-flex w-100 justify-content-start align-items-center">
                    <i class="fa fa-usd mr-3" aria-hidden="true"></i>

                    <span class="menu-collapsed">Paiements</span>
                </div>

            </a>
            
            <?php  if (isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {
            
            ?>
            
            <a href="Supplément.php" class="bg-dark list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <span class="fa fa-shopping-cart fa-fw mr-3"></span>
                    <span class="menu-collapsed">Supplément</span>
                </div>
            </a>
            <?php }?>

<li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                <small>Backup</small>
            </li>
            <a href="backup.php" class="bg-dark list-group-item list-group-item-action">
               
               <div class="d-flex w-100 justify-content-start align-items-center">
                       <i class="fa fa-database mr-3" aria-hidden="true"></i>
   
                       <span class="menu-collapsed">Backup</span>
                   </div>
   
               </a>
   

            
            <?php }?>
            <form action="login.php" method="POST">
            <button value="déconnecter" name="déconnecter" type="submit" class="btn list-group-item list-group-item-action d-flex align-items-center pl-4 mt-4">
            
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <span id="collapse-icon" class="fa fa-sign-out mr-3"></span>
                    
                    <span id="collapse-text" class="menu-collapsed">Déconnecter</span>
                </div>
                
   
            </button>
            </form>


            <!-- Logo -->
            <!-- <li class="list-group-item logo-separator d-flex justify-content-center">
                <img src='../img/logo.png' width="50" height="50" />    
            </li>    -->
        </ul><!-- List Group END-->
    </div><!-- sidebar-container END -->
<?php if (isset($_POST["déconnecter"])) {


session_destroy();


}
 }else  if ( !isset($_SESSION['login'])) {
    header('Location: login.php');
}
   
 ?>
 <script>

var activePage = window.location.pathname;


const navLinks = document.querySelectorAll('nav a').forEach(link => {
  if(link.href.includes(`${activePage}`)){
    link.classList.add('active');
    
  }
})

setTimeout(function(){
   
const str = window.location.href;
const char = ['departments.php'];
const bar = ['notstudents.php','students.php','transfer.php','add_class.php'];

count1 = 0;
count2 = 0;
for (let i = 0; i < char.length; i++) {
   
  if(str.includes(char[i])){
      count1++
  }
}

if(count1 > 0){

    // blue

var Classi = document.getElementById("Class");
    Classi.click();
    
}

// blue

for (let i = 0; i < bar.length; i++) {
   
  if(str.includes(bar[i])){
      count2++
  }
}

if(count2 > 0){
var studentsi = document.getElementById("student");
   studentsi.click();
}
console.log(count1)
console.log(count2)

}, 700)



console.log(el1);
</script>

<script>
// Page Dark/Light Mode Toggle
(function(){
var buttonText, theme;
const isDarkMode = window.matchMedia("(prefers-color-scheme: dark)"),
modeButton = document.querySelector(".light-switch"),
currentTheme = localStorage.getItem("theme");	

// Get locally saved moode
if (currentTheme == 'dark') {
document.body.classList.toggle("dark-mode");
} else if (currentTheme == 'light') {
document.body.classList.toggle("light-mode");
}

// Set initial button title
buttonText = (document.body.classList.contains('dark-mode')) ? 'light':'dark';
modeButton.setAttribute("title", `Switch to ${buttonText} mode`);

// Generate button switch logic
modeButton.onclick = () => {
if (isDarkMode.matches) {
document.body.classList.toggle("light-mode");
document.body.classList.toggle("dark-mode");
theme = document.body.classList.contains("light-mode") ? "light":"dark";
} else {

document.body.classList.toggle("dark-mode");
theme = document.body.classList.contains("dark-mode") ? "dark":"light";
}

// Set button title
buttonText = (document.body.classList.contains('dark-mode')) ? 'light':'dark';
modeButton.setAttribute("title", `Switch to ${buttonText} mode`);

// Store last used state
localStorage.setItem("theme", theme);
}	
}());
</script>
