
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


if (isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {

  $dateTIMEnow = date("Y-m-d H:i:s");
  $nameUSER = $_SESSION['login'][2];
  $prenomeUSER =  $_SESSION['login'][3];

  $thisMOTH = date("m");
  $thisYEAR = date("Y");
  $thisDAY = date("d");
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

  }else{

    $query_insert_colors = "INSERT INTO setting (Namess, Valuess)
    VALUES ('white', '#ffffff'), ('black', '#000'), ('grey1', '#333333'), ('grey2', '#343a40'), ('blue', '#007bff'), ('green', '#28a745'), ('red', '#dc3545'), ('logo', 'logo.png'), ('Titre', 'Nachwa')";
    mysqli_query($conn,$query_insert_colors);

    header("Refresh:0");

    ob_end_flush();   

  }
?>
<link rel="stylesheet" href="../css\students.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>
<script src="../js/FileSaver.min.js"></script>

<!-- and dont forget to include excel-gen,js file -->
<script src="../js/excel-gen.js"></script>
<title><?php echo "$Titre"; ?> Admin - Setting</title>

<div class="col">
<br><br>
<h4 class="text-right" style="text-align: center !important;">Paramètres du site</h4> <br>

<form method="POST" class="ml-5" onsubmit="return confirm ('Êtes-vous sûr?')">
<div class="form-row mt-5">
<div class="form-group col-md-2">
<label  class="col-form-label">Année scolaire :</label>
    </div>

    <div class="form-group col-md-1">
      <input type="number" name="begin" value="<?php echo $value_annee_setting_Begin; ?>" class="form-control" id="inputCity" min="2022" disabled>
    </div>
    <div class="form-group col-md-0">
<label  class="col-form-label">-</label>
    </div>
    <div class="form-group col-md-1">
    <input type="number" name="end" value="<?php echo $value_annee_setting_End; ?>" class="form-control" id="inputCity" min="2023" disabled>
    </div>
    <div class="form-group col-md-1">
    <button class="btn btn-success mt-1 edit" name="updateANEE" type="button" title="Edit"><i class="fa fa-edit" ></i></button>
    </div>
    </div>
    <br>
    <hr>
    <br>
    <br>
    <h5 class="text-right" style="text-align: center !important;">Modifier les couleurs</h5> <br>
    <div>
    <label for="head" class="mt-5 mr-5">couleur principale #1 :</label>
    <input type="color" id="head" name="blue" value="<?php echo"$color_blue"; ?>">

</div>

<div>
    <label for="head" class="mt-5 mr-5">couleur principale #2 :</label>
    <input type="color" id="head" name="grey2" value="<?php echo"$color_grey2"; ?>">

</div>

<div>
    <label for="head" class="mt-5 mr-5">couleur principale #3 :</label>
    <input type="color" id="head" name="white" value="<?php echo"$color_white"; ?>">

</div>

<div>
    <label for="head" class="mt-5 mr-5">couleur secondaire #1 :</label>
    <input type="color" id="head" name="grey1" value="<?php echo"$color_grey1"; ?>">

</div>

<div>
    <label for="head" class="mt-5 mr-5">couleur secondaire #2 :</label>
    <input type="color" id="head" name="black" value="<?php echo"$color_black"; ?>">

</div>

<div>
    <label for="head" class="mt-5 mr-5">couleur secondaire #3 :</label>
    <input type="color" id="head" name="green" value="<?php echo"$color_green"; ?>">

</div>


<div>
    <label for="head" class="mt-5 mr-5">couleur secondaire #4 :</label>
    <input type="color" id="head" name="red" value="<?php echo"$color_red"; ?>">

</div>

<div style="text-align: center;">
<button type="submit" class="btn btn-primary m-5" name="Enregistrer_color"><i class="fa fa-floppy-o mr-2" aria-hidden="true"></i>Enregistrer les couleurs</button>
<button type="submit" name="Retour_original" class="btn btn-secondary m-5"><i class="fa fa-refresh mr-2" aria-hidden="true"></i>Retour à l'origine</button>
</div>

</form>


    <hr>
<br>
<br>
    <h5 class="text-right" style="text-align: center !important;">Modifier Logo&Titre</h5> <br>
    <br>
<br>
    <form method="POST"  onsubmit="return confirm ('Êtes-vous sûr?')" class="ml-5" enctype="multipart/form-data">

<div style="text-align: center;">
<img id="imagelogo" for="formFileLg" id="logo" class="form-label" src="..\img\<?php echo $logo; ?>" alt=""><br>

</div>

<label for="imgInp" class="form-label">changer de logo :</label>
  <input class="form-control form-control-lg" name="fileToUpload" id="imgInp" type="file">

  <div class="mb-3 mt-4">
  <label for="exampleFormControlInput1" class="form-label">Titre du site :</label>
  <input type="text" class="form-control" name="Titre" id="exampleFormControlInput1" value="<?php echo "$Titre"; ?>" placeholder="Titre..." style="width: 21%;">
</div>

<div style="text-align: center;">
<button type="submit" class="btn btn-primary m-5" name="Logo_Titre"><i class="fa fa-floppy-o mr-2" aria-hidden="true"></i>Enregistrer</button>
</div>

<hr>
    </form>
<?php

// uplod logo 
if (isset($_POST["Logo_Titre"])) {



if (isset($_FILES['fileToUpload']['name'])) {
 

$target_dir = "../img/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

$name_logo =  htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
// Check if image file is a actual image or fake image
if(isset($_POST["Logo_Titre"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "Le fichier est une image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "Le fichier n'est pas une image.";
    $uploadOk = 0;
  }
}


// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "Désolé, votre fichier est trop volumineux.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Désolé, votre fichier n'a pas été téléchargé.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "Le fichier ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " a été téléchargé.";
    $query = "UPDATE `setting` SET `Valuess` = '$name_logo' WHERE `Namess` = 'logo';";
  mysqli_query($conn,$query);
  } else {
    echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
  }
}

  

}

if (isset($_POST["Titre"])) {
  $Titre_value = $_POST["Titre"];
  $query = "UPDATE `setting` SET `Valuess` = '$Titre_value' WHERE `Namess` = 'Titre';";
mysqli_query($conn,$query);

header("Refresh:0");

ob_end_flush(); 
}

}
// uplode logo final 
if (isset($_POST["Retour_original"])) {

  
  
  $query = "UPDATE `setting` SET `Valuess` = '#ffffff' WHERE `Namess` = 'white';";
  mysqli_query($conn,$query);
  $query = "UPDATE `setting` SET `Valuess` = '#000' WHERE `Namess` = 'black';";
  mysqli_query($conn,$query);
  $query = "UPDATE `setting` SET `Valuess` = '#333333' WHERE `Namess` = 'grey1';";
  mysqli_query($conn,$query);
  $query = "UPDATE `setting` SET `Valuess` = '#343a40' WHERE `Namess` = 'grey2';";
  mysqli_query($conn,$query);
  $query = "UPDATE `setting` SET `Valuess` = '#007bff' WHERE `Namess` = 'blue';";
  mysqli_query($conn,$query);
  $query = "UPDATE `setting` SET `Valuess` = '#28a745' WHERE `Namess` = 'green';";
  mysqli_query($conn,$query);
  $query = "UPDATE `setting` SET `Valuess` = '#dc3545' WHERE `Namess` = 'red';";
  mysqli_query($conn,$query);
  
  header("Refresh:0");

  ob_end_flush();   

  }

