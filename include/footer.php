<?php 
if ( !isset($_SESSION['login'])) {
    header('Location: login.php');
}
 
 if ( isset($_SESSION['login']) && $_SESSION['login'][6] ==="Director" || isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {
    
    ?>

    </div>
</div>
       


    </div><!-- Main Col END -->
    
</div><!-- body-row END -->
<!--   Core JS Files   -->
<script>

// Hide submenus
$('#body-row .collapse').collapse('hide'); 

// Collapse/Expand icon
$('#collapse-icon').addClass('fa-angle-double-left'); 

// Collapse click
$('[data-toggle=sidebar-colapse]').click(function() {
    SidebarCollapse();
});

function SidebarCollapse () {
    $('.menu-collapsed').toggleClass('d-none');
    $('.sidebar-submenu').toggleClass('d-none');
    $('.submenu-icon').toggleClass('d-none');
    $('#sidebar-container').toggleClass('sidebar-expanded sidebar-collapsed');
    
    // Treating d-flex/d-none on separators with title
    var SeparatorTitle = $('.sidebar-separator-title');
    if ( SeparatorTitle.hasClass('d-flex') ) {
        SeparatorTitle.removeClass('d-flex');
    } else {
        SeparatorTitle.addClass('d-flex');
    }
    
    // Collapse/Expand icon
    $('#collapse-icon').toggleClass('fa-angle-double-left fa-angle-double-right');
}


</script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" type="text/javascript"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: https://www.jque.re/plugins/version3/bootstrap.switch/ -->

<SCript>$(window).on("load",function(){
     $(".loader-wrapper").fadeOut("slow");

     var activePagef = window.location.pathname.split("/").pop();;
    

     if (activePagef === "Suppl%C3%A9ment.php") {
        activePagef= "Supplément.php"
     }

     $('#sidebar-container a[href="'+activePagef+'"]').attr('style', 'background-color: <?php  echo $color_blue ; ?> !important');

     
});





</SCript>


</body>

</html>

<?php } ?>