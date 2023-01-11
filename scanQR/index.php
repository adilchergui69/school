



<?php session_start();

// if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {


?>

<html>
  <head>
    <title>QRscan &ndash; Paiements</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  </head>
  <body>
    
    <div id="app">
      <div class="sidebar">
        <section class="cameras">
          <h2 class="bg-primary">Cameras</h2>
          <ul>
            <li v-if="cameras.length === 0" class="empty">No cameras found</li>
            <li v-for="camera in cameras">
              <span v-if="camera.id == activeCameraId" :title="formatName(camera.name)" class="active">{{ formatName(camera.name) }}</span>
              <span v-if="camera.id != activeCameraId" :title="formatName(camera.name)">
                <a @click.stop="selectCamera(camera)">{{ formatName(camera.name) }}</a>
              </span>
            </li>
          </ul>
        </section>
        <section class="scans">
          <h2 class="bg-primary">historique</h2>
          <ul v-if="scans.length === 0">
            <li class="empty">Pas encore d'analyses</li>
          </ul>
          <transition-group name="scans" tag="ul">
          <li v-for="scan in scans" :key="scan.date" :title="scan.content">{{ scan.content }}</li>
          

          </transition-group>

          <div> 
           
          </div>
        </section>
 
      </div>


      <div class="preview-container">
        <video id="preview"></video>
       
        <form >
          <div class="input-group mb-3 mt-3">
          <select class="form-control mr-3" id="select" aria-label="Default select example">
                <option value="Mensuel">Paiement Mensuel</option>
                <option value="Activités">Paiement Activités</option>
          </select>    
          <input type="number" class="form-control" id="id_student"  placeholder="ID etudiant" name="id" aria-label="ID etudiant" aria-describedby="basic-addon2">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary bg-success text-white" name="submit" id="submit" type="button">soumettre</button>
            </div>
            
          </div>
          </form>

      </div>
    </div>
    
    <script type="text/javascript" src="app.js"></script>
    <script type="text/javascript">
      let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
      scanner.addListener('scan', function (content) {
        $("#id_student").val(content)
        // alert(content)
      });
      Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
          scanner.start(cameras[0]);
        } else {
          console.error('No cameras found.');
        }
      }).catch(function (e) {
        console.error(e);
      });

      $( "#submit" ).click(function() {
 var  value_input = $("#id_student").val();


if ($("#select").val() == "Activités") {
  
  var link = "../pages/payment.php?ids=" + value_input 

}

if ($("#select").val() == "Mensuel") {

  var link = "../pages/payment.php?id=" + value_input 
  
}
  

  // alert(link);
  window.open(link, "_blank");

});
    </script>




  </body>
</html>

<?php ?>