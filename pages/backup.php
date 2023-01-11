
<?php 


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


if ( !isset($_SESSION['login'])) {
  header('Location: login.php');
}

if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {

  $dateTIMEnow = date("Y-m-d H:i:s");
  $nameUSER = $_SESSION['login'][2]; 
  $prenomeUSER =  $_SESSION['login'][3];
  
include("../Connect/cnx.php");

ob_start();

  if (isset($_POST["submit"]) && isset($_POST["password"])) {

if ( $_POST["password"] =="t@@985*/sp^èçà''@fghghjrtySDJSHsdh()d_ç(à'-è_çàezoe@@§§!;cs") {
    # code...



    $tables = array();
    $sql = "SHOW TABLES";
    $result = mysqli_query($conn, $sql);
    
    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }
    $sqlScript = "";
    foreach ($tables as $table) {
        
        // Prepare SQLscript for creating table structure
        $query = "SHOW CREATE TABLE $table";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_row($result);
        
        $sqlScript .= "\n\n" . $row[1] . ";\n\n";
        
        
        $query = "SELECT * FROM $table";
        $result = mysqli_query($conn, $query);
        
        $columnCount = mysqli_num_fields($result);
        
        // Prepare SQLscript for dumping data for each table
        for ($i = 0; $i < $columnCount; $i ++) {
            while ($row = mysqli_fetch_row($result)) {
                $sqlScript .= "INSERT INTO $table VALUES(";
                for ($j = 0; $j < $columnCount; $j ++) {
                    $row[$j] = $row[$j];
                    
                    if (isset($row[$j])) {
                        $sqlScript .= '"' . $row[$j] . '"';
                    } else {
                        $sqlScript .= '""';
                    }
                    if ($j < ($columnCount - 1)) {
                        $sqlScript .= ',';
                    }
                }
                $sqlScript .= ");\n";
            }
        }
        
        $sqlScript .= "\n"; 
    }
    if(!empty($sqlScript))
    {
        // Save the SQL script to a backup file
        $backup_file_name = "../bkpfolderthert785fkfkfkfk/".$database_name . '_backup_' . date('Y-m-d') . '.sql';
        $fileHandler = fopen($backup_file_name, 'w+');
        $number_of_lines = fwrite($fileHandler, $sqlScript);
        fclose( $fileHandler); 
    
        // Download the SQL backup file to the browser
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($backup_file_name));
        ob_clean();
        flush();
        readfile($backup_file_name);
        exec('rm ' . $backup_file_name); 
        $files = glob('../bkpfolderthert785fkfkfkfk/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file)) {
    unlink($file); // delete file
  }
}
    }

    $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Backup','$dateTIMEnow','Data','Toutes les données ont été téléchargées avec succès','$ip_address')";
    mysqli_query($conn,$Query_spy);

}else{
  $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Backup','$dateTIMEnow','Data','Il y a eu une tentative infructueuse de téléchargement des données','$ip_address')";
  mysqli_query($conn,$Query_spy);
  $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Déconnecter','$dateTIMEnow','$nameUSER $prenomeUSER','La déconnexion est due à une tentative infructueuse de téléchargement de données','$ip_address')";
  mysqli_query($conn,$Query_spy);
  mysqli_close($conn);
    session_destroy();
header('Location: login.php');
}
  }





include("../include/nav.php");
?>

<link rel="stylesheet" href="../css\student.css">

    <!-- MAIN -->
    <div class="col">

        <!-- <h1>
            
            <small class="text-muted"></small>
        </h1> -->
        
        
<!-- For demo purpose -->
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">

    <title><?php echo "$Titre"; ?> Admin - Backup</title>

<form action="" method="POST">
<h4 class="mb-4 mt-5" style="text-align: center;">Backup</h4>  

<div class="input-group mb-3">
  <input  type="password" class="form-control" placeholder="Entrer le mot de passe..."  name="password" aria-label="Entrer le mot de passe..." aria-describedby="basic-addon2">
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" name="submit" type="submit">Télécharger</button>
  </div>
</div>

</form>

              </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>


</form>


<?php  include("../include/footer.php");
 }else  if ( !isset($_SESSION['login'])) {
  header('Location: login.php');
}
?>
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="..\js\printThis.js"></script>

   <!-- <script>

$(document).ready(function(){
  $('#adiltest').printThis();
 
});



</script> -->