if (isset($_POST["Enregistrer_color"])) {

$blue =  $_POST["blue"];
$grey2 =  $_POST["grey2"];
$white =  $_POST["white"];
$grey1 =  $_POST["grey1"];
$black =  $_POST["black"];
$green =  $_POST["green"];
$red =  $_POST["red"];

$query = "UPDATE `setting` SET `Valuess` = '$white' WHERE `Namess` = 'white';";
mysqli_query($conn,$query);
$query = "UPDATE `setting` SET `Valuess` = '$black' WHERE `Namess` = 'black';";
mysqli_query($conn,$query);
$query = "UPDATE `setting` SET `Valuess` = '$grey1' WHERE `Namess` = 'grey1';";
mysqli_query($conn,$query);
$query = "UPDATE `setting` SET `Valuess` = '$grey2' WHERE `Namess` = 'grey2';";
mysqli_query($conn,$query);
$query = "UPDATE `setting` SET `Valuess` = '$blue' WHERE `Namess` = 'blue';";
mysqli_query($conn,$query);
$query = "UPDATE `setting` SET `Valuess` = '$green' WHERE `Namess` = 'green';";
mysqli_query($conn,$query);
$query = "UPDATE `setting` SET `Valuess` = '$red' WHERE `Namess` = 'red';";
mysqli_query($conn,$query);

header("Refresh:0");

ob_end_flush();   
}
if (isset($_POST["updateANEE"])) {

    $begin = $_POST["begin"];
    $end = $_POST["end"];

    $query = "SELECT * FROM setting WHERE Namess = 'Anne_Begin' OR Namess = 'Anne_End'";
    $result = mysqli_query($conn,$query);

    $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Insérer','$dateTIMEnow','Année scolaire','ََAnnée scolaire a changé de ($value_annee_setting_Begin-$value_annee_setting_End) à ($begin-$end)','$ip_address')";
    mysqli_query($conn,$Query_spy);

if ($result) {
  if (mysqli_num_rows($result) === 2) {

    $query = "UPDATE `setting` SET `Valuess`='$begin' WHERE `Namess`='Anne_Begin'";
    $result = mysqli_query($conn,$query);

    $query = "UPDATE `setting` SET `Valuess`='$end' WHERE `Namess`='Anne_End'";
    $result = mysqli_query($conn,$query);

 
  } else {
    $query = "INSERT INTO `setting`( `Namess`, `Valuess`) VALUES ('Anne_Begin','$begin')";
    $result = mysqli_query($conn,$query);
    $query = "INSERT INTO `setting`( `Namess`, `Valuess`) VALUES ('Anne_End','$end')";
    $result = mysqli_query($conn,$query);

  }
}

header('Location: setting.php');

ob_end_flush();

}

 include("../include/footer.php"); }else  if ( !isset($_SESSION['login'])) {
            header('Location: login.php');
        }?>
       <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>


    <script>

$(document).ready(function () {
            
            $( ".edit" ).click(function() {

           
            $(this).find(">:first-child").attr('class', 'fa fa-floppy-o');
            $tag = $(this).parent().parent() ;
            $tag.find(":input[type=number]").prop("disabled", false).css("background-color", "white");
            // $(this).attr("type", "submit"); 
            setTimeout( function() {
                $( ".edit" ).attr("type", "submit"); 
                     
                    }, 500);

                    
});


 
});


imgInp.onchange = evt => {
  const [file] = imgInp.files
  if (file) {
    logo.src = URL.createObjectURL(file)
  }
}


$( "#imagelogo" ).click(function() {

  $( "#imgInp" ).click();
});

</script>
