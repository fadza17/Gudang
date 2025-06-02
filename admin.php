<?php

//jika belum login
if(isset($_SESSION['role'])&& $_SESSION['role']=='admin'){

}else{
    header('location:outside.php');

};


?>