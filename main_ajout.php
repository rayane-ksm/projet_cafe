<?php
require "function.php";
// var_dump($_POST);

// $connect = connexion_bdd();
insertion_commande($connect);
header('Location:index.php');
?>