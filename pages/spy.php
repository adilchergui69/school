
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

  $thisMOTH = date("m");
  $thisYEAR = date("Y");
  $thisDAY = date("d");
include("../Connect/cnx.php");

ob_start();

include("../include/nav.php");

?>
<link rel="stylesheet" href="../css\students.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>
<script src="../js/FileSaver.min.js"></script>

<!-- and dont forget to include excel-gen,js file -->
<script src="../js/excel-gen.js"></script>
<title><?php echo "$Titre"; ?> Admin - Spy</title>

<div class="col">
<br><br>
<h4 class="text-right" style="text-align: center !important;">Espionner toutes les activités</h4> <br>

<div class="table-responsive mt-5">
        
    <table id="<?php echo"Paiement".$thisDAY."_".$thisMOTH."_".$thisYEAR;?>" class="table table-striped table-bordered" style="width:100%" data-sorter="datesSorter">
        <thead>
            <tr>       
                <th>ID</th>
                <th>Acteur</th>
                <th>Opération</th>
                <th>Date</th>
                <th>Victime</th>
                <th>Informations</th>
                <th>Ip Address</th>
                
                

            </tr>
        </thead>
        <tbody>

          <?php 

          $Query_spy = "SELECT `ID`, `Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address` FROM `spy`";
          $result_spy = mysqli_query($conn,$Query_spy);

          while ($row_spy = mysqli_fetch_assoc($result_spy)) {
          
          ?>
            <tr>
                <td><?php echo $row_spy["ID"]; ?></td>
                <td><?php echo $row_spy["Actor"]; ?></td>
                <td><?php echo $row_spy["Operation"]; ?></td>
                <td><?php echo $row_spy["Date"];?></td>
                <td><?php echo $row_spy["Victim"];?></td>
                <td><?php echo $row_spy["Information"]; ?></td>
                <td><?php echo $row_spy["ip_address"]; ?></td>

            </tr>
            <?php }?>



        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Acteur</th>
                <th>Opération</th>
                <th>Date</th>
                <th>Victime</th>
                <th>Informations</th>
                <th>Ip Address</th>

          </tr>

        </tfoot>
    </table>
    <br><br>
    <button class="btn btn-success" id="generate-excel"> Télécharger Rapport </button>
    <br><br><br><br>
  
    </div >
    
    </div>

<?php

 include("../include/footer.php"); }else  if ( !isset($_SESSION['login'])) {
            header('Location: login.php');
        }?>
       <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {

excel = new ExcelGen({

"src_id" : "<?php echo"Paiement".$thisDAY."_".$thisMOTH."_".$thisYEAR;?>",
"show_header" : "true"

});

$("#generate-excel").click(function () {
excel.generate();
});


$('#<?php echo"Paiement".$thisDAY."_".$thisMOTH."_".$thisYEAR;?>').DataTable(
{

searching: true,

//Datatables will show information about the table including information about filtered data if that action is being performed 
//https://datatables.net/reference/option/info
info: false,
order: [[ 0, "desc" ]],


}
);



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