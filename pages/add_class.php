
<?php 
session_start();
// if ( !isset($_SESSION['login'])) {
//   header('Location: login.php');
// }
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

if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Director" || isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {
    

include("../Connect/cnx.php");

ob_start();

include("../include/nav.php");

$Query = "SELECT `ID`, `Annee` FROM `niveau`";
$result = mysqli_query($conn,$Query);

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

?>
<title><?php echo "$Titre"; ?> Admin - Gestion Département</title>
<style>

.btn-block{

  width: 90px !important;
  height: 22px !important;
  padding-bottom: 22px;
  
}

</style>


<div class="col m-2">
<br><br>
<h4 class="text-right" style="text-align: center !important;">Gestion Département</h4> <br>



<form action="" method="POST" onsubmit="return confirm ('Êtes-vous sûr?')">
<div class="table-responsive mt-5">
        
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>       
          <th><input type="checkbox" class="checkall"></th>

            <th>ID</th>
                <th>Nom</th>
                <th>prenom</th>
                <th>Date de naissance</th>
       
                
                

            </tr>
        </thead>
        <tbody>

          <?php 

          $Query_table_paiement_activite = "SELECT * FROM `etudiant` WHERE `statut` IS NULL or `statut` = 0";
          $result_table_paiement_activite = mysqli_query($conn,$Query_table_paiement_activite);

          while ($row_table_paiement_activite = mysqli_fetch_assoc($result_table_paiement_activite)) {
          
          ?>
            <tr>
            <td><input type="checkbox" name="check_list[]" value="<?php echo $row_table_paiement_activite["ID"]; ?>"></td>
            <td> <a href="./student.php?id=<?php echo $row_table_paiement_activite["ID"]; ?>" target="_blank"><?php echo $row_table_paiement_activite["ID"]; ?></a> </td>
                <td><?php echo $row_table_paiement_activite["Nom"]; ?></td>
                <td><?php echo $row_table_paiement_activite["Prenom"];?></td>
                <td><?php echo $row_table_paiement_activite["Date_de_naissance"];?></td>

            </tr>
            <?php }?>



        </tbody>
        <tfoot>
            <tr>
            <th><input type="checkbox" class="checkall"></th>
            <th>ID</th>
                <th>Nom</th>
                <th>prenom</th>
                <th>Groupe</th>
          

          </tr>

        </tfoot>
    </table>

    </div >
    
    <div class=" m-2 mt-1">
   

    <div class="form-row">
    <div class="col">
    <label for="inputEmail4">Class</label>
    <select required class="custom-select" name="select_Annee"  id="nivo">
    <option selected disabled class="bg-secondary text-white">Choisir Class...</option>


<?php 

  



$Query = "SELECT `ID`, `Annee` FROM `niveau`";
$result = mysqli_query($conn,$Query);

while ($row = mysqli_fetch_assoc($result)) {


$ID_class =  $row["ID"];
$name_class = $row["Annee"];
echo "<option value='$ID_class'>$name_class</option>";

}


?>   
</select>
</div>
    <div class="col">
    <label for="inputEmail4">Groupe</label>
    <select class="custom-select" name="select_GROUP" id="results">

 
   </select>
    </div>
    <div class="col">
    <label for="inputEmail4">Année scolaire</label>
    <select required class="custom-select mb-5" name="select_Annee_scolaire" id="inputGroupSelect04">

  <option value="<?php echo $Annee_scolaireSQL; ?>"><?php echo $Annee_scolaireSQL; ?></option>

</select>

 
   </select>
    </div>
  </div>
    </div>
    <button type="submit" class="btn btn-primary m-5 pl-5 pr-5 pt-2 pb-2" name="Enregistrer" style="margin-left: 45% !important;">Enregistrer</button>

    </form> 

</div><!-- /.row -->


</div>

<script>

   
   
