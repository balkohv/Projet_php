<?php
if(!isset($_COOKIE['user'])){
    header("location: login.php");
}else{
    header("location: consultation/visualisationConsultations.php");
}
?>