
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

include("../Connect/cnx.php");



include("../include/nav.php");

$alert = "";
if (isset($_POST["submit"]) && isset($_POST["Nom"]) && isset($_POST["Prenom"]) && isset($_POST["email"]) && isset($_POST["tel"]) && isset($_POST["pass"]) && isset($_POST["re_pass"]) && isset($_POST["role"]) && $_POST["pass"] == $_POST["re_pass"]) {

  $Nom = $_POST["Nom"];
  $Prenom = $_POST["Prenom"];
  $email = $_POST["email"];
  $tel = $_POST["tel"];
  $pass = $_POST["pass"];
  $re_pass = md5($_POST["re_pass"]);
  $role = $_POST["role"];

  
//prepare the statement
$stmt = "SELECT `Email` FROM `users` WHERE `Email`= '$email'";
//execute the statement
$results = mysqli_query($conn, $stmt);
//fetch result
$row = mysqli_num_rows($results);
if ($row) {
    $alert = '<br><br><div class="alert alert-danger" role="alert">
    Email existe Déjà!
  </div>';
} else {
    $qry= "INSERT INTO `users`(  `Mot_de_passe`, `Nom`, `Prenom`, `Email`, `Tel`, `Role`) VALUES ('$re_pass','$Nom','$Prenom','$email','$tel','$role')";
    mysqli_query($conn, $qry);

    $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Insérer','$dateTIMEnow','$Nom $Prenom','Un nouvel utilisateur a été ajouté avec le rôle ($role) ','$ip_address')";
    mysqli_query($conn,$Query_spy);

    $alert = '<br><br><div class="alert alert-success" role="alert">
    Utilisateur ajouté avec Succès!
  </div>';
} 
}
  

if (isset($_POST["update"])) {
  

  $btnIDuser = $_POST["update"];
  $Nom = $_POST["Nomup"];
  $Prenom = $_POST["Prenomup"];
  $email = $_POST["emailup"];
  $tel = $_POST["telup"];

$Query_usersSPY = "SELECT * FROM users WHERE ID=$btnIDuser;";

$result_usersSPY = mysqli_query($conn,$Query_usersSPY);

$row_usersSPY = mysqli_fetch_assoc($result_usersSPY);

  $Nom_spy = $row_usersSPY["Nom"];
  $Prenom_spy = $row_usersSPY["Prenom"];
  $email_spy = $row_usersSPY["Email"];
  $tel_spy = $row_usersSPY["Tel"];

  $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Mettre à jour','$dateTIMEnow','$Nom_spy $Prenom_spy','Les informations utilisateur ont été modifiées de ($Nom_spy,$Prenom_spy,$email_spy,$tel_spy) à ($Nom,$Prenom,$email,$tel)','$ip_address')";
  mysqli_query($conn,$Query_spy);

  $qryupdate = "UPDATE `users` SET `Nom`='$Nom',`Prenom`='$Prenom',`Email`='$email',`Tel`='$tel' WHERE `ID` = $btnIDuser";
  mysqli_query($conn,$qryupdate);



}
 
if (isset($_POST["delet"])) {
  

    $btnIDuserdelet = $_POST["delet"];

    $Query_usersSPY = "SELECT * FROM users WHERE ID=$btnIDuserdelet;";

$result_usersSPY = mysqli_query($conn,$Query_usersSPY);

$row_usersSPY = mysqli_fetch_assoc($result_usersSPY);

  $Nom_spy = $row_usersSPY["Nom"];
  $Prenom_spy = $row_usersSPY["Prenom"];
  $email_spy = $row_usersSPY["Email"];
  $tel_spy = $row_usersSPY["Tel"];

    $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Supprimé','$dateTIMEnow','$Nom_spy $Prenom_spy','utilisateur $Nom_spy $Prenom_spy a été supprimé','$ip_address')";
    mysqli_query($conn,$Query_spy);

    $qryupdate = "DELETE FROM `users` WHERE `ID` = $btnIDuserdelet";
    mysqli_query($conn,$qryupdate);
  
  }
 
  


$Query = "SELECT `ID`, `Nom`,`Prenom`,`Email`,`Tel`, `Role` FROM `users`";
$result = mysqli_query($conn,$Query);

?>

    <!-- MAIN -->
    <div class="col">
        
        <!-- <h1>
        TABLEAU 
            <small class="text-muted">DE BORD</small>
        </h1> -->
        
        
<!-- For demo purpose -->




<style>
.rounded-pill {
    border-radius: 50rem!important;
}

.count {
    border-radius: 18px;
    margin-right: 30px;
    margin-bottom: 20px !important;

}

body {
  background: #f2f2f2;
}

.row-container {
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
  transition: all 0.3s cubic-bezier(.25,.8,.25,1);
  cursor: pointer;
}

.row-container:hover {
  box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
}
button {
  position: relative;
  bottom: 1px;
}
@media screen and (max-width: 992px) {
  .le-hide {
    visibility: hidden;
  }
}

input {
  border: hidden;
  background-color: #f2f2f2;

}

.no-outline:focus {
  outline: none;
  background-color: #f2f2f2;
}
</style>
 

<title><?php echo "$Titre"; ?> Admin - Utilisateurs</title>