if("undefined"==typeof jQuery)throw new Error("multiselect requires jQuery");!function(t){"use strict";var e=t.fn.jquery.split(" ")[0].split(".");if(e[0]<2&&e[1]<7)throw new Error("multiselect requires jQuery version 1.7 or higher")}(jQuery),function(t){"function"==typeof define&&define.amd?define(["jquery"],t):t(jQuery)}(function(t){"use strict";var e=function(t){function e(e,o){var i=e.prop("id");this.$left=e,this.$right=t(t(o.right).length?o.right:"#"+i+"_to"),this.actions={$leftAll:t(t(o.leftAll).length?o.leftAll:"#"+i+"_leftAll"),$rightAll:t(t(o.rightAll).length?o.rightAll:"#"+i+"_rightAll"),$leftSelected:t(t(o.leftSelected).length?o.leftSelected:"#"+i+"_leftSelected"),$rightSelected:t(t(o.rightSelected).length?o.rightSelected:"#"+i+"_rightSelected"),$undo:t(t(o.undo).length?o.undo:"#"+i+"_undo"),$redo:t(t(o.redo).length?o.redo:"#"+i+"_redo")},delete o.leftAll,delete o.leftSelected,delete o.right,delete o.rightAll,delete o.rightSelected,this.options={keepRenderingSort:o.keepRenderingSort,submitAllLeft:void 0!==o.submitAllLeft?o.submitAllLeft:!0,submitAllRight:void 0!==o.submitAllRight?o.submitAllRight:!0,search:o.search,ignoreDisabled:void 0!==o.ignoreDisabled?o.ignoreDisabled:!1},delete o.keepRenderingSort,o.submitAllLeft,o.submitAllRight,o.search,o.ignoreDisabled,this.callbacks=o,this.init()}return e.prototype={init:function(){var e=this;e.undoStack=[],e.redoStack=[],(e.$left.find("optgroup").length||e.$right.find("optgroup").length)&&(e.callbacks.sort=!1,e.options.search=!1),e.options.keepRenderingSort&&(e.skipInitSort=!0,e.callbacks.sort!==!1&&(e.callbacks.sort=function(e,o){return t(e).data("position")>t(o).data("position")?1:-1}),e.$left.find("option").each(function(e,o){t(o).data("position",e)}),e.$right.find("option").each(function(e,o){t(o).data("position",e)})),"function"==typeof e.callbacks.startUp&&e.callbacks.startUp(e.$left,e.$right),e.skipInitSort||"function"!=typeof e.callbacks.sort||(e.$left.mSort(e.callbacks.sort),e.$right.each(function(o,i){t(i).mSort(e.callbacks.sort)})),e.options.search&&e.options.search.left&&(e.options.search.$left=t(e.options.search.left),e.$left.before(e.options.search.$left)),e.options.search&&e.options.search.right&&(e.options.search.$right=t(e.options.search.right),e.$right.before(t(e.options.search.$right))),e.events()},events:function(){var e=this;e.options.search&&e.options.search.$left&&e.options.search.$left.on("keyup",function(t){if(this.value){e.$left.find('option:search("'+this.value+'")').mShow(),e.$left.find('option:not(:search("'+this.value+'"))').mHide()}else e.$left.find("option").mShow()}),e.options.search&&e.options.search.$right&&e.options.search.$right.on("keyup",function(t){if(this.value){e.$right.find('option:search("'+this.value+'")').mShow(),e.$right.find('option:not(:search("'+this.value+'"))').mHide()}else e.$right.find("option").mShow()}),e.$right.closest("form").on("submit",function(t){e.$left.find("option").prop("selected",e.options.submitAllLeft),e.$right.find("option").prop("selected",e.options.submitAllRight)}),e.$left.on("dblclick","option",function(t){t.preventDefault();var o=e.$left.find("option:selected");o.length&&e.moveToRight(o,t)}),e.$left.on("keypress",function(t){if(13===t.keyCode){t.preventDefault();var o=e.$left.find("option:selected");o.length&&e.moveToRight(o,t)}}),e.$right.on("dblclick","option",function(t){t.preventDefault();var o=e.$right.find("option:selected");o.length&&e.moveToLeft(o,t)}),e.$right.on("keydown",function(t){if(8===t.keyCode||46===t.keyCode){t.preventDefault();var o=e.$right.find("option:selected");o.length&&e.moveToLeft(o,t)}}),(navigator.userAgent.match(/MSIE/i)||navigator.userAgent.indexOf("Trident/")>0||navigator.userAgent.indexOf("Edge/")>0)&&(e.$left.dblclick(function(t){e.actions.$rightSelected.trigger("click")}),e.$right.dblclick(function(t){e.actions.$leftSelected.trigger("click")})),e.actions.$rightSelected.on("click",function(o){o.preventDefault();var i=e.$left.find("option:selected");i.length&&e.moveToRight(i,o),t(this).blur()}),e.actions.$leftSelected.on("click",function(o){o.preventDefault();var i=e.$right.find("option:selected");i.length&&e.moveToLeft(i,o),t(this).blur()}),e.actions.$rightAll.on("click",function(o){o.preventDefault();var i=e.$left.children(":not(span):not(.hidden)");i.length&&e.moveToRight(i,o),t(this).blur()}),e.actions.$leftAll.on("click",function(o){o.preventDefault();var i=e.$right.children(":not(span):not(.hidden)");i.length&&e.moveToLeft(i,o),t(this).blur()}),e.actions.$undo.on("click",function(t){t.preventDefault(),e.undo(t)}),e.actions.$redo.on("click",function(t){t.preventDefault(),e.redo(t)})},moveToRight:function(e,o,i,n){var r=this;return"function"==typeof r.callbacks.moveToRight?r.callbacks.moveToRight(r,e,o,i,n):"function"!=typeof r.callbacks.beforeMoveToRight||i||r.callbacks.beforeMoveToRight(r.$left,r.$right,e)?(e.each(function(e,o){var i=t(o);if(r.options.ignoreDisabled&&i.is(":disabled"))return!0;if(i.parent().is("optgroup")){var n=i.parent(),l=r.$right.find('optgroup[label="'+n.prop("label")+'"]');l.length||(l=n.clone(),l.children().remove()),i=l.append(i),n.removeIfEmpty()}r.$right.move(i)}),n||(r.undoStack.push(["right",e]),r.redoStack=[]),"function"!=typeof r.callbacks.sort||i||r.$right.mSort(r.callbacks.sort),"function"!=typeof r.callbacks.afterMoveToRight||i||r.callbacks.afterMoveToRight(r.$left,r.$right,e),r):!1},moveToLeft:function(e,o,i,n){var r=this;return"function"==typeof r.callbacks.moveToLeft?r.callbacks.moveToLeft(r,e,o,i,n):"function"!=typeof r.callbacks.beforeMoveToLeft||i||r.callbacks.beforeMoveToLeft(r.$left,r.$right,e)?(e.each(function(e,o){var i=t(o);if(i.is("optgroup")||i.parent().is("optgroup")){var n=i.is("optgroup")?i:i.parent(),l=r.$left.find('optgroup[label="'+n.prop("label")+'"]');l.length||(l=n.clone(),l.children().remove()),i=l.append(i.is("optgroup")?i.children():i),n.removeIfEmpty()}r.$left.move(i)}),n||(r.undoStack.push(["left",e]),r.redoStack=[]),"function"!=typeof r.callbacks.sort||i||r.$left.mSort(r.callbacks.sort),"function"!=typeof r.callbacks.afterMoveToLeft||i||r.callbacks.afterMoveToLeft(r.$left,r.$right,e),r):!1},undo:function(t){var e=this,o=e.undoStack.pop();if(o)switch(e.redoStack.push(o),o[0]){case"left":e.moveToRight(o[1],t,!1,!0);break;case"right":e.moveToLeft(o[1],t,!1,!0)}},redo:function(t){var e=this,o=e.redoStack.pop();if(o)switch(e.undoStack.push(o),o[0]){case"left":e.moveToLeft(o[1],t,!1,!0);break;case"right":e.moveToRight(o[1],t,!1,!0)}}},e}(t);t.multiselect={defaults:{startUp:function(t,e){e.find("option").each(function(e,o){var i=t.find('option[value="'+o.value+'"]'),n=i.parent();i.remove(),"OPTGROUP"==n.prop("tagName")&&n.removeIfEmpty()})},beforeMoveToRight:function(t,e,o){return!0},afterMoveToRight:function(t,e,o){},beforeMoveToLeft:function(t,e,o){return!0},afterMoveToLeft:function(t,e,o){},sort:function(t,e){return"NA"==t.innerHTML?1:"NA"==e.innerHTML?-1:t.innerHTML>e.innerHTML?1:-1}}};var o=window.navigator.userAgent,i=o.indexOf("MSIE ")+o.indexOf("Trident/")+o.indexOf("Edge/")>-3;t.fn.multiselect=function(o){return this.each(function(){var i=t(this),n=i.data("crlcu.multiselect"),r=t.extend({},t.multiselect.defaults,i.data(),"object"==typeof o&&o);n||i.data("crlcu.multiselect",n=new e(i,r))})},t.fn.move=function(t){return this.append(t).find("option").prop("selected",!1),this},t.fn.removeIfEmpty=function(){return this.children().length||this.remove(),this},t.fn.mShow=function(){return this.removeClass("hidden").show(),i&&this.each(function(e,o){t(o).parent().is("span")&&t(o).parent().replaceWith(o),t(o).show()}),this},t.fn.mHide=function(){return this.addClass("hidden").hide(),i&&this.each(function(e,o){t(o).parent().is("span")||t(o).wrap("<span>").hide()}),this},t.fn.mSort=function(t){return this.find("option").sort(t).appendTo(this),this},t.expr[":"].search=function(e,o,i){return t(e).text().toUpperCase().indexOf(i[3].toUpperCase())>=0}});

