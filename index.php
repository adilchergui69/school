<?php 
// if ( !isset($_SESSION['login'])) {
//     header('Location: pages/login.php');
// }

session_start();

if (isset($_SESSION['login']) && $_SESSION['login'][6] ==="Admin" ) {

    header('Location: pages/index.php');

 }else  if ( !isset($_SESSION['login'])) {
            header('Location: pages/login.php');
        }?>
   