<div class="container">
    
    <?php echo $alert; ?>
<br><br>

<div class="card bg-light ">
<article class="card-body mx-auto" style="max-width: 400px;">
	<h4 class="card-title mt-3 text-center">Créer un compte</h4>
	
    <form  method="POST" enctype="multipart/form-data" onsubmit="return confirm ('Êtes-vous sûr?')">
        
    <div class="form-group input-group">
    	<div class="input-group-prepend">
        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
		</div>
		<input class="form-control" name="Nom" placeholder="Nom" type="text"> 
    	<input  class="form-control ml-2" name="Prenom" placeholder="Prenom" type="text">
    </div> <!-- form-group// -->
    <div class="form-group input-group">
    	<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
		 </div>
        <input name="email" class="form-control" placeholder="Adresse E-mail" type="email">
    </div> <!-- form-group// -->
    <div class="form-group input-group">
    	<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
		</div>
    	<input name="tel" class="form-control" placeholder="Numéro de Téléphone" type="text">
    </div> <!-- form-group// -->
   
    <div class="form-group input-group">
    	<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
		</div>
        <input class="form-control" placeholder="Créer un mot de passe" name="pass" type="password">
    </div> <!-- form-group// -->
    <div class="form-group input-group">
    	<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
		</div>
        <input class="form-control" placeholder="Répéter le mot de passe" name="re_pass" type="password">
    </div> <!-- form-group// -->     
    <div class="form-group input-group">
    	<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-building"></i> </span>
		</div>
		<select class="form-control" name="role">
			<option selected="" disabled> Sélectionnez le Role</option>
            <option>Director</option>
			<option>Admin</option>

		</select>
	</div> <!-- form-group end.// -->                                 
    <div class="form-group">
        <button type="submit" name="submit" class="btn btn-primary btn-block"> Créer un compte  </button>
    </div> <!-- form-group// -->      
                                                    
</form>
</article>
</div> <!-- card.// -->
<br><br>

<div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Prenom</th>
                                        <th scope="col">Nom</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Tel</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <form method="POST"  onsubmit="return confirm ('Êtes-vous sûr?')">
                                <?php  while ($row = mysqli_fetch_assoc($result)) {?>


                                
                                    <tr>    
                                        <th scope="row"><?php echo $row["ID"]; ?></th>
                                        <td> <input type="text" class="no-outline Prenom input" placeholder="Prenom" value="<?php echo $row["Prenom"]; ?>" disabled style="width: 100%;"></td>
                                        <td> <input type="text" class="no-outline Nom input" placeholder="Nom" value="<?php echo $row["Nom"]; ?>" disabled style="width: 100%;"> </td>
                                        <td> <input type="text" class="no-outline Email input" placeholder="Email" value="<?php echo $row["Email"];?>" disabled style="width: 100%;">  </td>
                                        <td> <input type="text" class="no-outline Tel input" placeholder="Tel" value="<?php echo $row["Tel"];?>" disabled style="width: 100%;">  </td>
                                        <td> <?php echo $row["Role"];?> </td>
                                        <td>
                                            <!-- Call to action buttons -->
                                            <ul class="list-inline m-0">
                                                
                                                <li class="list-inline-item mb-1">
                                                    <button class="btn btn-success btn-sm rounded-0 edit" type="button" data-toggle="tooltip" data-placement="top" value="<?php echo $row["ID"]; ?>"  title="Edit"><i class="fa fa-edit"></i></button>
                                                </li>
                                                <li class="list-inline-item mb-1">
                                                    <button class="btn btn-danger btn-sm rounded-0" type="submit" name="delet" data-toggle="tooltip" data-placement="top" value="<?php echo $row["ID"]; ?>" title="Delete"><i class="fa fa-trash"></i></button>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    
<?php }?>
</form>      
                                </tbody>
                            </table>
                            <br><br>

    </div>
    

   
              
              

       




  
    </div><!-- contener.// -->


<?php  include("../include/footer.php"); }else  if ( !isset($_SESSION['login'])) {
            header('Location: login.php');
        }?>
   



   <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            
            $( ".edit" ).click(function() {
                var name = $(this).attr("name");

if (name =="update") {

 
}else{

                $(".input").removeAttr('name');
                $(".input").prop("disabled", true).css("background-color", "#f2f2f2");
                $(".edit").removeAttr('name');
                $(".edit").attr("type", "button");
                $(".edit").find(">:first-child").attr('class', 'fa fa-edit');

}
                
               
                $tag = $(this).parent().parent().parent().parent() ;
                $tag.find(":input[type=text]").prop("disabled", false).css("background-color", "white");
                    $(this).find(">:first-child").attr('class', 'fa fa-check');
                    $tag.find(".Prenom").attr('name', 'Prenomup');
                    $tag.find(".Nom").attr('name', 'Nomup');
                    $tag.find(".Email").attr('name', 'emailup');
                    $tag.find(".Tel").attr('name', 'telup');
                    $targertyhis = $(this)
                    setTimeout(
  function() 
  {
    $targertyhis.attr('name', 'update');
    $targertyhis.attr("type", "submit");
                  }, 500);
                    
                    
});

           
    $('#example').DataTable();
});
    </script>



   