jQuery(document).ready(function($) { $('#multiselect').multiselect(); });
</script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://raw.githubusercontent.com/crlcu/multiselect/master/js/multiselect.min.js"></script>

<?php  include("../include/footer.php");

if (isset($_POST['check_list']) && isset($_POST['select_Annee_scolaire']) && isset($_POST['Enregistrer']) && isset($_POST['select_Annee']) && isset($_POST['select_GROUP'])) {

  $grop_ID = $_POST["select_GROUP"];
  $Annee_scolaire = $_POST["select_Annee_scolaire"];


  // $result = mysqli_query($conn,$Query);

  include('../include/generateQR/qrlib.php');

  // how to save PNG codes to server
  
  $tempDir = "../qr_img/";
  

  $dateTIMEnow = date("Y-m-d H:i:s");
  $nameUSER = $_SESSION['login'][2];
  $prenomeUSER =  $_SESSION['login'][3];
  

  foreach($_POST['check_list'] as $Etu_ID) {

    // echo $Etu_ID;

    $codeContents = $Etu_ID;

    $Query_inscription = "INSERT INTO `inscription`(`Etu_ID`, `grop_ID`, `Annee_scolaire`) VALUES ($Etu_ID,$grop_ID,'$Annee_scolaire')";
  mysqli_query($conn,$Query_inscription);

  $Query_student = "SELECT * FROM etudiant WHERE ID=$Etu_ID";
  $result_student = mysqli_query($conn,$Query_student);
  $row_student = mysqli_fetch_assoc($result_student);
  $name_student = $row_student['Nom'];
  $Prenom_student = $row_student['Prenom'];

  $Query_nivoGROUYP = "SELECT groupe.ID, groupe.Nom, groupe.niveau_ID,niveau.ID,niveau.Annee FROM groupe JOIN niveau ON groupe.niveau_ID = niveau.ID WHERE groupe.ID = $grop_ID";
  $result_Query_nivoGROUYP = mysqli_query($conn,$Query_nivoGROUYP);
  $row_nivoGROUYP = mysqli_fetch_assoc($result_Query_nivoGROUYP);
  $namegroupqr = $row_nivoGROUYP["Nom"];
  $nameNIVO = $row_nivoGROUYP["Annee"];


  $Query_spy = "INSERT INTO `spy`(`Actor`, `Operation`, `Date`, `Victim`, `Information`, `ip_address`) VALUES ('$nameUSER $prenomeUSER','Insérer','$dateTIMEnow','$name_student $Prenom_student','Étudiant est inscrit au département: $nameNIVO _ groupe: $namegroupqr','$ip_address')";
  mysqli_query($conn,$Query_spy);
  
  // we need to generate filename somehow, 
  // with md5 or with database ID used to obtains $codeContents...
  $fileName = $codeContents.'.png';
  
  $pngAbsoluteFilePath = $tempDir.$fileName;
  $urlRelativeFilePath = $tempDir.$fileName;
  
  // generating
  if (!file_exists($pngAbsoluteFilePath)) {
      QRcode::png($codeContents, $pngAbsoluteFilePath,'L',10);
      echo 'File generated!';
      echo '<hr />';
  } else {
      echo 'File already generated! We can use this cached file to speed up site on common codes!';
      echo '<hr />';
  }
  

  $update = "UPDATE `etudiant` SET `statut`='1' ,`QR_code`='$urlRelativeFilePath' WHERE `ID` = $Etu_ID";

 mysqli_query($conn,$update);
    

 header("Refresh:0");

 ob_end_flush();  

    

}


}}else  if ( !isset($_SESSION['login'])) {
  header('Location: login.php');
}


?>
   



   <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".checkall").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

       
    $('#example').DataTable();
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

  $(document).ready(function(){

    
   
    });

</script>